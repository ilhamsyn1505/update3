<?php
date_default_timezone_set('Asia/Jakarta');
include "function.php";
echo color("green","[]      BISMILLAHIRRAHMANIRRAHIM      []\n");
echo color("yellow","[]          BY : KOMPLONK             []\n");
echo color("green","[]  Time  : ".date('[d-m-Y] [H:i:s]')."   []\n");
function change(){
        $nama = nama();
        $email = str_replace(" ", "", $nama) . mt_rand(100, 999);
        ulang:
        echo color("nevy","(•) Nomor : ");
        // $no = trim(fgets(STDIN));
        $nohp = trim(fgets(STDIN));
        $nohp = str_replace("62","62",$nohp);
        $nohp = str_replace("(","",$nohp);
        $nohp = str_replace(")","",$nohp);
        $nohp = str_replace("-","",$nohp);
        $nohp = str_replace(" ","",$nohp);

        if (!preg_match('/[^+0-9]/', trim($nohp))) {
            if (substr(trim($nohp),0,3)=='62') {
                $hp = trim($nohp);
            }
            else if (substr(trim($nohp),0,1)=='0') {
                $hp = '62'.substr(trim($nohp),1);
        }
         elseif(substr(trim($nohp), 0, 2)=='62'){
            $hp = '6'.substr(trim($nohp), 1);
        }
        else{
            $hp = '1'.substr(trim($nohp),0,13);
        }
        }
        otp:
        $data = '{"email":"'.$email.'@gmail.com","name":"'.$nama.'","phone":"+'.$hp.'","signed_up_country":"ID"}';
        $register = request("/v5/customers", null, $data);
        if(strpos($register, '"otp_token"')){
        $otptoken = getStr('"otp_token":"','"',$register);
        echo color("green","+] Kode verifikasi sudah di kirim")."\n";
        echo color("nevy","?] Otp: ");
        $otp = trim(fgets(STDIN));
        $data1 = '{"client_name":"gojek:cons:android","data":{"otp":"' . $otp . '","otp_token":"' . $otptoken . '"},"client_secret":"83415d06-ec4e-11e6-a41b-6c40088ab51e"}';
        $verif = request("/v5/customers/phone/verify", null, $data1);
        if(strpos($verif, '"access_token"')){
        echo color("green","+] Berhasil mendaftar\n");
        $token = getStr('"access_token":"','"',$verif);
        $uuid = getStr('"resource_owner_id":',',',$verif);
        echo color("green","+] Token : ".$token."\n");
        save("token.txt",$token);
        setpin:
         echo color("nevy","=============( SET PIN )=============")."\n";
         echo color("yellow","========( PIN ANDA = 112233 )========")."\n";
         echo color("green","+] Kode verifikasi sudah di kirim")."\n";
         $data2 = '{"pin":"112233"}';
         $getotpsetpin = request("/wallet/pin", $token, $data2, null, null, $uuid);
         otpsetpin:
         echo color("nevy","?] Otp set pin: ");
         $otpsetpin = trim(fgets(STDIN));
         $verifotpsetpin = request("/wallet/pin", $token, $data2, null, $otpsetpin, $uuid);
         $messageverifotpsetpin = fetch_value($verifotpsetpin,'"message":"','"');
         if(strpos($verifotpsetpin, 'OTP kamu tidak berlaku. Silakan masukkan OTP yang masih berlaku.')){
         echo color("red","-] Message: ".$messageverifotpsetpin)."\n";
         goto setpin;
         }else{
         echo color("green","+] Message: +] SUKSES!!!");
         }
         }else{
         echo color("red","-] Otp yang anda input salah\n");
         echo color("nevy","==============Register==============\n");;
         goto otp;
         }
         }else{
         echo color("red","Nomor Sudah Terdaftar/Salah !!!\n");
         echo color("nevy","==============Register==============\n");
         goto ulang;
 }
}
echo change()."\n"; ?>
