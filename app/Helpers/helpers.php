<?php

// WElcome

use App\Classes\Theme\Metronic;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

function welcome_word() {

    $return = '';
    /* This sets the $time variable to the current hour in the 24 hour clock format */
    $time = date("H");
    /* If the time is less than 1200 hours, show good morning */
    if ($time < "12") {
        $return =  "Good Morning, and have a nice day 🤗";
    } else
    /* If the time is grater than or equal to 1200 hours, but less than 1700 hours, so good afternoon */
    if ($time >= "12" && $time < "15") {
        $return =  "Good Afternoon, and have a nice day 😇";
    } else
    /* Should the time be between or equal to 1700 and 1900 hours, show good evening */
    if ($time >= "15" && $time < "19") {
        $return = "Good Afternoon. Hope you have an afternoon as lovely as you are 🥰";
    } else
    /* Finally, show good night if the time is greater than or equal to 1900 hours */
    if ($time >= "19") {
        $return =  "Good Evening, and keep spirit! 😆";
    }

    return ($return);
}
// end welcome

function get_avatar($str){
    $acronym    = '';
    $word       = '';
    $words      = preg_split("/(\s|\-|\.)/", $str);
    foreach($words as $w) {
        $acronym .= substr($w,0,1);
    }
    $word = $word . $acronym ;
    return $word;
}

// FUNCTION LDAP
function encryptPass($pass) {
    return encryptLDAP("$pass", "gek123", "iknowwhatyoudidlastnigth");
}

function encryptLDAP($password, $salt, $secret) {
    try {
        $salt = ($salt == null || strlen(trim($salt)) == 0) ? getRandomSalt() : $salt;
        while(strlen($salt) < 2) {
            $salt .= "A";
        }
        if(strlen($salt) > 2) {
                $salt = substr($salt,0,2);
        }
        $bsalt = array_slice(unpack("C*", "\0".$salt),1);
        $bpwd = array_slice(unpack("C*", "\0".$password),1);
        $digest = array_slice(unpack("C*", "\0".pack("H*",hash("md5", $salt.$secret))),1);

        $len = strlen($salt) + (count($digest) * ((count($bpwd) + 16) / 16));
        $i = 0;
        $p = 0;
        $j = 0;
        $result = array();
        for(;$i<count($bsalt);$i++) {
            $result[$i] = $bsalt[$i];
        }
        for(;$i<$len;$i++) {
            if($p < count($bpwd)) {
                $result[$i] = ($bpwd[$p] ^ $digest[($j % count($digest))]);
            } else {
                $result[$i] = (0 ^ $digest[($j % count($digest))]);
            }
            $j++;
            $p++;
        }
        return base64_encode(implode(array_map("chr", $result)));
    } catch(Exception $e){}
}

function doencrypt($password, $secret) {
    return encryptLDAP($password, null, $secret);
}

function decryptLDAP($password, $secret) {
    $decode = array_slice(unpack("c*", "\0".base64_decode($password)),1);
    $bsalt = array();
    $bsalt[0] = $decode[0];
    $bsalt[1] = $decode[1];

    $bxorp = array();
    for($x = 2; $x < count($decode); $x++) {
        $bxorp[$x - 2] = $decode[$x];
    }
    $salt = implode(array_map("chr", $bsalt));
    $digest = array_slice(unpack("c*", "\0".pack("H*",hash("md5", $salt.$secret))),1);

    $len = count($digest) * ((count($bxorp) + 15) / 16);
    $j = 0;
    $p = 0;
    for($i = 0; $i < $len; $i++) {
        if($p < count($bxorp)) {
            $bxorp[$p] = ($bxorp[$p] ^ $digest[($j % count($digest))]);
        }
        $p++;
        $j++;
    }

    $sDecode = implode(array_map("chr", $bxorp));
    return trim($sDecode);
}

function getRandomSalt(){
    $pattern = "/^[\w]+$/";
    $rand = chr("0");
    $salt = "";
    for($i=0; $i<2;){
        $rand=chr((getRandomInt()%62)."");
        preg_match($pattern,$rand, $matches);
        if(count($matches) == 0){
            continue;
        }
        $salt .= $matches[0];
        $i++;
    }
    return $salt;
}

function getRandomInt(){
    $n = rand(0,100000);
    return $n;
}
// END FUNCTION LDAP

// Toaster
function toaster($msg, $type, $title)
{
    return array(
        'title'         => $title,
        'message'       => $msg,
        'alert-type'    => $type
    );
}

// Cek Valid
function isValid($cek)
{
    $isValid = '';
    if($cek){
        $isValid = ' is-invalid';
    }
    return $isValid;
}

function ButtonSED($data, $route_type, $permission_type, $show = true)
{
    $button = '';
    if($show){
        $button .= '<a class="btn btn-icon btn-light btn-sm btn-hover-warning" href="'.  route($route_type.'.show',Crypt::encrypt($data->id)) .'" data-toggle="tooltip"  data-theme="dark" title="Show">
        '. Metronic::getSVGController("media/svg/icons/General/Settings-1.svg", "svg-icon-md svg-icon-warning") .'
        </a>';
    }
    if(auth()->user()->can($permission_type.'.edit')){
        $button .= ' <a class="btn btn-icon btn-light btn-sm btn-hover-primary" href="'.  route($route_type.'.edit',Crypt::encrypt($data->id)) .'" data-toggle="tooltip"  data-theme="dark" title="Edit">
                '. Metronic::getSVGController("media/svg/icons/Communication/Write.svg", "svg-icon-md svg-icon-primary") .'
        </a>';
    }
    if(auth()->user()->can($permission_type.'.delete')){
        $button .= ' <button class="btn btn-icon btn-light btn-sm btn-delete btn-hover-danger" data-remote="'. route($route_type.'.destroy', Crypt::encrypt($data->id)) .'" data-toggle="tooltip"  data-theme="dark" title="Delete">
            '. Metronic::getSVGController("media/svg/icons/General/Trash.svg", "svg-icon-md svg-icon-danger") .'
        </button>';
    }

    return $button;
}

function eventType($type)
{
    $return = "";
    switch ($type) {
    case 'created':
        $return = '<span class="label label-success label-pill label-inline mr-2">'.$type.'</span>';
        break;

    case 'updated':
        $return = '<span class="label label-warning label-pill label-inline mr-2">'.$type.'</span>';
        break;

    case 'deleted':
        $return = '<span class="label label-danger label-pill label-inline mr-2">'.$type.'</span>';
        break;
    default:
        # code...
        break;
    }

    return $return;
}

function createdAt($created)
{
    return "<b>". date('Y-m-d H:i:s', strtotime($created)) . "</b><br> " . Carbon::parse($created)->diffForHumans() . " ";
}

//function SAVE Permission
function savePermission($argv)
{
    // dd($argv);
    if($argv[0] == 'make:controller'){
        $type = explode('\\', $argv[1]);
        $type = end($type);
        $type = str_replace('Master', '', $type);
        $type = str_replace('Controller', '', $type);
        $type = Str::of($type)->kebab();
        $type = Str::lower($type);
        // dd($argv);
        if(isset($argv[2])){
            // if($argv[2] == '-r'){
                $permissions = [
                    $type . '.index',
                    $type . '.create',
                    $type . '.edit',
                    $type . '.delete',
                ];
                foreach ($permissions as $permission) {
                    Permission::findOrCreate($permission);
                }
                // default role
                $role = Role::findById(2);

                // sync permissions to role
                $role->syncPermissions(Permission::all());
            // }
        }
    }
}

function convertNumber($number){
    $number = str_replace(",",":",$number);
    $number = str_replace(".","",$number);
    $number = str_replace(":",".",$number);
    return $number;
}

function convertDate($date)
{
    return date('Y-m-d', strtotime($date));
}

function reverseDate($date)
{
    return date('d-m-Y', strtotime($date));
}

function statusDT($flag_id, $flag)
{
    if($flag_id){
        if($flag->level == '1'){
            return '<span class="label label-warning label-pill label-inline">'.$flag->description.'</span>';
        }elseif($flag->level < '50'){ //APPROVE
            // return '<label class="badge badge-success"><i class="fas fa-check-circle"></i> '. $flag->description .'</label>';
            return '<span class="label label-success label-pill label-inline">'.$flag->description.'</span>';
        }elseif($flag->level >= '50'){ //REJECT
            // return '<label class="badge badge-danger"><i class="fas fa-times-circle"></i> '. $flag->description .'</label>';
            return '<span class="label label-danger label-pill label-inline">'.$flag->description.'</span>';
        }
    }else{
        return '<label class="badge badge-primary"><i class="fas fa-save"></i> Save As Draft</label>';
    }
}

function convertDateMT($date)
{
    $date   = substr($date, 0, 6);
    $year   = substr($date, 0,2);
    $month  = substr($date, 2,2);
    $day    = substr($date, 4,2);
    $full_date = $year .'-'. $month .'-'. $day;

    return date("F d, Y", strtotime($full_date));
}

function konversi($x){

    $x = abs($x);
    $angka = array ("","satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
    $temp = "";

    if($x < 12){
     $temp = " ".$angka[$x];
    }else if($x<20){
     $temp = konversi($x - 10)." belas";
    }else if ($x<100){
     $temp = konversi($x/10)." puluh". konversi($x%10);
    }else if($x<200){
     $temp = " seratus".konversi($x-100);
    }else if($x<1000){
     $temp = konversi($x/100)." ratus".konversi($x%100);
    }else if($x<2000){
     $temp = " seribu".konversi($x-1000);
    }else if($x<1000000){
     $temp = konversi($x/1000)." ribu".konversi($x%1000);
    }else if($x<1000000000){
     $temp = konversi($x/1000000)." juta".konversi($x%1000000);
    }else if($x<1000000000000){
     $temp = konversi($x/1000000000)." milyar".konversi($x%1000000000);
    }

    return $temp;
}

function tkoma($x){
    $str = stristr($x,".");
    $ex = explode('.',$x);
    // dd($ex);
    $a = 0;
    if((@$ex[1]/10) >= 1){
     $a = abs($ex[1]);
    }
    $string = array("nol", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan",   "sembilan","sepuluh", "sebelas");
    $temp = "";

    $a2 = @$ex[1]/10;
    $pjg = strlen($str);
    $i =1;


    if($a>=1 && $a< 12){
     $temp .= " ".$string[$a];
    }else if($a>12 && $a < 20){
     $temp .= konversi($a - 10)." belas";
    }else if ($a>20 && $a<100){
     $temp .= konversi($a / 10)." puluh". konversi($a % 10);
    }else{
     if($a2<1){

      while ($i<$pjg){
       $char = substr($str,$i,1);
       $i++;
       $temp .= " ".$string[$char];
      }
     }
    }
    return $temp;
}

function terbilang($x){
    if($x<0){
        $hasil  = "minus ".trim(konversi($x));
    }else{
        $poin   = trim(tkoma($x));
        $hasil  = trim(konversi($x));
    }

    if($poin){
        if($poin != 'nol nol'){
            $hasil = $hasil." koma ".$poin;
        }
    }else{
        $hasil = $hasil;
    }
    return $hasil;
}
