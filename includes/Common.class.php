<?php
	/*
	 * 这是一个公共类
	 * 存放一些公用的方法
	 */
	 require_once 'libs/Smarty.class.php';
	 class Common{
	 	
		//提示信息方法
		function message($message=array()){
			$smarty=new smarty();

			$smarty->assign('message',$message);
			$smarty->display('message.html');
			exit;
		}
		
		//获取用户消息
		function get_login(){
			$username=empty($_SESSION['username'])?'':$_SESSION['username'];
	
			//如果用户已经登录
			$count=0;
			if($username != ''){
				require_once 'includes/FriendService.class.php';
				$member_id=$_SESSION['member_id'];
				$friendService=new FriendService();
				$message=$friendService->fri_notice($member_id);
				$count=count($message);
						
			}
			return $count;
		}
		
		
	}
?>