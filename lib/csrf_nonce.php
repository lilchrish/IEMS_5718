<?php

session_start();

define('NONCE_SECRET','C5s6%C&^%Cz5c657xc7aSZ5e4244^Cs');

//generate salt
function generateSalt($length=10){
        $chars='qazwsxedcrfvtgbyhnujmikolp1029837456QAZWSXEDCRFVTGBYHNUJMIKOLP';
        $char_len=strlen($chars)-1;
        $output='';
        while(strlen($output)<$length){
            $output.=$chars[rand(0,$char_len)];
        }
        return $output;
}
//store Nonce
function storeNonce($form_id,$nonce){
        if(is_string($form_id)==false){
            throw new InvalidArgumentException("A valid Form ID is required");
        }
        $_SESSION['nonce'][$form_id]=md5($nonce);
        return true;
}
//hash tokens and return nonce
function generateNonce($length=10,$form_id,$expiry_time){
        $secret=NONCE_SECRET;

        if(is_string($secret)==false||strlen($secret)< 10){
            throw new InvalidArgumentException("A valid Nonce Secret is required");
        }
        $salt=generateSalt($length);
        $time=time()+(60*intval($expiry_time));
        $toHash=$secret.$salt.$time;
        $nonce=$salt.':'.$form_id.':'.$time.':'.hash('sha256',$toHash);

        //store Nonce
        storeNonce($form_id,$nonce);
        return $nonce;
}
        
function verifyNonce($nonce){
    $secret=NONCE_SECRET;

    $split=explode(':',$nonce);
    if(count($split)!==4){
        return false;
    }

    $salt=$split[0];
    $form_id=$split[1];
    $time=intval($split[2]);
    $oldHash=$split[3];

    if(time()>$time){
        return false;
    }

    if(isset($_SESSION['nonce'][$form_id])){
        if($_SESSION['nonce'][$form_id]!==md5($nonce)){
            return false;
        }
    }else{
        return false;
    }

    $toHash=$secret.$salt.$time;
    $reHashed=hash('sha256',$toHash);
    if($reHashed!==$oldHash){
        return false;
    }
        return true;
    }
?>
