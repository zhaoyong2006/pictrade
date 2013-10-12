<?php
	/*
	 * 这是一个用户状态操作类
	 * 
	 */
	 require_once 'includes/SqlHelper.class.php';
	class Status{
		
		function add_status($status,$member_id,$time){
			$sql="insert into xk_member_status(member_id,status_text,status_time) values('$member_id','$status','$time')";

			$sqlHelper = new SqlHelper();
			$res = $sqlHelper->execute_dml($sql);
			$sqlHelper->close_connect();
			return $res;
		}
		
		//获取最新状态列表
		function get_status($member_id,$limit){
			$sql="select * from xk_member_status where member_id='$member_id' order by status_id DESC limit 0,$limit";

			$sqlHelper = new SqlHelper();
			$res = $sqlHelper->execute_dql2($sql);

			$sqlHelper->close_connect();
			foreach($res as $k=>$v){
				$res[$k]['status_time']=date("Y-m-d H:i:s",$v['status_time']);
			}
			//待定，添加评论次数搜索
			return $res;
		}
		
		//获取好友动态
		function get_friend_status(){
			$member_id=@$_SESSION['member_id'];
			
			$sql="select member_id,fusername,status_id,status_text,status_time,comm_num from xk_member_status,xk_friend where uid='$member_id' and fuid = member_id order by status_id DESC limit 0,5";
			$sqlHelper = new SqlHelper();
			$res = $sqlHelper->execute_dql2($sql);

			$sqlHelper->close_connect();
			foreach($res as $k=>$v){
				$res[$k]['status_time']=date("Y-m-d H:i:s",$v['status_time']);
			}
			
			return $res;
		}
		
		//根据status_id获取状态
		function get_status_byid($status_id){
			$sql="select * from xk_member_status where status_id='$status_id'";
			
			$sqlHelper=new SqlHelper();
			$status=$sqlHelper->execute_dql2($sql);
			$sqlHelper->close_connect();
			foreach($status as $k=>$v){
				$status[$k]['status_time']=date("Y-m-d H:i:s",$v['status_time']);
			}
			return $status;
		}

		function update_comm_num($status_id){
			$sql="update xk_member_status set comm_num=comm_num+1 where status_id='$status_id'";
			
			$sqlHelper = new SqlHelper();
			$res = $sqlHelper->execute_dml($sql);

			$sqlHelper->close_connect();
			return $res;
		}
	}
?>