<?php
namespace Zdc\Db;
use \PDO;
class PDOHelper{
    public static $db =null;// new PDO('mysql:host=192.168.1.68;dbname=test','root','123456'); 
	public static $lastSql;
	public static $dbConfig=null;//array('DB_HOST'=>'192.168.1.68','DB_USER'=>'root','DB_PWD'=>'123456','DB_NAME'=>'test','DB_CHARSET'=>'utf8');
	
	public static function  _connect(){
		if(is_null(self::$db)){
				self::$dbConfig=C('DB_CONFIG');
				self::$db= new PDO('mysql:host='.self::$dbConfig['DB_HOST'].';dbname='.self::$dbConfig['DB_NAME'],self::$dbConfig['DB_USER'],self::$dbConfig['DB_PWD']);
				self::$db->query("SET NAMES '".self::$dbConfig['DB_CHARSET']."';");
		}
	}

	public static function query($sql,$returnAffectCount=false){
		self::_connect();
		self::$lastSql=$sql;
		$st = self::$db->query($sql);

		if(false===$st){
				$rs=false;
		}else if($returnAffectCount){
				$rs=$st->rowCount();
		}else{
				$st->setFetchMode(PDO::FETCH_ASSOC);
				$rs=$st->fetchAll();
		}
		return $rs;
	}

	public static  function exec($sql){
		self::_connect();
		self::$lastSql=$sql;
		$count =self::$db->exec($sql);
		return $count;
	}
}