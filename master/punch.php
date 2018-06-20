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
$state = isset($_REQUEST['state']) && !empty($_REQUEST['state']) ? $_REQUEST['state'] : "";
$type = isset($_REQUEST['type']) && !empty($_REQUEST['type']) ? $_REQUEST['type'] : "";
$username=$_SESSION['username'];
$times=$type.'Times';
if ($type=='getUp'){
    $chineseType='早起打卡';
    $banner='新的一天，你也要元气满满哦';
    $rankTable='getuprank';
}elseif ($type=='sleep'){
    $chineseType='早睡打卡';
    $banner='晚安好梦';
    $rankTable='sleeprank';
}elseif ($type=='train'){
    $chineseType='健身打卡';
    $banner='强身健体，活力满满';
    $rankTable='trainrank';
}elseif ($type=='study'){
    $chineseType='单词打卡';
    $banner='四六级必过';
    $rankTable='studyrank';
}

?>
<!DOCTYPE html>

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

$sql = "UPDATE punch set $type = 1 WHERE userid = $id";
$time = time();
if ($mysqli->query($sql) === true) {
    if ($state==0){
        $sql1 = "insert into $rankTable(userid,username,punchTime) values ($id,'$username',$time)";
        if ($mysqli->query($sql1) === true) {
            $Info="打卡成功";
        }else{
            $Info="打卡数据存入失败";
        }
    }elseif ($state==1){
        $Info="今天已经".$chineseType."过啦，请明日再来";
    }
}

$sql2 = "UPDATE punch set $times = $times + 1 WHERE userid = $id";

if ($mysqli->query($sql2) === true) {
    if ($state==0){
            $Info1="累计打卡多少次";
    }
}

    echo "
<div class='container'>
    <div class='content''>
        <div class='starter-template'>
            <!-- 这里做了修改，其他地方自由发挥 -->
            <h1>Welcome To Hrbust Punch System</h1>
            <div class='jumbotron'>
                <h2>".$_SESSION['username']." , $Info</h2>
                <h1>$banner</h1>
                <table align='center'>

                </table>
            </div>
        </div>
        <a class='btn btn-success' href='/master/rank.php?type=$type' role='button'>排行榜</a>

        <button type='button' class='btn btn-danger'>注销登陆</button>
    </div>

</div><!-- /.container -->
";


$mysqli->close();
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
