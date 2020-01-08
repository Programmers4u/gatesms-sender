<? 
namespace Programmers4u\gatesms\sms\sender;

/*******************************************************************
*   SKRYPT WEBAPI (sms api) DO WYSYŁANIA SMS GATESMS.EU            *
*   Właścicielem i autorem skryptu jest Marcin Kania               *
*   http://www.gatesms.eu  2002 - 2011 Programmers4u               *                                         
********************************************************************/  

class SmsSender {

    private $to; 

    private $from; 

    private $login; 

    private $pass;

    private $test;

    private $selfNumber;

    private $secure; 

    private $time;

    private $smsConnect;

    private $msg;
    
    private $server;

    private $fields;

    private $url;

    private $report;
                
    public function __construct() {

        $this->from="";

        $this->test=0;  

        $this->selfNumber="";

        $this->secure=false;

        $this->time=time();

        $this->smsConnect=null;

        $this->server=['gatesms.eu'];

        $this->fields = null;

        $this->url=($this->secure)?"https://".$this->server[0]."/sms_api.php":"http://".$this->server[0]."/sms_api.php";

        $this->report = [
            'status' => 'ok', //ok | error
            'report' => '', //string | array
        ];
        $this->Connection();
    }
        
    public function setLogin($login) {
        $this->login=$login;
    }
        
    public function setPass($pass) {
        $this->pass=$pass;
    }
        
    public function setFrom($from) {
        $this->from=$from;
    }
        
    public function setTo($to) {
        $this->to=$to;
    }

    public function setTest($test=1) {
        $this->test=$test;
    }

    public function setTime($data='') {//eg. 2010-10-06 23:45:00
        if($data) {
        	$d=explode(" ",$data);
            $h=explode(":",$d[1]);
            $d=explode("-",$d[0]);
            $this->time=mktime($h[0],$h[1],$h[2],$d[1],$d[2],$d[0]);
        };
    }

    public function setSelfNumber($number=-1) {
        $this->selfNumber=$number;
    }
        
    public function setMsg($msg) {
        $replacement = array(
		    "&"=>"%26",
			"#"=>"%23",
			" "=>"%20"
        );
        $this->msg = strtr($msg,$replacement);	
    }
                        
    private function Connection() {
        	
		$header[0] = "Connection: keep-alive";
        $header[1] = "Keep-Alive: 300";		

        if(!$this->smsConnect) { 
            $this->smsConnect = curl_init();	
            if($this->secure) curl_setopt($this->smsConnect, CURLOPT_SSL_VERIFYPEER, 0); 	    
    		curl_setopt($this->smsConnect, CURLOPT_HTTPHEADER, $header);  			         
            curl_setopt($this->smsConnect, CURLOPT_POST, 1);
            curl_setopt($this->smsConnect, CURLOPT_USERAGENT, "SMSbot:(www.gatesms.eu)");
            curl_setopt($this->smsConnect, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($this->smsConnect, CURLOPT_FRESH_CONNECT, 1);
            curl_setopt($this->smsConnect, CURLOPT_TIMEOUT, 15);
            curl_setopt($this->smsConnect, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($this->smsConnect, CURLOPT_RETURNTRANSFER, 1); 
            curl_setopt($this->smsConnect, CURLOPT_URL, $this->url);
        };	
    }
                
    public function checkCredit() {
        $this->fields="login=".$this->login;
        $this->fields.="&pass=".$this->pass;
        $this->fields.="&check_credit=1";                                     
        if(!$this->smsConnect) $this->Connection();
        curl_setopt($this->smsConnect, CURLOPT_POSTFIELDS, $this->fields);
        return (string) curl_exec($this->smsConnect);
    }

    /**
     * sendSms function
     *
     * @return mixed
     */    
    public function sendSms() {

        if(!$this->login) return false;
        if(!$this->pass) return false;
        if(!$this->to) return false;
        if(!$this->msg) return false;

        $this->fields="login=".$this->login;
        $this->fields.="&pass=".$this->pass;

    	$this->fields.="&msg=".$this->msg; 
    	$this->fields.="&to=".$this->to;
        if($this->from)
            $this->fields.="&from=".$this->from;
        $this->fields.="&test=".$this->test; 
        if($this->selfNumber)
            $this->fields.="&self_number=".$this->selfNumber; 
        $this->fields.="&time=".$this->time;             
			
        if(!$this->smsConnect) $this->Connection();
        curl_setopt($this->smsConnect, CURLOPT_POSTFIELDS, $this->fields);
        $response = curl_exec($this->smsConnect);
        return $response;
    }

    public function dump() {        
        return $this->url.'?'.$this->fields;
    }

    private function ConnectClose() {
        if($this->smsConnect) curl_close($this->smsConnect);
    }

    public function __desctruct() {
        $this->ConnectClose();
    }

};
