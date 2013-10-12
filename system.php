<?php
		//后台
	require_once 'libs/Smarty.class.php';
	require_once 'includes/Common.class.php';
	require_once 'includes/AdminService.class.php';
	
	$smarty=new smarty();
	$smarty->template_dir = array('.' . DS . 'templates/admin' . DS);
	$adminService=new AdminService();
	session_start();
	$common=new Common();
	$do=empty($_GET['do'])?'':$_GET['do'];
	if($do == 'login'){
		$admin_name=empty($_POST['admin_name'])?'':$_POST['admin_name'];
		$admin_password=empty($_POST['admin_password'])?'':$_POST['admin_password'];
		$authcode=empty($_POST['authcode'])?'':trim($_POST['authcode']);
		if($admin_name == '' || $admin_password == ''){
			$common->message(array('text'=>'用户名或密码不能为空!','link'=>'admin.php','jump'=>'1'));
			exit;
		}
		if($authcode != $_SESSION['authcode']){
			$common->message(array('text'=>'验证码错误!','link'=>'admin.php','jump'=>'1'));
			exit;
		}
		$res=$adminService->check_admin($admin_name, $admin_password);
		if($res == '0'){
			$common->message(array('text'=>'登录失败!','link'=>'admin.php','jump'=>'1'));
			exit;
		}else{
			$_SESSION['admin_name']=$admin_name;
			$common->message(array('text'=>'登录成功!','link'=>'admin.php','jump'=>'1'));
			exit;
		}
	}
?>