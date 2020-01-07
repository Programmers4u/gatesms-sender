<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Programmers4u\gatesms\sms\sender\SmsSender;

final class smsSenderTest extends TestCase
{
    
    public function testLoginFiled(): void
    {
        $sms = new SmsSender();
        $sms->setTest(1);
        $sms->setLogin('');
        $sms->setPass('');
        $res = $sms->sendSms();
        $this->assertFalse($res);
    }

    public function testSendFiled(): void
    {
        $sms = new SmsSender();
        $sms->setTest(1);
        $sms->setLogin('a@a.pl');
        $sms->setPass('test');
        $sms->setTo('2222');
        $sms->setMsg('test');
        $res = $sms->sendSms();
        $this->assertIsArray($res);
    }

    public function testCheckCredit(): void {
        $sms = new SmsSender();
        $sms->setTest(1);
        $sms->setLogin('a@a.pl');
        $sms->setPass('test');
        $res = $sms->checkCredit();
        $this->assertIsString($res);
    }
}