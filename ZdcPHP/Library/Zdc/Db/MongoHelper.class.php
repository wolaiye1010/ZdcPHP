<?php
namespace Zdc/Db;
class MongoHelper{
	public static $dbConfig=array('host'=>'localhost','username'=>'root','password'=>'123456','db'=>'admin','selectDb'=>'');
	public static $dsn=null;
	public static $mongoClient=null;
	public static $db=null;

	public static function _connect(){
		if(is_null(self::$mongoClient)){
			$dbConfig=self::$dbConfig;
			self::$dsn=is_null(self::$dsn)?"mongodb://{$dbConfig['username']}:{$dbConfig['password']}@{$dbConfig['host']}/{$dbConfig['db']}":self::$dsn;
			//mongodb://[username:password@]host1[:port1][,host2[:port2:],...]/db
			self::$mongoClient = new MongoClient(self::$dsn);
			$selectDb=self::$dbConfig['selectDb']?self::$dbConfig['selectDb']:self::$dbConfig['db'];
			self::$db=self::$mongoClient->selectDb($selectDb);
		}
	}
	
	public static function find($tableName,array $where =array(), array $fields = array()){
		self::_connect();	
		$cursor=self::$db->$tableName->find($where,$fields);
		$rs=iterator_to_array($cursor);
		return $rs;
	}
}
