<? 
namespace Programmers4u\gatesms\sender;

/*******************************************************************
*   SKRYPT WEBAPI (sms api) DO WYSYŁANIA SMS GATESMS.EU            *
*   Właścicielem i autorem skryptu jest Marcin Kania               *
*   http://www.gatesms.eu  2002 - 2011 Programmers4u               *                                         
********************************************************************/  

class SmsSender {
    private $to; 
    private $from; 
    private $smsTyp; 
    private $login; 
    private $pass;
    private $pl;
    private $wap;
    private $transaction;
    private $test;
    private $contact;
    private $selfNumber;
    private $secure; 
    private $time;
    private $name;
    private $import;
    private $hlr;
    private $massGroup;
    private $email;
    private $pakiet;
    private $msg_info;
    private $smsConnect;
    private $msg;
    private $servers;
    private $server;
                
    public function SmsSender() {
        $this->smsTyp="sms";
        $this->from="";
        $this->wap="";
        $this->pl=0;      
        $this->test=0;  
        $this->msg_info=0;  
        $this->transaction=0;
        $this->import=0;
        $this->contact=0;
        $this->name="";
        $this->email="";
        $this->pakiet="";
        $this->selfNumber=-1;
        $this->secure=0;
        $this->time=time();
        $this->smsConnect=null;
        $this->server=0;
        $this->servers=array('gatesms.eu');
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
        
    public function setSmsTyp($typ="sms") {
        $this->smsTyp=$typ;
    }
        
    public function setTo($to) {
        $this->to=$to;
    }

    public function setPL($pl) {
        $this->pl=$pl;
    }

    public function setWap($wap) {
        $this->wap=$wap;
    }

    public function setTest($test=1) {
        $this->test=$test;
    }

    public function setContact($contact=1) {
        $this->contact=$contact;
    }

    public function setTransaction($transaction=1) {
        $this->transaction=$transaction;
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
        $this->msg=($this->smsTyp!='premium') ? strtr($msg,$replacement) : $msg;	
    }
        
    public function ChangeServer($id) {
        $this->server=$id;	
    }
                
    private function Connection() {
        	
        $url=($this->secure)?"https://".$this->servers[$this->server]."/sms_api.php":"http://".$this->servers[$this->server]."/sms_api.php";
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
            curl_setopt($this->smsConnect, CURLOPT_URL, $url);
        };	
    }
        
    public function checkNumber($number) {
        $fields="spr_numer=".$number;
        $fields.="&full=1";
        if(!$this->smsConnect) $this->Connection();            
        curl_setopt($this->smsConnect, CURLOPT_POSTFIELDS, $fields);
        return curl_exec($this->smsConnect);
    }
        
    public function checkCredit() {
        $fields="login=".$this->login;
        $fields.="&pass=".$this->pass;
        $fields.="&check_credit=1";                                     
        if(!$this->smsConnect) $this->Connection();
        curl_setopt($this->smsConnect, CURLOPT_POSTFIELDS, $fields);
        return curl_exec($this->smsConnect);
    }
        
    public function sendSms() {
        $out="";
        $fields="login=".$this->login;
        $fields.="&pass=".$this->pass;
        if($this->pakiet=='') {
        	$fields.="&msg=".$this->msg; 
        	$fields.="&to=".$this->to;
        } else {
        	$fields.="&pakiet=".$this->pakiet;
        };
        $fields.="&sms_type=".$this->smsTyp;
        $fields.="&from=".$this->from;
        $fields.="&pl=".$this->pl; 
        $fields.="&wap=".$this->wap; 
        $fields.="&transaction=".$this->transaction; 
        $fields.="&test=".$this->test; 
        $fields.="&msg_info=".$this->msg_info; 
        $fields.="&contact=".$this->contact; 
        $fields.="&self_number=".$this->selfNumber; 
        $fields.="&time=".$this->time;             
        $fields.="&name=".$this->name;
        $fields.="&import=".$this->import;             
        $fields.="&email=".$this->email;                                      
			
        if(!$this->smsConnect) $this->Connection();
        curl_setopt($this->smsConnect, CURLOPT_POSTFIELDS, $fields);
        return curl_exec($this->smsConnect);
    }

    public function ConnectClose() {
        if($this->smsConnect) curl_close($this->smsConnect);
    }

    public function __desctruct() {
        $this->ConnectClose();
    }

};
?>