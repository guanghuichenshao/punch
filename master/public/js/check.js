$(document).ready(function() {
	var nameFlag = true;
	var emailFlag = true;
	var pwdFlag = true;

	$('#username').keyup(function() {
		var length = $(this).val().length;
		if ( length >= 2 && length <= 20 ) {
			$.post('admin/Register.php', {username: $(this).val(),type:'name'}, function(data, textStatus, xhr) {
				if (textStatus == 'success') {
					if (data == '1') {
						$('#dis_un').text('用户名已经被使用');
						nameFlag = false;
					}else{
						$('#dis_un').text('');
						nameFlag = true;
					}
				}
			});
		}else{
			$('#dis_un').text('');
            nameFlag = false;
		}
	});

	$('#remail').blur(function() {
		if ($(this).val() != '') {
			var reg = /\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/;
			if (reg.test($(this).val())) {
				$.post('admin/Register.php', {email: $(this).val(),type: 'email'}, function(data, textStatus, xhr) {
					if (textStatus == 'success') {
						if (data == 1) {
							$('#dis_em').text('该邮箱已经被注册');
							emailFlag = false;
						}else{
							$('#dis_em').text('');
							emailFlag = true;
						}
					}
				});
			}else{
				$('#dis_em').text('请核对邮箱格式');
				emailFlag = false;
			}
		}else{
			$('#dis_em').text('');
            nameFlag = false;
		}
	});

	$('#password').blur(function(){
		if ($(this).val() == '') {
			$('#dis_pwd').text('密码不能为空');
		}else if($(this).val().length < 6){
			$('#dis_pwd').text('请设置至少6位密码');
		}else{
			$('#dis_pwd').text('');
		}
	});

	$('#confirm').blur(function() {
		var val = $('#password').val();
		if (val != '') {
			if ($(this).val() == '') {
				$('#dis_con_pwd').text('请输入确认密码');
				pwdFlag = false;
			}else if($(this).val() != val){
				$('#dis_con_pwd').text('两次密码不一致');
				pwdFlag = false;
			}else{
				$('#dis_con_pwd').text('');
				pwdFlag = true;
			}
		}else{
			$('#dis_con_pwd').text('');
			pwdFlag = false;
		}
	});

	$('#reg').click(function() {
		if (!(nameFlag && emailFlag && pwdFlag)) {
			alert('请重新检查注册信息!');
			return false;
		}
	});
});
