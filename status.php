<?php
	require_once 'libs/Smarty.class.php';
	require_once 'includes/MemberService.class.php';
	require_once 'includes/Common.class.php';
	require_once 'includes/StatusService.class.php';
	require_once 'includes/StatusComm.class.php';

	session_start();
	$common=new Common();
	$statusComm = new StatusComm();
	$action=empty($_GET['action'])?'':$_GET['action'];
	$statusService=new Status();
	
	//评论
	if($action == 'reply_ok'){
		$status_id=empty($_POST['status_id'])?'':$_POST['status_id'];
		$info=empty($_POST['info'])?'':$_POST['info'];
		$member_id=@$_SESSION['member_id'];
		if($member_id == ''){
			$common->message(array('text'=>'请登录后再评论该状态','link'=>'login.php','jump'=>'1'));
			exit;
		}
		if($info == ''){
			$common->message(array('text'=>'评论内容不能为空','link'=>'index.php','jump'=>'1'));
			exit;
		}
		$time=time();
		
		$res=$statusComm->add_comm($member_id, $status_id, $info, $time);
		//更新状态评论数
		$res2=$statusService->update_comm_num($status_id);
		
		if($res != '1' || $res2 != '1'){
			$common->message(array('text'=>'评论失败','link'=>'user.php','jump'=>'1'));
			exit;
		}
		$common->message(array('text'=>'评论成功！','link'=>'user.php','jump'=>'1'));
		exit;
	}
	
	//获取状态id
	$status_id=empty($_GET['status_id'])?'':$_GET['status_id'];
	
	$statusService = new Status();
	$status=$statusService->get_status_byid($status_id);
	
	//获取评论
	$status_comm=$statusComm->get_comm($status_id);
	
	//获取用户名
	$username=$_SESSION['username'];
	
	$count=$common->get_login();
	
	$smarty=new Smarty();
	$nav=array("","","","","class='c'");
	$smarty->assign('notice_num',$count);
	$smarty->assign('username',$username);
	$smarty->assign('status',$status['0']);
	$smarty->assign('status_comm',$status_comm);
	$smarty->assign('nav',$nav);
	$smarty->display('status.html');
?>