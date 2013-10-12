<?php
	/*
	 * 这是一个好友表操作类
	 * 
	 */
	 require_once 'includes/Sqlhelper.class.php';
	 
	 class FriendService{
	 	
		//添加好友
		function add_fri($from_id,$fri_id,$con_fri){
			$sqlHelper=new SqlHelper();
			$sql="select member_username from xk_member where member_id='$fri_id'";
			$res = $sqlHelper->execute_dql2($sql);		
			$name=$res['0']['member_username'];

			$time=time();
			$sql2="insert into xk_friend(uid,fuid,fusername,note,dateline) values('$from_id','$fri_id','$name','$con_fri','$time')";
			$res2=$sqlHelper->execute_dml($sql2);
			$sqlHelper->close_connect();	
			return $res2;
			
		}
		
		//获取好友
		function get_friend($member_id){
			$sql="select * from xk_friend where uid='$member_id' and status='1'";
			$sqlHelper=new SqlHelper();
			$res=$sqlHelper->execute_dql2($sql);

			$sqlHelper->close_connect();
			return $res;	
		}
		//获取未读好友请求
		function fri_notice($member_id){
			$sql="select * from xk_friend where fuid='$member_id' and status='0'";
			$sqlHelper=new SqlHelper();
			$res=$sqlHelper->execute_dql2($sql);
			$sqlHelper->close_connect();
			return $res;
		}
		
		//同意加好友
		function agree_notice($from_id,$fri_id,$name,$con_fri,$id,$time){
			$sql="update xk_friend set status='1' where id='$id'";
			$sqlHelper=new SqlHelper();
			$res = $sqlHelper->execute_dml($sql);
			
			$sql2="insert into xk_friend(uid,fuid,fusername,status,note,dateline) values('$from_id','$fri_id','$name','1','$con_fri','$time')";
			$res2=$sqlHelper->execute_dml($sql2);
			$sqlHelper->close_connect();	
			return $res2;
			
		}
	 }

?>