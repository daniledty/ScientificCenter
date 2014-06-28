<?php
namespace Home\Controller;
use Think\Controller;
class AdminController extends Controller {
    public function index(){
		$this->display();
    }
	public function publish() {
        $ch = curl_init();
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wxbbd0b2dd8005f725&secret=6d8834f69ee1fadb98213a872036ddcf";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $response = json_decode(curl_exec($ch));
        curl_close($ch);
        $access_token = $response->access_token;
        echo $access_token;
        echo '<br/>';
        
        $ch = curl_init();
        $url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=".$access_token;
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        echo $response;
        echo '<br/>';
        $response = json_decode($response);
        $users = $response->data->openid;
        
        
        foreach ($users as $user) {
            $message = sprintf('{"touser":"%s","msgtype":"news","news":{"articles":[{"title":"%s","description":"%s","url":"http://www.baidu.com/","picurl":"http://www.baidu.com/img/baidu_sylogo1.gif"}]}}',
                $user, $_POST['title'], $_POST['body']);
            $ch = curl_init();
            $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$access_token;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $message);
            $response = curl_exec($ch);
            curl_close($ch);
            echo $message;
            echo '<br/>';
            echo $response;
            echo '<br/>';
        }
        header('HTTP/1.1 301 Moved Permanently');    
        header('Location:index');

    }
}


