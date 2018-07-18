<?php
if (isset($_POST['loginButton'])) {
    //Login button was pressed
    $username = $_POST['loginUsername'];
    $password = $_POST['loginPassword'];

    $result = $account->login($username, $password);

    //成功時、ログインセッション保持してインデックスへ飛ばす
    if ($result == true) {
        $_SESSION['userLoggedIn'] = $username;
        header("Location: index.php");
    }
}
