<?php
	/*
	 * 留言类
	 * 
	 */
	 require_once 'includes/SqlHelper.class.php';
	 class MessageService{
	 	
		function add_message($from_id,$to_id,$info,$dateline){
			$sql="insert into xk_menber_message(from_id,to_id,info,dateline) values('$from_id','$to_id','$info','$dateline')";
			//echo $sql;exit;
			$sqlHelper = new SqlHelper();
			$res=$sqlHelper->execute_dml($sql);
			$sqlHelper->close_connect();
			
			return $res;
		}
		
		//获取留言
		function get_message($member_id){
			$sql="select from_id,to_id,info,dateline,member_username from xk_menber_message,xk_member where to_id='$member_id' and from_id=member_id order by message_id DESC";
			
			$sqlHelper = new SqlHelper();
			$res=$sqlHelper->execute_dql2($sql);
			$sqlHelper->close_connect();
			foreach($res as $k=>$v){
				$res[$k]['dateline']=date("Y-m-d H:i:s",$v['dateline']);
			}
			return $res;
		}
	 }
?>