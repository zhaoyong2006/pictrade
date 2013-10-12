<?php
	/*
	 * 日志评论类
	 * 
	 */
	 require_once 'includes/SqlHelper.class.php';
	 class JournalComm{
	 	
		function add_comm($from_id,$journal_id,$note,$dateline){
			$sql="insert into xk_member_journal_comment(from_id,journal_id,note,dateline) values('$from_id','$journal_id','$note','$dateline')";
			
			$sqlHelper = new SqlHelper();
			$res = $sqlHelper->execute_dml($sql);
			$sqlHelper->close_connect();
			
			return $res;
		}
		
		function get_comm($journal_id){
			$sql="select from_id,journal_id,note,dateline,member_username from xk_member_journal_comment,xk_member where journal_id='$journal_id' and from_id=member_id order by comment_id DESC";
			
			$sqlHelper=new SqlHelper();
			$journal_comm=$sqlHelper->execute_dql2($sql);
			$sqlHelper->close_connect();
			foreach($journal_comm as $k=>$v){
				$journal_comm[$k]['dateline']=date("Y-m-d H:i:s",$v['dateline']);
			}
			return $journal_comm;
		}
		
		
	}

?>