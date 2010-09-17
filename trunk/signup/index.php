<?php
include('common.php');

function between($str, $strStart, $strEnd) {
    $posStart = strpos($str, $strStart);
    $posEnd = strpos($str, $strEnd, $posStart);
    return substr($str, $posStart + strlen($strStart), $posEnd - $posStart - strlen($strStart));
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $twitter = req('https://twitter.com/signup', false, false);
    $recaptha_params = between($twitter, 'https://api-secure.recaptcha.net/challenge', '">');
    $recaptcha = req('https://api-secure.recaptcha.net/challenge'.$recaptha_params, false, false);
    
    $auth_key = between($twitter, '<input name="authenticity_token" type="hidden" value="', '" />');
    $challenge = between($recaptcha, "challenge : '", "',");
    echo 
'<!doctype html>
<html>
    <head>
        <title>Let\'s tear down this wall!</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link href="style.css" media="screen" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <form action="'.$_SERVER["REQUEST_URI"].'" method="POST">
            <input name="authenticity_token" type="hidden" value="'.$auth_key.'" />
            <input id="follow" name="follow" type="hidden" />
            <input name="recaptcha_challenge_field" type="hidden" value="'.$challenge.'" />
            <p>
                <label for="user_name">姓名（可以为中文）</label><br/>
                <input autocomplete="off" class="text_field" id="user_name" name="user[name]" size="30" type="text" /><span></span>
            <p>
            <p>
                <label for="user_screen_name">用户名（用于登录，只能是英文字母、数字及下划线的组合）</label><br/>
                <input autocomplete="off" class="text_field" id="user_screen_name" maxlength="15" name="user[screen_name]" size="15" type="text" /><span></span>
            </p>
            <p>
                <label for="user_user_password">密码（至少六位）</label><br/>
                <input autocomplete="off" class="text_field" id="user_user_password" name="user[user_password]" size="30" type="password" /><span></span>
            </p>
            <p>
                <label for="user_email">邮箱（请填写真实邮箱，注册成功后会收到注册邮件）</label><br/>
                <input autocomplete="off" class="text_field" id="user_email" name="user[email]" size="30" type="text" /><span></span>
            </p>
            <input name="user[send_email_newsletter]" type="hidden" value="1" />
            <p>
                <label for="recaptcha_response_field">输入下面的两个单词（以半角空格分开）</label><br/>
                <img src="image.php?c='.$challenge.'" alt="recaptcha" /><br/>                
            <input type="text" id="recaptcha_response_field" name="recaptcha_response_field" class="text_field">
            </p>
            <input name="user[send_email_newsletter]" type="hidden" value="0" />
            <p><input alt="我同意。创建我的账号。" class="btn btn-m" id="user_create_submit" name="commit" onclick="this.disabled=true,this.form.submit();" type="submit" value="创建账号" /></p>
        </form>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
        <script type="text/javascript" src="main.js"></script>
    </body>
</html>';
} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $error_msg = false;
    $fields = array( 'name', 'screen_name', 'user_password', 'email' );
    foreach ($fields as $field) {
        if (!strcmp($_POST['user'][$field], '')) {
            $error_msg = '所有选项均为必填。';
            break;
        }
    }
    if (!$error_msg && strlen($_POST['user']['user_password']) < 6) {
        $error_msg = '密码长度不够。';
    }

    if (!$error_msg) {
        $r = req('https://twitter.com/account/create', @file_get_contents('php://input'), false);    
        $err_msg = array(
            'You can\'t do that right now.' => '出现未知错误。',
            'Please try to match the 2 words shown above' => '验证码输入错误。',
            'has already been taken' => '用户名或邮箱已有人使用。',
            'is not a valid email address' => '邮箱格式不正确。'
        );
        foreach ($err_msg as $k => $v) {
            if (strpos($r, $k) !== false) {
                $error_msg = $v;
                break;
            }
        }
    }
    if (!$error_msg) {
        echo '注册成功，请到注册的邮箱中查收邮件。';
    } else {
        echo $error_msg.'请返回<a href="'.$_SERVER["REQUEST_URI"].'">注册页面</a>重试。';
    }
}
?>
