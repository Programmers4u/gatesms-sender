<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Programmers4u\gatesms\sender\SmsSender;

final class smsSenderTest extends TestCase
{
    
    public function testSendSms(): void
    {
        $sms = new SmsSender();
        $sms->setTest(1);

        $sms->setLogin('');
        $sms->setPass('');

        $res = $sms->sendSms();

        $this->assertTrue(preg_match('/002/isU',$res));

    }

}