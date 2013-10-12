<?php
	/*
	 * 日志管理类
	 */
	 require_once 'includes/SqlHelper.class.php';
	 class JournalService{
	 	
		//增加日志
		function add_journal($member_id,$title,$content){
			$sql="insert into xk_member_journal(journal_title,member_id,journal_text) values('$title','$member_id','$content')";
			$sqlHelper=new SqlHelper();
			$res=$sqlHelper->execute_dml($sql);
			return $res;
		}
		
		function get_journal($member_id,$limit){
			$sql="select * from xk_member_journal where member_id='$member_id' order by journal_id DESC limit 0,$limit";
			$sqlHelper = new SqlHelper();
			$res = $sqlHelper->execute_dql2($sql);

			$sqlHelper->close_connect();
			foreach($res as $k=>$v){
				$res[$k]['journal_time']=date("Y-m-d H:i:s",$v['journal_time']);
			}
			return $res;
		}
		//根据journal_id获取状态
		function get_journal_byid($journal_id){
			$sql="select * from xk_member_journal where journal_id='$journal_id'";
			
			$sqlHelper=new SqlHelper();
			$journal=$sqlHelper->execute_dql2($sql);
			$sqlHelper->close_connect();
			foreach($journal as $k=>$v){
				$journal[$k]['journal_time']=date("Y-m-d H:i:s",$v['journal_time']);
			}
			return $journal;
		}
		
		function update_comm_num($journal_id){
			$sql="update xk_member_journal set comm_num=comm_num+1 where journal_id='$journal_id'";
			
			$sqlHelper = new SqlHelper();
			$res = $sqlHelper->execute_dml($sql);

			$sqlHelper->close_connect();
			return $res;
		}
		
		function get_hot_journal($limit){
			$sql="select * from xk_member_journal order by comm_num DESC limit 0,$limit";
			$sqlHelper=new SqlHelper();
			$res=$sqlHelper->execute_dql2($sql);
			$sqlHelper->close_connect();
			
			return $res;
		}
	 }
?>