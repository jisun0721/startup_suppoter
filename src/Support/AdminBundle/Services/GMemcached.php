<?php

namespace Support\AdminBundle\Services;
use Memcache;
class GMemcached {
    //감사합니다.
    protected static $instance;
    protected static $configs = array(
           array('host' => '127.0.0.1', 'port' => 11211),   
//        array('host' => 'generis.dm1cky.0001.apne1.cache.amazonaws.com', 'port' => 11211),
    );
    
    const PREFIX_USER = 'support_user_';  
    const PREFIX_PAGE = "product_page_";
    const PREFIX_ALL = "product_all_";
    const PREFIX_RANKING = "product_buy_ranking";
    const PREFIX_INFOS = "info_";
    const PREFIX_DETAIL = "detail_";
    
    const MEM_LIMIT_USER  = 3600;
    const MEM_LIMIT_GREE_MODERATION = 3600; //とりあえず1時間をリミットにしておいた 監査OKは24時間、監査中は3時間キャッシュできる
    const MEM_LIMIT_GREE_PEOPLE = 3600; //1시간 제한 とりあえず1時間をリミットにしておいた
    
    public static function factory() {
        if (!self::$instance instanceof Memcache) {
            self::$instance = new Memcache();
            foreach(self::$configs as $config){
                self::$instance->addServer($config['host'], $config['port']);
            }
            
        }
        return self::$instance;
    }
    
    /**
     * memcachedに値を保存します。
     * 
     * $prefixと$keyを繋げたキーで$limitの間memcachedに値を保存します。
     * $prefixと$limitはEsocialMemcachedクラスの定数に定義します。
     * $keyは保存する値に応じて適切なものを指定してください。
     * 
     * @param string $key 固有のキー
     * @param mixed $data 保存するデータ
     * @param int $limit 0で期限なし
     * @param int $compressed デフォルトで圧縮する
     * @return bool
     */
    public static function set($key,$data,$limit= 0,$compressed=MEMCACHE_COMPRESSED){
        $instance = self::factory();
        $hashKey = self::generateHashKey($key);
        return $instance->set($hashKey, $data, $compressed, $limit);
    }
    
    /**
     * memcachedに保存された値を取り出す。失敗した場合FALSEを返す
     * 
     * @param string $key
     * @return mixed
     */
    public static function get($key){
        $instance = self::factory();
        $hashKey = self::getHashKey($key);
        return $instance->get($hashKey);
    }
    
    /**
     * 
     * @param string $key
     * @return bool
     */
    public static function delete($key){
        $instance = self::factory();
        $hashKey = self::getHashKey($key);
        return $instance->delete($hashKey);
    }
    
    public static function getHashKey($key){
        $instance = self::factory();
        $timestamp = $instance->get($key);
        return sha1($key.$timestamp);
    }
    
    public static function generateHashKey($key){
        $instance = self::factory();
        $timestamp = microtime();
        $instance->set($key,$timestamp,0,0);
        return sha1($key.$timestamp);
    }
}