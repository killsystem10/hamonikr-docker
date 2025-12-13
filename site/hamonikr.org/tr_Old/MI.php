<?
Class MI extends mysqli
{
	# Basic
	private $HOST,$DATA,$USER,$PASS,$DBCON;
	public $TRAN=true;
	function MI() {}
	function ERROR($msg) {printf("MySQL Error : %s\n",$msg);exit;}
	function Conn($data='',$user='',$pass='',$host='',$char='') {$this->DATA=($data!=false)?$data:'hamonitr';$this->USER=($user!=false)?$user:'hamonitr';$this->PASS=($pass!=false)?$pass:'gkahslzk!$(';$this->HOST=($host!=false)?$host:'www.hamonikr.org';$this->DBCON=new mysqli($this->HOST,$this->USER,$this->PASS,$this->DATA);if(!$this->DBCON){$this->ERROR(mysqli_connect_error());}$char=($char!=false)?$char:'utf8';mysqli_query($this->DBCON,'set names '.$char);return $this->DBCON;}
	function Query($sql,$show=false) {if($show==true){echo '<br><br>'.$sql.'<br><br>';exit;}$result=mysqli_query($this->DBCON,$sql) or die ($this->ERROR('Query Error =><br /><br />'.$sql.'<br /><br />'.mysqli_error($this->DBCON)));if($result){$this->TRAN=true;return $result;}else{$this->TRAN=false;return false;}}
	function Close() {mysqli_close($this->DBCON);}
	function Begin() {$this->Query('set autocommit = 0;');$this->Query('begin;');}
	function Commit() {if($this->TRAN){$this->Query('commit');}else{$this->Query('rollback');$this->TRAN=false;}return $this->TRAN;}
	function Lock($table,$opt) {return $this->Query('lock tables '.$table.' '.$opt);}
	function Unlock($table) {return $this->Query('unlock tables '.$table);}
	function InsertID() {return mysqli_insert_id($this->DBCON);}
	# Extends
	function NRow($sql) {return mysqli_num_rows($this->Query($sql));}
	function FRow($sql) {return mysqli_fetch_row($this->Query($sql));}
	function FAss($sql) {return mysqli_fetch_assoc($this->Query($sql));}
	function FArr($sql) {return mysqli_fetch_array($this->Query($sql));}
	function KRow($sql,$show=false) {if($show){echo '<br><br>'.$sql.'<br><br>';exit;}$res=Array();if($result=$this->Query($sql)) while($row=$result->fetch_row()) $res[]=$row;$res['Count']=count($res);return $res;}
	function KAss($sql,$show=false) {if($show){echo '<br><br>'.$sql.'<br><br>';exit;}$res=Array();if($result=$this->Query($sql)) while($row=$result->fetch_assoc()) $res[]=$row;$res['Count']=count($res);return $res;}
	function KArr($sql,$show=false) {if($show){echo '<br><br>'.$sql.'<br><br>';exit;}$res=Array();if($result=$this->Query($sql)) while($row=$result->fetch_array()) $res[]=$row;$res['Count']=count($res);return $res;}


	Function Total($Table,$Colm,$Whe="",$Ord="",$Limit="",$Group="",$View=""){
		$C=(!$Colm)?"*":$Colm;
		if($Whe!=""){
			$W=eregi_replace(",,","##",$Whe);
			$W=eregi_replace(","," and ",$W);
			$W=eregi_replace("##",",",$W);
			$W=" where ".$W;
		}
		$O=(!$Ord)?"":" order by ".eregi_replace("="," ",$Ord);
		$L=(!$Limit)?"":" limit ".$Limit;
		$G=(!$Group)?"":" group by ".$Group;
		$this->SQL="select $C from $Table $W $G $O $L";
		if($View!=false){
			echo $this->SQL;
			exit;
		}else{
			return $this->TotalNum();
		}
	}

	Function Sele($Table,$Colm,$Whe="",$Chk="",$Ord="",$Limit="",$Group="",$View=""){
		$C=(!$Colm)?"*":$Colm;
		if($Whe!=""){
			$W=eregi_replace(",,","##",$Whe);
			$W=eregi_replace(","," and ",$W);
			$W=eregi_replace("##",",",$W);
			$W=" where ".$W;
		}
		$O=(!$Ord)?"":" order by ".eregi_replace("="," ",$Ord);
		$L=(!$Limit)?"":" limit ".$Limit;
		$G=(!$Group)?"":" group by ".$Group;
		$this->SQL="select $C from $Table $W $G $O $L";

		if($_REQUEST[sun]) echo $this->SQL."<br/>"; // kbh

		if($View!=false){
			echo $this->SQL;exit;
		}else{
			if($Chk!=false){
				return $this->FArr($this->SQL);
			}else{
				return $this->FRow($this->SQL);
			}
		}
	}
}
?>
