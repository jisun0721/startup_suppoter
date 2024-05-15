<?php

namespace Support\FrontBundle\Services;

//use Lsw\ApiCallerBundle\Call\HttpGetJson;
use Lsw\ApiCallerBundle\Call\HttpPostJson;
use Symfony\Component\DependencyInjection\SimpleXMLElement;

class NaverAPI {
    
   private static $config = array(
        "client" => "pNxiq9oHRcoF_4yXL4bk",
        "secret" => "pB07dGkYe_"
    );
    
    public function getConfig($key){
        return self::$config[$key];
    }
            
    // CSRF 방지를 위한 상태 토큰 생성 코드
    // 상태 토큰은 추후 검증을 위해 세션에 저장되어야 한다.

    public function generateState() {
        $mt = microtime();
        $rand = mt_rand();
        return md5($mt . $rand);
    }
    
    //로그인 후 처리
    public function loginProcess($controller, $code, $state){      
        $userData = array();
        //https://nid.naver.com/oauth2.0/token?grant_type=authorization_code&client_id=".$client_id."&client_secret=".$client_secret."&redirect_uri=".$redirectURI."&code=".$code."&state=".$state
        $tokenUrl = "https://nid.naver.com/oauth2.0/token";
        $redirectURI = urlencode("http://www.startupsupporter.net/app_dev.php/login/naver");
        $tokenParam = array(    
            "client_id" => self::$config["client"],
            "client_secret" =>self::$config["secret"],
            "grant_type" => "authorization_code",
            "state" => $state,
            "code" => $code,
            "redirect_uri" => $redirectURI
        );


      //  $result = $controller->get("api_caller")->call(new HttpPostJson($tokenUrl, $tokenParam));
        $result = "https://nid.naver.com/oauth2.0/token?grant_type=authorization_code&client_id=".$tokenParam["client_id"]."&client_secret=".$tokenParam["client_secret"]."&redirect_uri=".$redirectURI."&code=".$tokenParam["code"]."&state=".$tokenParam["state"];

        if(isset($result->error) || is_null($result)){
            echo "Login Error";
        }else{
         //사용자 정보
//            $userUrl = "https://apis.naver.com/nidlogin/nid/getUserProfile.xml";
            $is_post = false;
            $curl = curl_init();
//            curl_setopt($curl, CURLOPT_URL, $userUrl); // URLをセット
//            curl_setopt($curl, CURLOPT_HEADER, "Content-Type:application/xml");
//            curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization : Bearer ".$result->access_token));
//            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // curl_exec()の結果を文字列で返す
//            $data =curl_exec($curl);
//            curl_close($curl);
            curl_setopt($curl, CURLOPT_URL, $result);
            curl_setopt($curl, CURLOPT_POST, $is_post);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $headers = array();
            $data = curl_exec ($curl);
            $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
         //   echo "status_code:".$status_code."";
            curl_close ($curl);
            if($status_code == 200) {
//                var_dump((array)json_decode($data));
                $arrayData = (array)json_decode($data);
                $token = $arrayData["access_token"];
                $header = "Bearer ".$token; // Bearer 다음에 공백 추가
                $userInfoUrl = "https://openapi.naver.com/v1/nid/me";
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $userInfoUrl);
                curl_setopt($ch, CURLOPT_POST, $is_post);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $headers = array();
                $headers[] = "Authorization: ".$header;
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                $response = curl_exec ($ch);
                $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close ($ch);
                if($status_code == 200) {
                  //  echo $response;
                } else {
                    echo "프로필 요청 Error 내용:".$response;
                }
            } else {
                echo "토큰 요청 Error 내용:".$data;
            }
            $arrayR = (array)json_decode($response);
            $arrayResponse = $arrayR["response"];
            if($arrayR["resultcode"] !="00"){
                  return 0;
             }else{
                $userData = array (
                    "enc_id" => $arrayResponse->enc_id, //(string)$arrayResponse["enc_id"],
                    "email" => $arrayResponse->email,//(string)$arrayResponse["email"],
                    "nickname" => $arrayResponse->nickname,//(string)$arrayResponse["nickname"],
                   // "profile_image" => (string)$xmlData->response->profile_image,
                    "age" => $arrayResponse->age,//(string)$arrayResponse["age"],
                  //  "birthday" => (string)$xmlData->response->birthday,
                    "gender" => $arrayResponse->gender,//(string)$arrayResponse["gender"],
                );
            }
            
        }
        
        return $userData;
    }
}
