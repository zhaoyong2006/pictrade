<?php
	/*
	 * 这是一个站点新闻的类
	 */
	 require_once 'SqlHelper.class.php';
	 class NewsService{
	 	
		//获取最新站点公告
		function get_news(){
			$sql="select * from xk_news order by content_id DESC limit 0,12";
			$sqlHelper=new SqlHelper();
			$res=$sqlHelper->execute_dql2($sql);
			$sqlHelper->close_connect();
			
			return $res;
		}
		
		//获取全部新闻
		function get_all_news(){
			$sql="select * from xk_news order by content_id DESC";
			$sqlHelper=new SqlHelper();
			$res=$sqlHelper->execute_dql2($sql);
			$sqlHelper->close_connect();
			foreach($res as $k=>$v){
				$res[$k]['content_time']=date("Y-m-d H:i",$v['content_time']);
			}
			return $res;
		}
		
		//添加新闻
		function add_news($content_title,$content_text,$content_time){
			$sql="insert into xk_news(content_title,content_text,content_time) values('$content_title','$content_text','$content_time')";
			
			$sqlHelper=new SqlHelper();
			$res=$sqlHelper->execute_dml($sql);
			$sqlHelper->close_connect();
			
			return $res;
		}
		
		//删除新闻
		function del_news($content_id){
			$sql="delete from xk_news where content_id='$content_id'";
			
			$sqlHelper=new SqlHelper();
			$res=$sqlHelper->execute_dml($sql);
			$sqlHelper->close_connect();
			
			return $res;
		}
		
		//获取单个新闻
		function get_news_byid($news_id){
			$sql="select * from xk_news where content_id='$news_id'";
			
			$sqlHelper=new SqlHelper();
			$res=$sqlHelper->execute_dql2($sql);
			$sqlHelper->close_connect();
			foreach($res as $k=>$v){
				$res[$k]['content_time']=date("Y-m-d H:i",$v['content_time']);
			}
			
			return $res;
		}
	 }
?>