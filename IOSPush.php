<?php
error_reporting(0);
set_time_limit(0);
 
class IOSPush
{
    private $alert = '';

    private $customData = array();
 
    private $group_size = 15;
 
    //证书
    private $certificate = '';
 
    //密码
    private $passphrase = '';
 
    //PUSH地址
    //private $push_url = 'ssl://gateway.push.apple.com:2195';
    private $push_url = 'ssl://gateway.sandbox.push.apple.com:2195';
 
    //feedback地址
    //private $feedback_url = 'ssl://feedback.push.apple.com:2196';
    private $feedback_url = 'ssl://feedback.sandbox.push.apple.com:2196';
 
    private $push_ssl = null;
    private $feedback_ssl = null;
 
    //是否获取feedback信息
    private $get_feedback_info = null;

    public function __set($prop, $value)
    {
        if (property_exists($this, $prop)) {
            $this->{$prop} = $value;
        }
    }
 
    public function __construct($alert, $customData)
    {
        $this->alert   = $alert;
        $this->customData = (array)$customData;
 
        $day = date('d', time());
        if($day % 9 == 0) {
            $this->get_feedback_info = true;
        } else {
            $this->get_feedback_info = false;
        }
    }
 
    public function push_message($tokens)
    {
        $this->open_push_ssl();
 
        $payload = $this->create_payload();

        //对device tokens信息进行分组
        $group_tokens = array_chunk($tokens, $this->group_size, true);
        $group_num    = count($group_tokens);
        $mark         = 0;
 
        $success_tokens = array();
        $feedback_tokens = array();

        foreach($group_tokens as $tokens) {
            $mark++;
            foreach($tokens as $token) {
                $msg = chr(0) . pack('n', 32) . pack('H*', $token) . pack('n', strlen($payload)) . $payload;
                $result = fwrite($this->push_ssl, $msg, strlen($msg));
 
                if(!$result) {
                    $this->close_push_ssl();
                    sleep(1);
                    $this->open_push_ssl();
                } else {
                    $success_tokens[] = $token;
 
                    if($this->get_feedback_info) {
                        if($this->feedback_info()) {
                            $feedback_tokens[] = $this->feedback_info();
                        }
                    }
                }
            }
 
            if($mark < $group_num) {
                $this->close_push_ssl();
                sleep(5);
                $this->open_push_ssl();
            }
        }
 
        $this->close_feedback_ssl();
        $this->close_push_ssl();
    }
 
    //链接push ssl
    private function open_push_ssl()
    {
        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'allow_self_signed', true);
        stream_context_set_option($ctx, 'ssl', 'verify_peer', false);
        stream_context_set_option($ctx, 'ssl', 'local_cert', $this->certificate);
        stream_context_set_option($ctx, 'ssl', 'passphrase', $this->passphrase);
 
        $this->push_ssl = stream_socket_client($this->push_url, $err, $errstr, 60, STREAM_CLIENT_CONNECT, $ctx);
 
        if(!$this->push_ssl) {
            echo "Failed to connect Apple Push Server {$err} {$errstr}! Please try again later.<br/>";
            exit();
        }
    }
 
    private function close_push_ssl()
    {
        fclose($this->push_ssl);
    }
 
    //根据实际情况，生成相应的推送信息，这里需要注意一下每条信息的长度最大为256字节
    private function create_payload()
    {
        $body = array();
        $body['aps'] = array(
                        'alert' => $this->alert,
                        'badge' => 1,
                        'sound' => 'default'
        );

        $body = array_merge($body, (array) $this->customData);
        
        return json_encode($body, JSON_UNESCAPED_UNICODE);
    }
 
    private function open_feedback_ssl()
    {
        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'allow_self_signed', true);
        stream_context_set_option($ctx, 'ssl', 'verify_peer', false);
        stream_context_set_option($ctx, 'ssl', 'local_cert', $this->certificate);
        stream_context_set_option($ctx, 'ssl', 'passphrase', $this->passphrase);
 
        $this->feedback_ssl = stream_socket_client($this->feedback_url, $err, $errstr, 60, STREAM_CLIENT_CONNECT, $ctx);
 
        if(!$this->feedback_ssl) {
            echo "Failed to connect Apple Feedback Server {$err} {$errstr}! Please try again later.<br/>";
            exit();
        }
    }
 
    private function close_feedback_ssl()
    {
        fclose($this->feedback_ssl);
    }
 
    private function feedback_info()
    {
        $this->open_feedback_ssl();
 
        while($devcon = fread($this->feedback_ssl, 38)) {
            $arr = unpack("H*", $devcon);
            $rawhex = trim(implode("", $arr));
            $feedbackTime = hexdec(substr($rawhex, 0, 8));
            $feedbackDate = date('Y-m-d H:i', $feedbackTime);
            $feedbackLen = hexdec(substr($rawhex, 8, 4));
            $feedbackDeviceToken = substr($rawhex, 12, 64);
        }
 
        if(is_null($feedbackDeviceToken)) {
            return $feedbackDeviceToken;
        } else {
            return false;
        }
    }
}