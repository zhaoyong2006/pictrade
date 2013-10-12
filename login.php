<?php
	require_once 'libs/Smarty.class.php';
	require_once 'includes/Common.class.php';
	
	$smarty=new Smarty();
	//$smarty->template_dir = array('.' . DS . 'admin_tem' . DS);
	// echo "<pre>";
	// print_r($smarty->template_dir);
	// echo "</pre>";exit;
	$common=new Common();
	$count=$common->get_login();

	$smarty->assign('username','');
	$smarty->display('login.html');
?>