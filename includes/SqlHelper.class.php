<?php
	//这个一个工具类,作用是完成对数据库的操作
	class SqlHelper {

		public $conn;
		public $dbname="mypicdb";
		public $username="root";
		public $password="root";
		public $host="localhost";

		public function __construct(){

			$this->conn=mysql_connect($this->host,$this->username,$this->password);
			if(!$this->conn){
				die("连接失败".mysql_error());
			}
			mysql_query("set names utf8");
			mysql_select_db($this->dbname,$this->conn);
		}



		//执行dql语句
		public function execute_dql($sql){

			$res=mysql_query($sql,$this->conn) or die(mysql_error());
			return $res;

		}

		//执行dql语句，但是返回的是一个数组
		public function execute_dql2($sql){

			$arr=array();
			$res=mysql_query($sql,$this->conn) or die(mysql_error());

			//把$res=>$arr 把结果集内容转移到一个数组中.
			while($row=mysql_fetch_assoc($res)){
				$arr[]=$row;
			}
			//这里就可以马上把$res关闭.
			mysql_free_result($res);
			return $arr;

		}

		//考虑分页情况的查询,这是一个比较通用的并体现oop编程思想的代码
		//$sql1="select * from where 表名 limit 0,6";
		//$sql2="select count(id) from 表名"
		public function exectue_dql_fenye($sql1,$sql2,$fenyePage){

			//这里我们查询了要分页显示的数据
			$res=mysql_query($sql1,$this->conn) or die(mysql_error());
			//$res=>array()
			$arr=array();
			//把$res转移到$arr
			while($row=mysql_fetch_assoc($res)){
				$arr[]=$row;
			}

			mysql_free_result($res);

			$res2=mysql_query($sql2,$this->conn) or die(mysql_error());

			if($row=mysql_fetch_row($res2)){
				$fenyePage->pageCount=ceil($row[0]/$fenyePage->pageSize);
				$fenyePage->rowCount=$row[0];
			}

			mysql_free_result($res2);

			//把导航信息也封装到fenyePage对象中
			$navigate="";
			if ($fenyePage->pageNow>1){
				$prePage=$fenyePage->pageNow-1;
				$navigate="<a href='empList.php?pageNow=$prePage'>上一页</a>&nbsp;";
			}
			if($fenyePage->pageNow<$fenyePage->pageCount){
				$nextPage=$fenyePage->pageNow+1;
				$navigate.="<a href='empList.php?pageNow=$nextPage'>下一页</a>&nbsp;";
			}

			//把$arr赋给$fenyePage
			$fenyePage->res_array=$arr;
			$fenyePage->navigate=$navigate;


		}

		//执行dml语句
		public  function execute_dml($sql){

			$b=mysql_query($sql,$this->conn);
			if(!$b){
				return 0;
			}else{
				if(mysql_affected_rows($this->conn)>0){
					return 1;//表示执行ok
				}else{
					return 2;//表示没有行受到影响
				}
			}

		}

		//关闭连接的方法
		public function close_connect(){

			if(!empty($this->conn)){
				mysql_close($this->conn);
			}
		}
	}
?>