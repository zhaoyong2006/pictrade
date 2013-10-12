<?php
	require_once 'libs/Smarty.class.php';

	$smarty=new Smarty();
	//$smarty->template_dir = array('.' . DS . 'admin_tem' . DS);
	// echo "<pre>";
	// print_r($smarty->template_dir);
	// echo "</pre>";exit;	$smarty->assign('username','');
	$nav=array("","","class='c'","","");
	$smarty->assign('nav',$nav);
	$smarty->display('reg.html');
?>