<?php
	require_once 'libs/Smarty.class.php';
	require_once 'includes/MemberService.class.php';
	require_once 'includes/Common.class.php';

	session_start();
	$common=new Common();
	$action=empty($_GET['action'])?'':$_GET['action'];
	if($action == 'recharge_ok'){
		$account=empty($_POST['account'])?'':$_POST['account'];
		$member_id=$_SESSION['member_id'];
		
		if(!is_numeric($account)){
			$common->message(array('text'=>'充值金币数应为数字','link'=>'recharge.php','jump'=>'1'));
			exit;
		}
		
		$memberService=new MemberService();
		$account2="account+$account";
		$res=$memberService->add_account($member_id, $account2);
		if($res != '1'){
			$common->message(array('text'=>'充值失败，请重试！','link'=>'recharge.php','jump'=>'1'));
			exit;
		}
		$common->message(array('text'=>'充值成功，成功充值'.$account.'金币','link'=>'user.php','jump'=>'1'));
		exit;
	}
	$username=$_SESSION['username'];
	$smarty=new Smarty();
	$count=$common->get_login();

	$smarty->assign('notice_num',$count);
	$smarty->assign('username',$username);
	$smarty->display('recharge.html');
?>