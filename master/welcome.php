<?php
$a = isset($_REQUEST['a']) && !empty($_REQUEST['a']) ? $_REQUEST['a'] : "";

  session_start();
if($a=='loginout'){
//清除sesssion
    unset($_SESSION['username']);
    unset($_SESSION['userid']);
    session_destroy();
    setcookie("username", "", time() - 3600);
    setcookie("userid", "", time() - 3600);
}
  if (isset($_SESSION['username'])) {
    header('location:index.php');
  }
//echo $_SESSION['username'];
//echo "/br";
//echo $_COOKIE['username'];
$url = 'http://api.yytianqi.com/observe?city=CH050101&key=保密';
$result = file_get_contents($url);
//echo $result;
$jsonArray = json_decode($result);
//json_decode($result,true);
//$arr = $jsonArray->msg;
$cityName = $jsonArray->data->cityName;
$lastUpdate = $jsonArray->data->lastUpdate;
$tq = $jsonArray->data->tq;
$qw = $jsonArray->data->qw;
$fl = $jsonArray->data->fl;
$fx = $jsonArray->data->fx;
$sd = $jsonArray->data->sd;
//$jsonArray2 = json_decode($jsonArray->data);
//$arr2 = $jsonArray2->cityId;



?>
<style type="text/css">

    table td {
        align="left";
    }
    table {
        margin-left: 40%;
    }
</style>
<!DOCTYPE html>
<html lang="zh-CN">
  <!-- header部分 -->
  <?php require_once 'public/layouts/header.php' ?>


  <body>
  <!-- 导航栏 -->
  <?php require_once 'public/layouts/nav.php' ?>
  <!-- 页面主体内容 -->
    <div class="container">
      <div class="content">
          <div class="starter-template">
              <h1>Welcome To Welcome To Hrbust Punch System</h1>

              <div class='jumbotron'>
                  <!-- 这里做了修改，其他地方自由发挥 -->
                  <p class="lead">你可以在这里进行每日的早起早睡，健身学习等打卡，还可以在排行榜与好友pk</p>


              </div>
              <h4>实时天气</h4>
              <table width="40%">
                  <tr>
                      <td>
                          城市
                      </td>
                      <td>
                          <?php echo $cityName ?>
                      </td>
                  </tr>
                  <tr>
                      <td>
                          现象
                      </td>
                      <td>
                          <?php echo $tq ?>
                      </td>
                  </tr>
                  <tr>
                      <td>
                          气温
                      </td>
                      <td>
                          <?php echo $qw ?>℃
                      </td>
                  </tr>
                  <tr>
                      <td>
                          风力
                      </td>
                      <td>
                          <?php echo $fl ?>
                      </td>
                  </tr>
                  <tr>
                      <td>
                          风向
                      </td>
                      <td>
                          <?php echo $fx ?>
                      </td>
                  </tr>
                  <tr>
                      <td>
                          相对湿度
                      </td>
                      <td>
                          <?php echo $sd ?>%
                      </td>
                  </tr>
                  <tr>
                      <td>
                          天气更新时间
                      </td>
                      <td>
                          <?php echo $lastUpdate ?>
                      </td>
                  </tr>
              </table>



          </div>
          <!-- 注册表单 -->
          <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" id="register" aria-labelledby="myLargeModalLabel">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Register</h4>
              </div>
              <form action="admin/Register.php" method="post" accept-charset="utf-8" class="form-horizontal">
                <div class="modal-body">

                  <div class="form-group">
                    <label for="username" class="col-sm-4 control-label">Name:</label>
                    <div class="col-sm-6">
                      <input type="text" class="form-control" name="username" id="username" minlength="2" maxlength="20" placeholder="username" required="">
                    </div>
                    <!-- 错误提示信息 -->
                    <h6 style="color: red;" id="dis_un"></h6>
                  </div>

                  <div class="form-group">
                    <label for="email" class="col-sm-4 control-label">Email:</label>
                    <div class="col-sm-6">
                      <input type="email" class="form-control" name="email" id="remail" placeholder="Email" required="">
                    </div>
                    <h6 style="color: red;" id="dis_em"></h6>
                  </div>

                  <div class="form-group">
                    <label for="password" class="col-sm-4 control-label">Password:</label>
                    <div class="col-sm-6">
                      <input type="password" class="form-control" name="password" id="password" placeholder="password" minlength="6" maxlength="20" required="">
                    </div>
                    <h6 style="color: red;" id="dis_pwd"></h6>
                  </div>

                  <div class="form-group">
                    <label for="confirm" class="col-sm-4 control-label">Confirm password:</label>
                    <div class="col-sm-6">
                      <input type="password" class="form-control" name="confirm" id="confirm" placeholder="confirm" minlength="6" maxlength="20" required="">
                    </div>
                    <h6 style="color: red;" id="dis_con_pwd"></h6>
                  </div>
                  
                  <div class="form-group">
                    <label for="code" class="col-sm-4 control-label"> verification code :</label>
                    <div class="col-sm-6">
                      <input type="text" class="form-control" name="code" id="code" placeholder="verification code" required="" maxlength="4" size="100">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-12">
                      <img src="admin/Captcha.php" alt="" id="codeimg" onclick="javascript:this.src = 'admin/Captcha.php?'+Math.random();">
                <span>Click to Switch</span>
                    </div>
                  </div>
                  <input type="hidden" name="type" value="all">
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal" style="float: left;">Close</button>
                  <input type="reset" class="btn btn-warning" value ="reset" />
                  <button type="submit" class="btn btn-primary" id="reg">register</button>
                </div>
              </form>
              </div>
            </div>
          </div>


          <!-- 登陆表单 -->
          <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" id="login" aria-labelledby="myLargeModalLabel">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Login</h4>
              </div>
              <form action="admin/Login.php" method="post" accept-charset="utf-8" class="form-horizontal">
                <div class="modal-body">

                  <div class="form-group">
                    <label for="email" class="col-sm-4 control-label">Email:</label>
                    <div class="col-sm-6">
                      <input type="email" class="form-control" name="email" id="email" placeholder="Email" required="">
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="password" class="col-sm-4 control-label">Password:</label>
                    <div class="col-sm-6">
                      <input type="password" class="form-control" name="password" placeholder="password" minlength="6" maxlength="20" required="">
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="password" class="col-sm-4 control-label">Remember Me:</label>
                    <div class="col-sm-3">
                      <label class="checkbox-inline">
                        <input type="radio" name="rem" id="yes" value="1" checked> Yes
                      </label>
                      <label class="checkbox-inline">
                        <input type="radio" name="rem" id="optionsRadios4" value="0"> No
                      </label>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="code" class="col-sm-4 control-label"> verification code :</label>
                    <div class="col-sm-6">
                      <input type="text" class="form-control" name="code" id="code" placeholder="verification code" required="" maxlength="4">
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="col-sm-12">
                      <img src="admin/Captcha.php" alt="" id="codeimg" onclick="javascript:this.src = 'admin/Captcha.php?'+Math.random();">
                <span>Click to Switch</span>
                    </div>
                  </div>
                  
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal" style="float: left;">Close</button>
                  <input type="reset" class="btn btn-warning" value ="reset" />
                  <button type="submit" class="btn btn-primary" name="login">Login</button>
                </div>
              </form>
              </div>
            </div>
          </div>

      </div>

    </div><!-- /.container -->
    
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