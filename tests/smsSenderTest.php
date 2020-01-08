<?php
declare(strict_types=1);

$env = file_get_contents(dirname(__FILE__).'/.env');
if($env) {
    $env = json_decode($env);
} else {
    copy(dirname(__FILE__).'/.env.template',dirname(__FILE__).'/.env');
}

use PHPUnit\Framework\TestCase;
use Programmers4u\gatesms\sms\sender\SmsSender;

final class smsSenderTest extends TestCase
{

    public function testFields(): void
    {
        $sms = new SmsSender();
        $res = $sms->sendSms();
        $this->assertFalse($res);
    }

    public function testCheckCredit(): void {
        global $env;
        $sms = new SmsSender();
        $sms->setLogin($env->login);
        $sms->setPass($env->pass);
        $res = $sms->checkCredit();
        $this->assertIsArray(explode(';',$res));
    }
    
    public function testSendSMSBadLogin(): void {
        global $env;
        $sms = new SmsSender();
        $sms->setTest(1);
        $sms->setSelfNumber($env->selfnumber);
        $sms->setLogin('fake@login.com');
        $sms->setPass($env->pass);
        $sms->setTo($env->tonumber);
        $sms->setMsg($env->message);
        $res = $sms->sendSms();

        $out = $this->resultToArray($res);
        $this->assertEquals('001',$out[0]['Status']);    
    }

    public function testSendSMS(): void {
        global $env;
        $sms = new SmsSender();
        $sms->setTest(1);
        $sms->setSelfNumber($env->selfnumber);
        $sms->setLogin($env->login);
        $sms->setPass($env->pass);
        $sms->setTo($env->tonumber);
        $sms->setMsg($env->message);
        $res = $sms->sendSms();

        $out = $this->resultToArray($res);
        $this->assertEquals('002',$out[0]['Status']);    
    }

    public function testDeduplikatorSMS(): void {
        global $env;
        $sms = new SmsSender();
        $sms->setTest(1);
        $sms->setSelfNumber($env->selfnumber);
        $sms->setLogin($env->login);
        $sms->setPass($env->pass);
        $sms->setTo($env->tonumber);
        $sms->setMsg($env->message);
        $res = $sms->sendSms();

        $out = $this->resultToArray($res);
        $this->assertEquals('800',$out[0]['Status']);    
    }

    public function testWrongNumber(): void {
        global $env;
        $sms = new SmsSender();
        $sms->setTest(1);
        $sms->setSelfNumber($env->selfnumber);
        $sms->setLogin($env->login);
        $sms->setPass($env->pass);
        $sms->setTo(substr($env->tonumber,2));
        $sms->setMsg($env->message);
        $res = $sms->sendSms();

        $out = $this->resultToArray($res);
        $this->assertEquals('106',$out[0]['Status']);
    }

    private function resultToArray(string $result): array {
        $out = [];
        $res = explode(',',$result);
        foreach($res as $r) {
            $v = explode(":",$r);
            array_push($out,[ trim($v[0]) => trim($v[1]) ]);
        }
        return $out;
    }
}