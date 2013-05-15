<?php
/**
 * @param $host $usname,$passwd,$dbname,$charset,$table,$data,$sql,$id
 * @author Asher
 */
class mysql{
	private $table;
	/**
	 * 初始化数据库
	 *
	 * @param string $host
	 * @param string $usname
	 * @param string $passwd
	 * @param string $dbname
	 * @param string $table
	 * @param string $charset
	 */
	public function __construct($host='',$usname='',$passwd='',$dbname='',$table='',$charset='utf8'){
		$this->table = $table;
		$conn   = mysql_connect($host,$usname,$passwd);
		mysql_select_db($dbname,$conn);
		$this->query(" SET NAMES $charset ");
		if(!$conn){
			$this->errors();
		}
	}
	/**
	 * 入库
	 *
	 * @param array $data
	 * @return int 插入后新增的id
	 */
	public function insert($data){
		$sql    = " INSERT INTO `$this->table` SET ";
		$sqlArr    = array();
		foreach($data as $key => $val){
			$sqlArr[]   = " `$key` = '$val' ";
		}
		$sql    .= implode(' , ',$sqlArr);
		$query  = $this->query($sql);
		if($query){
			return mysql_insert_id();
		}

	}
	/**
	 * 删除
	 *
	 * @param int $id
	 * @return boolean
	 */
	public function delTable($id){
		$sql    = " DELETE FROM `$this->table` WHERE `id` in ($id ) ";
		return  $this->query($sql);
	}
	/**
	 * 更新
	 *
	 * @param unknown_type $data
	 * @param unknown_type $id
	 * @return unknown
	 */
	public function updateTable($data,$id){
		$sql    = " UPDATE `$this->table` SET ";
		$sqlArr = array();
		foreach($data as $key => $val){
			$sqlArr[] = " `$key` = '$val' ";
		}
		$sql    .= implode(' , ',$sqlArr);
		$sql    .= " WHERE `id`=$id ";
		return $this->query($sql);
	}
	/**
	 * query
	 *
	 * @param unknown_type $sql
	 * @return unknown
	 */
	public function query($sql){
		$query  = mysql_query($sql);
		if(!$query){
			return $this->errors();
		}else{
			return $query;
		}
	}
	/**
	 * 报错
	 *
	 */
	private function errors(){
		echo mysql_error().'<hr />';
		exit();
	}
	/**
	 * 查询一条sql
	 *
	 * @param unknown_type $sql
	 * @return unknown
	 */
	public function findOne($sql){
		$query  = $this->query($sql);
		return mysql_fetch_assoc($query);
	}
	public function findData($sql){
        $data="";
		try
        {
            $query = $this->query($sql);
		    while($row = mysql_fetch_assoc($query))
            {
			$data[]=$row;
		    }
        }
        catch(Exception $e){
            $data=array();
        }
		return $data;
	}
	/**
	 * 查询
	 *
	 * @param array $tmpArr
	 * @return array
	 */
	public function find($tmpArr=array()){
		foreach ($tmpArr as $kname=>$value){
			$tmpsql.=$kname.'='."'$value'";
		}
		$sql = "SELECT * FROM $this->table WHERE $tmpsql";
		$query  = $this->query($sql);
		while($row = mysql_fetch_assoc($query)){
			$data[]=$row;
		}
		return $data;
	}
	/**
	 * 查询所有内容
	 *
	 * @return array
	 */
	public function findAll(){
		$sql = "SELECT * FROM $this->table";
		$query  = $this->query($sql);
		$data   = array();
		while($row = mysql_fetch_assoc($query)){
			$data[] = $row;
		}
		return $data;
	}
	/**
	 * 统计
	 *
	 * @param string $column
	 * @param mix $val
	 * @return int
	 */
	public function count($column,$val){
		$sql = "SELECT COUNT( `$column` ) FROM $this->table WHERE `$column` like '%$val%' ";
		//echo $sql;
		$query  = $this->query($sql);
		$data = mysql_fetch_assoc($query);
		//print_r($data);
		return $data["COUNT( `$column` )"];
	}
	/**
	 * 读取字段名
	 *
	 * @return array
	 */
	public function readColumn(){
		$result=$this->query("show columns from $this->table");
		$data = array();
		while($row = mysql_fetch_row($result)){
			$data[]=$row[0];
		}
		return $data;
	}
	/*
		特有函数，负责减少num数目
	*/
	public function reduce($id)
	{
		$result = $this->query("update $this->table set num=num-1 where id=$id");
		return $result;
	}
}
