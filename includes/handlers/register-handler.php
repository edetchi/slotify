<?php
//入力値のサニタイズはここで
function sanitizeFormPassword($inputText)
{
    $inputText = strip_tags($inputText);
    return $inputText;
}

function sanitizeFormUsername($inputText)
{
    $inputText = strip_tags($inputText);
    $inputText = str_replace(" ", "", $inputText);
    return $inputText;
}

function sanitizeFormString($inputText)
{
    $inputText = strip_tags($inputText);
    $inputText = str_replace(" ", "", $inputText);
    $inputText = ucfirst(strtolower($inputText));
    return $inputText;
}

//registerButtonボタンが押されたときの処理
if (isset($_POST['registerButton'])) {
    //各値をサニタイズ
    $username = sanitizeFormUsername($_POST['username']);
    $firstName = sanitizeFormString($_POST['firstName']);
    $lastName = sanitizeFormString($_POST['lastName']);
    $email = sanitizeFormString($_POST['email']);
    $email2 = sanitizeFormString($_POST['email2']);
    $password = sanitizeFormPassword($_POST['password']);
    $password2 = sanitizeFormPassword($_POST['password2']);

    //登録用関数を呼び出し（バリデーション、データベースへの登録、失敗時false）
    $wasSuccessful = $account->register($username, $firstName, $lastName, $email, $email2, $password, $password2);

    //登録成功時、ログインセッション保持してインデックスへ飛ばす
    if ($wasSuccessful == true) {
        $_SESSION['userLoggedIn'] = $username;
        header("Location: index.php");
    }
}
