<?php 
/**
* login
*/
class Login
{
	public $email;
	public $password;
	public $rem;
	public $code;
	function __construct()
	{
		if (!isset($_POST['login'])) {
			echo "<script>alert('You access the page does not exist!');history.go(-1);</script>";
			exit();
		}
		require '../config.php';

		$this->email = $_POST['email'];
		$this->password = $_POST['password'];
		$this->code = $_POST['code'];
		$this->rem = $_POST['rem'];
	}

	public  function  checkMail(){
		//验证邮箱格式
		$pattern = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i";
		if (!preg_match($pattern,$this->email)) {
			echo "<script>alert('Email format incorrect.please try again!');history.go(-1);</script>";
			exit();
		}
	}

	public function checkPwd()
	{
		//验证密码格式
		if (!trim($this->password) == '') {
			$strlen = strlen($this->password);
			if ($strlen < 6 || $strlen > 20) {
				echo "<script>alert('Password length of illegal.please try again!');history.go(-1);</script>";
				exit();
			}else{
				$this->password = md5($this->password);
			}
		}else{
			echo "<script>alert('Password cannot be blank.please try again!');history.go(-1);</script>";
			exit();
		}
	}

	public function checkCode()
	{
		//验证码处理
		if ($this->code != $_SESSION['code']) {
			echo "<script>alert('Verification code is not correct.please try again!');history.go(-1);</script>";
			exit();
		}
	}

	public function checkUser()
	{

		//数据库验证

        $mysql_conf = array(
            'host'    => '127.0.0.1:3306',
            'db'      => 'daka',
            'db_user' => 'root',
            'db_pwd'  => 'root',
        );

        $mysqli = @new mysqli($mysql_conf['host'], $mysql_conf['db_user'], $mysql_conf['db_pwd']);
        $mysqli->query("set names 'utf8';");//编码转化
        $select_db = $mysqli->select_db($mysql_conf['db']);
        if (!$select_db) {
            die("could not connect to the db:\n" .  $mysqli->error);
        }
        //$sql = "SELECT username FROM human WHERE email = '123@qq.com' and password = 'dadada'";

		$sql = "SELECT username , userid FROM human WHERE email = '".$this->email."' and password = '".$this->password."'";
        $res = $mysqli->query($sql);
//        while ($row=$res->fetch_assoc())
//        {
//            echo ($row['username']);//这里的name是标的列名。
//        }


        if ($res) {


            if ($res->num_rows == 0) {
                echo "<script>alert('Email or password is incorrect.please try again!');history.go(-1);</script>";
                exit();
            } else {


                $row = $res->fetch_assoc();
                $_SESSION['username'] = $row['username'];
                $_SESSION['userid'] = $row['userid'];
                setcookie("username", $row['username'], time() + 3600);
                setcookie("userid", $row['userid'], time() + 3600);
                if ($this->rem == 1) {
                    $_SESSION['rem'] = '1';
                }
                echo "<script>alert('Login Success!');location.href = '/master/index.php'</script>";
                $mysqli->close();
                exit();
            }
        }
	}

	public function doLogin()
	{
		$this->checkCode();
		$this->checkMail();
		$this->checkPwd();
		$this->checkUser();
	}
}

$login = new Login();
$login->doLogin();

