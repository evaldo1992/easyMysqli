<?php
class EasyInsert{
	
	private $fields;
	private $types;
	private $values;
	private $table;
	private $sql;
	public $mysql;
	private $error;
	private $insert_id;
	private $prepare_statement;
	
	public function getError(){
		return $this->error;
	}
	public function getId(){
		return $this->insert_id;
	}
	function __construct(&$mysql,$table){
		$this->mysql=$mysql;
		$this->table=$table;
		$this->fields=array();
		$this->values=array();
		$this->types=array();
	}
	function addField($field,$value,$type){
		$this->fields[]=$field;
		$this->values[]=$value;
		$this->types[]=$type;
	}
	public function insert(){
		if($this->prepare()){
			if($this->bindParam($this->prepare_statement,$this->types,$this->values)){
				return $this->execute();
			}else{
				$this->error=$this->prepare_statement->error;
				$this->prepare_statement->close();
				return false;
			}
		}else{
			$this->error=$this->mysql->error;
			$this->error->close();
			return false;
		}
	}
	private function prepare(){
		$symbol='?';
		$symbols=array();
		for($i=0;$i<count($this->fields);$i++){
			$symbols[]=$symbol;
		}
		$this->sql="INSERT INTO ".$this->table."  (".implode(',',$this->fields).") VALUES (".implode(',',$symbols).")";
	
		if(!$this->prepare_statement=$this->mysql->prepare($this->sql)){
			return false;
		}
		return true;
	}	
	private function execute(){
		if($this->prepare_statement->execute()){
			$this->insert_id=$this->prepare_statement->insert_id;
			$this->prepare_statement->close();
			return true;
		}
		$this->error=$this->prepare_statement->error;
		return false;
	}
	private function bindParam(&$sentencia,$types=array(),$var1=array()){
		$refs=array();
		$array=&$var1;
		 foreach($array as $key => $value){
            $refs[$key] = &$array[$key];
        }		
		$bind=array_merge(array(implode('',$types)),$refs);
		return call_user_func_array(array(&$sentencia,'bind_param'),$bind);
	}
}
?>