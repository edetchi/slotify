<?php
class Account
{
    private $con;
    private $errorArray;

    public function __construct($con)
    {
        $this->con = $con;
        //エラー変数を初期化
        $this->errorArray = array();
    }

    //ログイン用メソッド、成功でtrue、失敗でfalse
    public function login($un, $pw)
    {
        $pw = md5($pw);

        $query = mysqli_query($this->con, "SELECT * FROM users WHERE username='$un' AND password='$pw'");

        if (mysqli_num_rows($query) == 1) {
            return true;
        } else {
            array_push($this->errorArray, Constants::$loginFailed);
            return false;
        }
    }
    //クラス外で呼び出す登録用メソッド
    public function register($un, $fn, $ln, $em, $em2, $pw, $pw2)
    {
        //各値のバリデーション
        $this->validateUsername($un);
        $this->validateFirstName($fn);
        $this->validateLastName($ln);
        $this->validateEmails($em, $em2);
        $this->validatePasswords($pw, $pw2);

        //エラーなしでDBに登録
        if (empty($this->errorArray) == true) {
            //Insert into db
            return $this->insertUserDetails($un, $fn, $ln, $em, $pw);
        } else {
            return false;
        }
    }

    //入力された場所にエラーを表示するためのメソッド
    public function getError($error)
    {
        //その場所のエラー$errorがエラー変数内になければ$errorを空白に
        if (!in_array($error, $this->errorArray)) {
            $error = "";
        }
        //エラー表示用のHTMLを返す（エラーがなくてもこのspanは表示される）
        return "<span class='errorMessage'>$error</span>";
    }

    //ユーザー情報を挿入するメソッド
    private function insertUserDetails($un, $fn, $ln, $em, $pw)
    {
        $encryptedPw = md5($pw);
        $profilePic = "assets/images/profile-pics/head_emerald.png";
        $date = date("Y-m-d");

        //usersのidを空欄''で挿入しようとしてもできないので、id以外のカラムを指定して挿入すること
        $result = mysqli_query($this->con, "INSERT INTO users (username, firstName, lastName, email, password, signUpDate, profilePic) VALUES ('$un', '$fn', '$ln', '$em', '$encryptedPw', '$date', '$profilePic')");

        return $result;
    }

    //以下バリデーション系メソッド
    private function validateUsername($un)
    {
        if (strlen($un) > 25 || strlen($un) < 5) {
            array_push($this->errorArray, Constants::$usernameCharacters);
            return;
        }

        $checkUsernameQuery = mysqli_query($this->con, "SELECT username FROM users WHERE username='$un'");
        if (mysqli_num_rows($checkUsernameQuery) != 0) {
            array_push($this->errorArray, Constants::$usernameTaken);
            return;
        }
    }

    private function validateFirstName($fn)
    {
        if (strlen($fn) > 25 || strlen($fn) < 2) {
            array_push($this->errorArray, Constants::$firstNameCharacters);
            return;
        }
    }

    private function validateLastName($ln)
    {
        if (strlen($ln) > 25 || strlen($ln) < 2) {
            array_push($this->errorArray, Constants::$lastNameCharacters);
            return;
        }
    }

    private function validateEmails($em, $em2)
    {
        if ($em != $em2) {
            array_push($this->errorArray, Constants::$emailsDoNotMatch);
            return;
        }

        if (!filter_var($em, FILTER_VALIDATE_EMAIL)) {
            array_push($this->errorArray, Constants::$emailInvalid);
            return;
        }

        $checkEmailQuery = mysqli_query($this->con, "SELECT email FROM users WHERE email='$em'");
        if (mysqli_num_rows($checkEmailQuery) != 0) {
            array_push($this->errorArray, Constants::$emailTaken);
            return;
        }
    }

    private function validatePasswords($pw, $pw2)
    {
        if ($pw != $pw2) {
            array_push($this->errorArray, Constants::$passwordsDoNoMatch);
            return;
        }

        if (preg_match('/[^A-Za-z0-9]/', $pw)) {
            array_push($this->errorArray, Constants::$passwordNotAlphanumeric);
            return;
        }

        if (strlen($pw) > 30 || strlen($pw) < 5) {
            array_push($this->errorArray, Constants::$passwordCharacters);
            return;
        }
    }
}
