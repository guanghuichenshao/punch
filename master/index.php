<?php 
session_start();

if (!isset($_SESSION['username'])) {
  if (isset($_COOKIE['username'])) {
    $_SESSION['username'] = $_COOKIE['username'];
  }else{
    header('location:welcome.php');
    exit();
  }
}
if (isset($_SESSION['rem'])) {
  setcookie('username',$_SESSION['username'],time()+3600);
  unset($_SESSION['rem']);
}

?>
<!DOCTYPE html>
<script language="javascript">
    function MsgBox() //声明标识符
    {
        var arr="<?php $getUp;?>";
        if (arr==0){


        } else {
            alert("今天已经早起签到过了");
        }

    }
</script>
<style type="text/css">
    table {
        width: 80%;
        height: 60%;
    }
    table td {
        padding: 30px;
    }
</style>
<html lang="zh-CN">
  <!-- header部分 -->
  <?php require_once 'public/layouts/header.php' ?>

  <body>
  <!-- 导航栏 -->
  <?php require_once 'public/layouts/nav.php' ?>


  <?php


  //数据库验证
  $id = $_SESSION['userid'];
  $mysql_conf = array(
      'host' => '127.0.0.1:3306',
      'db' => 'daka',
      'db_user' => 'root',
      'db_pwd' => 'root',
  );

  $mysqli = @new mysqli($mysql_conf['host'], $mysql_conf['db_user'], $mysql_conf['db_pwd']);
  $mysqli->query("set names 'utf8';");//编码转化
  $select_db = $mysqli->select_db($mysql_conf['db']);
  if (!$select_db) {
      die("could not connect to the db:\n" . $mysqli->error);
  }

  $sql = "SELECT getUp,study,sleep,train FROM punch WHERE userid = $id";
  $res = $mysqli->query($sql);
  while ($row = $res->fetch_assoc()) {
      //echo($row['username']);//这里的name是标的列名。
      $getUp=$row['getUp'];
      $study=$row['study'];
      $sleep=$row['sleep'];
      $train=$row['train'];
      //echo "<script>alert(".$row['getUp'].")</script>";

  }

  $mysqli->close();

echo "
  <!-- 页面主体内容 -->
    <div class='container'>
      <div class='content'>
          <div class='starter-template'>
                <!-- 这里做了修改，其他地方自由发挥 -->
            <h1>Welcome To Hrbust Punch System</h1>
            <div class='jumbotron'>
              <h1>Hello, ".$_SESSION['username']."</h1>
                <table align='center'>
                    <tr>
                        <td>
                            <a onclick='MsgBox()' class='btn btn-primary btn-lg'  href='/master/punch.php?state=$getUp&type=getUp' role='button'>早起打卡</a>
                        </td>
                        <td>
                            <a class='btn btn-success btn-lg' href='/master/punch.php?state=$sleep&type=sleep'' role='button'>早睡打卡</a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a class='btn btn-info btn-lg' href='/master/punch.php?state=$train&type=train'' role='button'>健身打卡</a>
                        </td>
                        <td>
                            <a class='btn btn-warning btn-lg' href='/master/punch.php?state=$study&type=study'' role='button'>单词打卡</a>
                        </td>
                    </tr>
                </table>
            </div>
          </div>
          <button type='button' class='btn btn-danger'>注销登陆</button>
      </div>

    </div><!-- /.container -->
";



  exit();


  ?>
  <!-- 网页底部 -->
    <?php require_once 'public/layouts/footer.php'; ?>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="public/js/check.js"></script>
  </body>
</html>
