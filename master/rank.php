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
$type = isset($_REQUEST['type']) && !empty($_REQUEST['type']) ? $_REQUEST['type'] : "";
?>
<!DOCTYPE html>
<script language="javascript">


    var _source,_sourceUrl,_pic,_showcount,_desc,_summary,_site,
        //_title = '';
        _width = 600,
        _height = 600,
        _top = (screen.height-_height)/2,
        _left = (screen.width-_width)/2,
        _url = 'www.baidu.com',
        _pic = 'https://raw.githubusercontent.com/guanghuichenshao/punch/master/master/eg.png';

    //分享到新浪微博
    function shareToSinaWB(event,times){
        event.preventDefault();
        //var _title = '【哈尔滨理工大学】我已经在哈理工打卡系统连续打卡'+times+'天了,快来和我一起吧',
        var _shareUrl = 'http://v.t.sina.com.cn/share/share.php?&appkey=895033136';     //真实的appkey，必选参数
        _shareUrl += '&url='+ encodeURIComponent(_url||document.location);     //参数url设置分享的内容链接|默认当前页location，可选参数
        _shareUrl += '&title=' + encodeURIComponent('【哈尔滨理工大学】我已经在哈理工打卡系统累计打卡'+times+'天了,快来和我一起吧！'||document.title);    //参数title设置分享的标题|默认当前页标题，可选参数
        _shareUrl += '&source=' + encodeURIComponent(_source||'');
        _shareUrl += '&sourceUrl=' + encodeURIComponent(_sourceUrl||'');
        _shareUrl += '&content=' + 'utf-8';   //参数content设置页面编码gb2312|utf-8，可选参数
        _shareUrl += '&pic=' + encodeURIComponent(_pic||'');  //参数pic设置图片链接|默认为空，可选参数
        window.open(_shareUrl,'_blank','width='+_width+',height='+_height+',top='+_top+',left='+_left+',toolbar=no,menubar=no,scrollbars=no, resizable=1,location=no,status=0');
    }

</script>
<style type="text/css">
    table {
        width: 80%;
        height: 30px;
    }
    table td {
        padding: 10px;
    }
    table .red {
        color: red;

    }
    .m-box{width:800px;margin:0 auto;padding:20px;background:#fff;}
    .m-box p{margin:0 0 10px;}
    .m-box .icn a{display:block;width:55px;height:35px;background:url('http://l.bst.126.net/rsc/img/weibo.png?035') no-repeat;}
    .m-box .icn .wb1{background-position:10px -216px;}

</style>
<html lang="zh-CN">
<!-- header部分 -->
<?php require_once 'public/layouts/header.php' ?>

<body>
<!-- 导航栏 -->
<?php require_once 'public/layouts/nav.php' ?>


<?php

if ($type=='getUp'){
    $chineseType='早起打卡';
    $table='getuprank';
}elseif ($type=='sleep'){
    $chineseType='早睡打卡';
    $table='sleeprank';
}elseif ($type=='study'){
    $chineseType='单词打卡';
    $table='studyrank';
}elseif ($type=='train'){
    $chineseType='健身打卡';
    $table='trainrank';
}

//数据库验证
$id = $_SESSION['userid'];
$username = $_SESSION['username'];
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
$typeTimes=$type."Times";
$sql2 = "SELECT $typeTimes FROM punch where userid = $id";
$res2 = $mysqli->query($sql2);
while ($row = $res2->fetch_assoc()) {
    $times = $row[$typeTimes];
}


$sql = "SELECT * FROM $table limit 10 ";
$res = $mysqli->query($sql);

$mysqli->close();

echo "
  <!-- 页面主体内容 -->
    <div class='container'>
      <div class='content'>
          <div class='starter-template'>
                <!-- 这里做了修改，其他地方自由发挥 -->
            <h1>Welcome To Hrbust Punch System</h1>
            
            <div class='jumbotron'>
              
                <table align='center'>
                    <tr>
                        <td>排名
                        </td>
                        <td>昵称
                        </td>
                        <td>打卡类型
                        </td>
                        <td>打卡时间
                        </td>
                    </tr>
                    ";
$count=0;
while ($row = $res->fetch_assoc()) {
    //echo($row['username']);//这里的name是标的列名。
    $count=$count+1;
    $userId=$row['userid'];
    $rankUserName=$row['username'];
    $punchTime=$row['punchTime'];
    $time=date('Y-m-d H:i:s',$punchTime);
    echo "<script>alert(".$time.")</script>";
    if ($userId==$id){
        $rank=$count;
        echo "
    <tr class='red'>";

        }else{
    echo "<tr>";
    }echo "
    
                        <td>$count
                        </td>
                        <td>$rankUserName
                        </td>
                        <td>$chineseType
                        </td>
                        <td>$time
                        </td>
                    </tr>
    
    
    ";

}



echo "
                    <tr>
                        <td>
                        </td>
                        <td>
                        </td>
                    </tr>
                </table>
            </div>
          </div>
          <h3> ".$_SESSION['username'].",你目前排在第".$rank."名,请努力保持，继续加油喔~</h3>
          <a class='btn btn-success' href='/master/index' role='button'>返回到首页</a>
          <a class='btn btn-success' href='/master/punch.php?state=1&type=$type' role='button'>返回上一页</a>
          <a class='btn btn-danger' href='/master/welcome.php?a=loginout' role='button'>注销登陆</a>
          <div class='m-box'>
    <div class='icn'><a class='wb1' onclick='shareToSinaWB(event,$times)'></a></div>

    </div>
    
   </div>
      </div>
      <!-- /.container -->
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
