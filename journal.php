<?php
	require_once 'libs/Smarty.class.php';
	require_once 'includes/MemberService.class.php';
	require_once 'includes/Common.class.php';
	require_once 'includes/JournalService.class.php';
	require_once 'includes/JournalComm.class.php';

	session_start();
	$common=new Common();
	$journalComm = new JournalComm();
	$action=empty($_GET['action'])?'':$_GET['action'];
	$journalService = new JournalService();
	//评论
	if($action == 'reply_ok'){
		$journal_id=empty($_POST['journal_id'])?'':$_POST['journal_id'];
		$info=empty($_POST['info'])?'':$_POST['info'];
		$member_id=@$_SESSION['member_id'];
		$time=time();
		if($member_id == ''){
			$common->message(array('text'=>'请登录后在做评论','link'=>'login.php','jump'=>'1'));
			exit;
		}
		if($info == ''){
			$common->message(array('text'=>'评论内容不能为空','link'=>'index.php','jump'=>'1'));
			exit;
		}
		$res=$journalComm->add_comm($member_id, $journal_id, $info, $time);
		//更新日志评论数
		$res2=$journalService->update_comm_num($journal_id);
		
		if($res != '1' || $res2 != '1' ){
			$common->message(array('text'=>'评论失败','link'=>'user.php','jump'=>'1'));
			exit;
		}
		$common->message(array('text'=>'评论成功！','link'=>'user.php','jump'=>'1'));
		exit;
	}
	
	//获取状态id
	$journal_id=empty($_GET['journal_id'])?'':$_GET['journal_id'];
	
	$journalService = new journalService();
	$journal=$journalService->get_journal_byid($journal_id);
	
	//获取评论
	$journal_comm=$journalComm->get_comm($journal_id);
	
	//获取用户名
	$username=$_SESSION['username'];
	
	$count=$common->get_login();
	
	$smarty=new Smarty();
	$nav=array("","","","","class='c'");
	$smarty->assign('notice_num',$count);
	$smarty->assign('username',$username);
	$smarty->assign('journal',$journal['0']);
	$smarty->assign('journal_comm',$journal_comm);
	$smarty->assign('nav',$nav);
	$smarty->display('journal.html');
?>