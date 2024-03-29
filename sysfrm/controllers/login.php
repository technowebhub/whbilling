<?php
// *************************************************************************
// *                                                                       *
// * iBilling -  Accounting, Billing Software                              *
// * Copyright (c) Sadia Sharmin. All Rights Reserved                      *
// *                                                                       *
// *************************************************************************
// *                                                                       *
// * Email: sadiasharmin3139@gmail.com                                                *
// * Website: http://www.sadiasharmin.com                                  *
// *                                                                       *
// *************************************************************************
// *                                                                       *
// * This software is furnished under a license and may be used and copied *
// * only  in  accordance  with  the  terms  of such  license and with the *
// * inclusion of the above copyright notice.                              *
// * If you Purchased from Codecanyon, Please read the full License from   *
// * here- http://codecanyon.net/licenses/standard                         *
// *                                                                       *
// *************************************************************************
if (isset($routes['1'])) {
    $do = $routes['1'];
} else {
    $do = 'login-display';
}
switch($do){
    case 'post':
$username = _post('username');
$username = filter_var($username, FILTER_SANITIZE_STRING);
$username = addslashes($username);
$password = _post('password');
$password = addslashes($password);
if($username != '' AND $password != ''){
    $d = ORM::for_table('sys_users')->where('username',$username)->find_one();
    if($d){
     $d_pass = $d['password'];
        if(Password::_verify($password,$d_pass) == true){
            //Now check if OTP is enabled
            if($d['otp'] == 'Yes'){
                Otp::make($d['id']);
                $_SESSION['tuid'] = $d['id'];

                r2(U.'otp');
            }
            else{
                $_SESSION['uid'] = $d['id'];
                $d->last_login = date('Y-m-d H:i:s');
                $d->save();
                //login log

                _log('Login Successful '.$username,'Admin',$d['id']);

                $rd = $config['redirect_url'].'/';
//                if ((isset($routes['2'])) AND (($routes['2'] != ''))){
//                    $rd =  $routes['2'];
//                    exit($rd);
//                }

                r2(U.$rd);
            }

        }
        else{
            _msglog('e','Invalid Username or Password');
            _log('Failed Login '.$username,'Admin');
            r2(U.'login');
        }
    }
    else{

        _msglog('e','Invalid Username or Password');

        r2(U.'login/');
    }
}

else{
    _msglog('e','Invalid Username or Password');

    r2(U.'login/');
}


        break;

    case 'login-display':

        $ui->display('login.tpl');
        break;

    case 'forgot-pw':

        $ui->display('forgot-pw.tpl');
        break;

    case 'forgot-pw-post':
        $username = _post('username');
        $d = ORM::for_table('sys_users')->where('username', $username)->find_one();
        if ($d) {

            $xkey = _raid('10');
            $d->pwresetkey = $xkey;
            $d->keyexpire = time() + 3600;

            $d->save();

            $e = ORM::for_table('sys_email_templates')->where('tplname','Admin:Password Change Request')->find_one();

            $subject = new Template($e['subject']);
            $subject->set('business_name', $config['CompanyName']);
            $subj = $subject->output();
            $message = new Template($e['message']);
            $message->set('name', $d['fullname']);
            $message->set('business_name', $config['CompanyName']);
            $message->set('password_reset_link', U.'login/pwreset-validate/'.$d['id'].'/token_'.$xkey);
            $message->set('username', $d['username']);
            $message->set('ip_address', $_SERVER["REMOTE_ADDR"]);
            $message_o = $message->output();
            Notify_Email::_send($d['fullname'],$d['username'],$subj,$message_o);

            _msglog('s','Check your email to reset Password.');

            r2(U.'login/');

        } else {
            _msglog('e','User Not Found!');

            r2(U.'login/forgot-pw/');
        }

        break;

    case 'pwreset-validate':

        $v_uid = $routes['2'];
        $v_token = $routes['3'];
        $v_token = str_replace('token_','',$v_token);

        $d = ORM::for_table('sys_users')->find_one($v_uid);

        if($d){

            $d_token = $d['pwresetkey'];
            if($v_token != $d_token){
                r2(U.'login/','e','Invalid Password Reset Key!');
            }
            $keyexpire = $d['keyexpire'];
            $ctime = time();
            if ($ctime > $keyexpire) {
                r2(U.'login/','e','Password Reset Key Expired!');
            }
            $password = _raid('6');
            $npassword = Password::_crypt($password);

            $d->password = $npassword;
            $d->pwresetkey = '';
            $d->keyexpire = '0';
            $d->save();

            $e = ORM::for_table('sys_email_templates')->where('tplname','Admin:New Password')->find_one();

            $subject = new Template($e['subject']);
            $subject->set('business_name', $config['CompanyName']);
            $subj = $subject->output();
            $message = new Template($e['message']);
            $message->set('name', $d['fullname']);
            $message->set('business_name', $config['CompanyName']);
            $message->set('login_url', U.'login/');
            $message->set('username', $d['username']);
            $message->set('password', $password);
            $message_o = $message->output();
            Notify_Email::_send($d['fullname'],$d['username'],$subj,$message_o);

            _msglog('s','Check your email to reset Password.');

            r2(U.'login/');

            }

        break;


    default:
        $ui->display('login.tpl');
        break;
}

