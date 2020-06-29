<?php
namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use app\models\Users;
use app\models\prizepraw;
use app\models\prizes;
use app\models\prizegot;

class SendMoneyController extends Controller
{
    public function actionIndex($count = 10)
    {
		echo 'Start send group count on '.$count.' '."\n";
		
		$prizegots = prizegot::find()->where(['ActionID' => 0, 'PrizeID' => 2])->limit($count)->all();
		if ( !empty($prizegots) )
		{
			foreach ( $prizegots as $unit )
			{
				$Users = Users::find()->where(['id' => $unit->UserID])->one();
				if ( !empty($Users) )
				{
					print "Send API request to UserID -> ".$Users->id."; Address: ".$Users->Address." Sum: $".$unit->Cost."   Status: ";
					
					//Тут надо или воспользоваться курлом, или же filegetcontents() для отправки запроса к АПИ и его анализу.
					
					print "Sent ";
					
					$unit->ActionID=1;
					$unit->save();
					
					print "save";
					
					print "\n";
				}
			}
		}
		
		return ExitCode::OK;
    }
}

