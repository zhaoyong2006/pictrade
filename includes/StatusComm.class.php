<?php
	/*
	 * 状态评论类
	 * 
	 */
	 require_once 'includes/SqlHelper.class.php';
	 class StatusComm{
	 	
		function add_comm($from_id,$status_id,$note,$dateline){
			$sql="insert into xk_member_status_comment(from_id,status_id,note,dateline) values('$from_id','$status_id','$note','$dateline')";
			
			$sqlHelper = new SqlHelper();
			$res = $sqlHelper->execute_dml($sql);
			$sqlHelper->close_connect();
			
			return $res;
		}
		
		function get_comm($status_id){
			$sql="select from_id,status_id,note,dateline,member_username from xk_member_status_comment,xk_member where status_id='$status_id' and from_id=member_id order by comment_id DESC";
			
			$sqlHelper=new SqlHelper();
			$status_comm=$sqlHelper->execute_dql2($sql);
			$sqlHelper->close_connect();
			foreach($status_comm as $k=>$v){
				$status_comm[$k]['dateline']=date("Y-m-d H:i:s",$v['dateline']);
			}
			return $status_comm;
		}
	 }
?>