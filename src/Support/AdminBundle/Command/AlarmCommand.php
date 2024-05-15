<?php

namespace Support\AdminBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Support\FrontBundle\Controller\PushController;

/**
 * Description of AlermCommand
 *
 * @author Administrator
 */
class AlarmCommand  extends ContainerAwareCommand{

    protected function configure()
    {
        $this
            ->setName('alarm:push')
            ->setDescription('run GCM Pusher')
//            ->addArgument('name', InputArgument::OPTIONAL, 'Who do you want to greet?')
//            ->addOption('yell', null, InputOption::VALUE_NONE, 'If set, the task will yell in uppercase letters')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $apiKey = "AIzaSyA2lefZgayJpgDBdBJGodSmdz0oZ-1U96M";

        $url = "https://android.googleapis.com/gcm/send";
        
        $order = "created";
        $asc = "DESC";
        
        $onedayleft = array();
        $threedaysleft = array();
        
        $products = $this->getContainer()->get('doctrine')->getRepository('SupportAdminBundle:Product')->loadProduct(0, $order, $asc);
        $now = time();
        foreach($products as $product) {
            if($now == $product->getRecCloseTime() - 60*60*24*2)
                $threedaysleft[] = $product;
            else if ($now == $product->getRecCloseTime())
                $onedayleft[] = $product;
        }
        
        foreach($onedayleft as $product){
            $bookmarks = $this->getContainer()->get('doctrine')->getRepository('SupportFrontBundle:UserBookMark')->findBy(array("mark_product"=>$product));
            $gcmTokens = array();
            foreach($bookmarks as $bookmark){
                
                $user = $this->getContainer()->get('doctrine')->getRepository('SupportFrontBundle:User')->find($bookmark->getUser()->getId());
                
                if($user->getGcmToken() != null)
                    $gcmTokens[] = $user->getGcmToken();
            }
            
            $devide_gcmTokens = array_chunk($gcmTokens, 1000);
                
            foreach($devide_gcmTokens as $regIds){
                $data = array(
                    'collapse_key'=> $this->generateRandomString(10),
                    'registration_ids' => $gcmTokens,
                    'data'=>array("message"=>"오늘 마감인 프로젝트 : ".$product->getEventName()),
                    'notification'=>array("title"=>"StartupSupporter", "body"=>"오늘 마감인 프로젝트 : ".$product->getEventName(), "sound"=>"default")
                );

                $headers = array(
                    "Authorization: key=".$apiKey,
                    'Content-Type: application/json'
                );

                $ch = curl_init();

                curl_setopt( $ch, CURLOPT_URL, $url );
                curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );
                curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
                curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
                curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
                curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode($data) );

                $response = curl_exec($ch);
                curl_close($ch);
            }
        }

        foreach($threedaysleft as $product){
            $bookmarks = $this->getContainer()->get('doctrine')->getRepository('SupportFrontBundle:UserBookMark')->findBy(array("mark_product"=>$product));
            $gcmTokens = array();
            foreach($bookmarks as $bookmark){
                
                $user = $this->getContainer()->get('doctrine')->getRepository('SupportFrontBundle:User')->find($bookmark->getUser()->getId());
                
                if($user->getGcmToken() != null)
                    $gcmTokens[] = $user->getGcmToken();
            }
            
            $devide_gcmTokens = array_chunk($gcmTokens, 1000);
                
            foreach($devide_gcmTokens as $regIds){
                $data = array(
                    'collapse_key'=> $this->generateRandomString(10),
                    'registration_ids' => $gcmTokens,
                    'data'=>array("message"=>"모집기간 3일 남음 : ".$product->getEventName()),
                    'notification'=>array("title"=>"StartupSupporter", "body"=>"오늘 마감인 프로젝트 : ".$product->getEventName(), "sound"=>"default")
                );

                $headers = array(
                    "Authorization: key=".$apiKey,
                    'Content-Type: application/json'
                );

                $ch = curl_init();

                curl_setopt( $ch, CURLOPT_URL, $url );
                curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );
                curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
                curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
                curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
                curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode($data) );

                $response = curl_exec($ch);
                curl_close($ch);
            }
        } 
    }    
    
    
        public function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    
}
