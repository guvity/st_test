<?php

use app\models\Users;
use app\models\prizepraw;
use app\models\prizes;
use app\models\prizegot;

$this->title = 'Твой шанс';

if ( Yii::$app->user->isGuest )
{

?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Генератор призов – Твой шанс!</h1>

        <p class="lead">Добро пожаловать! На этом сайте вы с легкостью получите приз, для этого просто авторизуйтесь!</p>

        <p><a class="btn btn-lg btn-success" href="/slotergrator/index.php?r=site/login">Авторизоваться</a></p>
    </div>

</div>
<?php

} else
{

	$Users = Users::find()->where(['id' => Yii::$app->user->identity->id])->one();
	if ( !empty($Users) )
	{
		$request = Yii::$app->request;
		if ( $Users->NewUser == 1 && $request->get('a') == 'GetPrize' )
		{
			$WinnerID = 0;
			$MaxRaund = 100; $CurrRaund = 0;
			while ( $WinnerID == 0 )
			{
				//Производим розыгрышь призов
				
				$prizepraw = prizepraw::find()->all();
				$Curr_Probability = 0;
				foreach( $prizepraw as $unit )
				{
					$Type[$unit->PrizeTypeID]['Start'] = $Curr_Probability;
					$Type[$unit->PrizeTypeID]['Stop'] = $Curr_Probability + $unit->Probability;
					$Curr_Probability = $Curr_Probability + $unit->Probability;
				}
				
				// Разгрываем тип, согласно приоритетам. Так же процеряем наличие призов. пока не найдем победителя.
				$tmp = mt_rand(1, $Curr_Probability);
				
				$TypeWiner = 0;
				foreach( $prizepraw as $unit )
				{
					if ( $tmp > $Type[$unit->PrizeTypeID]['Start'] && $tmp <= $Type[$unit->PrizeTypeID]['Stop'] )
					{
						$TypeWiner = $unit->PrizeTypeID;
					}
				}
				
				
				$prizes = prizes::find()->where(['TypeID' => $TypeWiner])->all();
				if ( !empty($prizes) )
				{
					$tmp = mt_rand(0, count($prizes)-1);
					
					if ( $prizes[$tmp]->Avail == '' )
					{
						$WinnerID = $prizes[$tmp]->ID;
					} else
					{
						if ( (floatval($prizes[$tmp]->Avail) - floatval($prizes[$tmp]->TheUse)) > floatval($prizes[$tmp]->MinCost) )
						{
							$WinnerID = $prizes[$tmp]->ID;
						}
					}
				}
				
				$CurrRaund++;
				if ( $CurrRaund > $MaxRaund ) { break; }
			}
			
			$prizes = prizes::find()->where(['ID' => $WinnerID])->one();
			if ( !empty($prizes) )
			{
				if ( $prizes->MinCost == '' )
				{
					//Считаем товар не делимым целлым с интервалом 1шт.
					
					Yii::$app->db->createCommand()->insert('prizegot', [
							'UserID' => Yii::$app->user->identity->id,
							'Date' => date('Y-m-d H:i:s'),
							'PrizeID' => $WinnerID,
							'Cost' => 1,
							'ActionID' => 0
							])->execute();
					$prizes->TheUse = floatval($prizes->TheUse) + 1;
					$prizes->save();
					
					$Users = Users::find()->where(['id' => Yii::$app->user->identity->id])->one();
					$Users->NewUser = 0;
					$Users->ChangeDate = date('Y-m-d H:i:s');
					$Users->save();
					
				} else
				{
					//Товар делится и должна быть найдена его цена.
					
					//Определим остаток товара и его максимальную стоимось 
					if ( $prizes->Avail == '' ) { $remain = 100000; } else { $remain = floatval($prizes->Avail) - floatval($prizes->TheUse); }
					if ( $remain > $prizes->MaxCost ) { $max = $prizes->MaxCost; } else { $max = $remain; }
					$min = $prizes->MinCost;
					
					$sum = mt_rand($min, $max);
					
					Yii::$app->db->createCommand()->insert('prizegot', [
							'UserID' => Yii::$app->user->identity->id,
							'Date' => date('Y-m-d H:i:s'),
							'PrizeID' => $WinnerID,
							'Cost' => $sum,
							'ActionID' => 0
							])->execute();
					$prizes->TheUse = floatval($prizes->TheUse) + $sum;
					$prizes->save();
					
					
					//Конечно правильние было бы сделать что бы баллы зачислялись на счет на бэкенде специальным сервисом, но в этой задаче я зачисляю их сразу.
					
					$Users = Users::find()->where(['id' => Yii::$app->user->identity->id])->one();
					$Users->bpoint = floatval($Users->bpoint) + $sum;
					$Users->save();
					
					$Users = Users::find()->where(['id' => Yii::$app->user->identity->id])->one();
					$Users->NewUser = 0;
					$Users->ChangeDate = date('Y-m-d H:i:s');
					$Users->save();
					
				}
			}
			
		}
		
		
		
		$request = Yii::$app->request;
		if ( $Users->NewUser == 0 && $request->get('a') == 'ChangeMoney' )
		{
			$ChangeK = Yii::$app->params['Change'];
			
			
			$prizegot = prizegot::find()->where(['UserID' => Yii::$app->user->identity->id])->one();
			if ( !empty($prizegot) && $prizegot->ActionID == 0 )
			{
				$prizes = prizes::find()->where(['ID' => $prizegot->PrizeID])->one();
				if ( $prizes->ID == 2 )
				{
					
					$prizegot->ActionID = 10;
					$prizegot->save();
					
					$sum = $prizegot->Cost * $ChangeK;
					Yii::$app->db->createCommand()->insert('prizegot', [
							'UserID' => Yii::$app->user->identity->id,
							'Date' => date('Y-m-d H:i:s'),
							'PrizeID' => 1,
							'Cost' => $sum,
							'ActionID' => 0
							])->execute();
					$prizes->TheUse = floatval($prizes->TheUse) + $sum;
					$prizes->save();
					
					$Users = Users::find()->where(['id' => Yii::$app->user->identity->id])->one();
					$Users->bpoint = floatval($Users->bpoint) + $sum;
					$Users->save();
				}
			}
		}
		
		$request = Yii::$app->request;
		if ( $Users->NewUser == 0 && $request->get('a') == 'Cancel' )
		{
			$ChangeK = Yii::$app->params['Change'];
			
			
			$prizegot = prizegot::find()->where(['UserID' => Yii::$app->user->identity->id])->one();
			if ( !empty($prizegot) && $prizegot->ActionID == 0 )
			{
				$prizes = prizes::find()->where(['ID' => $prizegot->PrizeID])->one();
				if ( $prizes->ID > 2 )
				{
					
					$prizegot->ActionID = 11;
					$prizegot->save();
				}
			}
		}
	}

	$Users = Users::find()->where(['id' => Yii::$app->user->identity->id])->one();
	if ( !empty($Users) )
	{
		
		if ( $Users->NewUser == 1 )
		{
			
			?>
				<div class="site-index">
					<div class="jumbotron">
						<p class="lead">Вы еще не получили приз, нажмите кнопку для его получения!</p>
						<p><a class="btn btn-lg btn-success" href="/slotergrator/index.php?r=site/index&a=GetPrize">Получить!</a></p>
					</div>
				</div>
			<?php
			
		} else
		{
			$prizegot = prizegot::find()->where(['UserID' => Yii::$app->user->identity->id])->orderBy(['ID' => SORT_DESC])->one();
			if ( !empty($prizegot) )
			{
				$prizes = prizes::find()->where(['ID' => $prizegot->PrizeID])->one();
			?>
				<div class="site-index">
					<div class="jumbotron">
						<h1>Поздравляем вы получили приз – <?php echo $prizes->desc ?>!</h1>
						<?php 
							if ( $prizes->MinCost == '')
							{
								?><p class="lead">Приз вам будет отправлен почтой, по указанному вами адресу.</p><?php
								if ( $prizegot->ActionID == 11 ) { ?><p class="lead">Вы отказались от приза! Нам очень жаль.</p><?php }
								if ( $prizegot->ActionID == 0 ) { ?><p class="lead">Вы можете отказаться от приза перейдя по этой <a href="/slotergrator/index.php?r=site/index&a=Cancel">ссылке</a>.</p><?php }
							} else
							{
								if ( $prizes->ID == 1 )
								{
									?><p class="lead">Размер вашего приза: <?php echo $prizegot->Cost ?> баллов</p><?php
								} else
								{
									?><p class="lead">Размер вашего приза: $ <?php echo $prizegot->Cost ?> U.S. dollars</p><?php
									if ( $prizegot->ActionID == 0 ) { ?><p class="lead">Если вы хотите обменять деньги на баллы лояльности пожалуйста перейдите по <a href="/slotergrator/index.php?r=site/index&a=ChangeMoney">ссылке</a>.</p><?php }
									if ( $prizegot->ActionID == 1 ) { ?><p class="lead">Деньги уже были отпралены на ваш счет!</p><?php }
								}
							}
						?>
					</div>
				</div>
			<?php
			}
		}
		
	
	} else
	{
		?> <div class="site-index"> Произошла ошибка </div> <?php
	}
}