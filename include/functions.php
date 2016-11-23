<?php
	//session_start();
	include_once "config.php";
	define(showrecs,"30");
	define(pagerange,"10");

	///////////////////////////////////////////////////////////////
	function jspq($s)
	{
		return str_replace(array('\\', '\'', "\n", "\r"), array('\\\\', '\\\'', "\\n", "\\r"), $s);
	}
	function jsobj($row)
	{
		$tmp = array();
		foreach ($row as $k => $v)
			$tmp[] = $k.":'" . jspq($v) . "'";
		return '{' . implode(',', $tmp) . '}';
	}
	//////////////////////// Date /////////////////////////////////
	function add_date($orgDate,$mth)
	{
		$cd = strtotime($orgDate);
		$retDAY = date('Y-m-d', mktime(0,0,0,date('m',$cd),date('d',$cd)+$mth,date('Y',$cd)));
		return $retDAY;
	};
	////////////////////  InsertData //////////////////////////////
	function autoInsert($table,$data)
	{
		$str="select * from " . $table;
		$result=mysql_query($str);

		$numField=mysql_num_fields($result);
		$sqlCommand="insert into ".$table . "(";
		for($i=1;$i<$numField;$i++)
		{
			$sqlCommand .= mysql_field_name($result,$i);
			if ($i!=$numField-1) $sqlCommand.= ",";
		}
		$sqlCommand.=") values(";
		for($i=0;$i<count($data);$i++)
		{
			$sqlCommand .= "\"" . $data[$i] . "\"";
			if ($i!=count($data)-1) $sqlCommand.= ",";
		}
		$sqlCommand.=")";

		$result=mysql_query($sqlCommand);

		if (!$result) {
			$message  = 'Invalid query: ' . mysql_error() . "\n";
			$message .= 'Whole query: ' . $sqlCommand;
			die($message);
			return false;
		}else{
			return true;
		}
	};

	function autoInsertWithAlert($table,$data,$url)
	{
		$str="select * from " . $table;
		$result=mysql_query($str);

		$numField=mysql_num_fields($result);
		$sqlCommand="insert into ".$table . "(";
		for($i=1;$i<$numField;$i++)
		{
			$sqlCommand .= mysql_field_name($result,$i);
			if ($i!=$numField-1) $sqlCommand.= ",";
		}
		$sqlCommand.=") values(";
		for($i=0;$i<count($data);$i++)
		{
			$sqlCommand .= "'" . $data[$i] . "'";
			if ($i!=count($data)-1) $sqlCommand.= ",";
		}
		$sqlCommand.=")";
		//echo $sqlCommand;
		$result=mysql_query($sqlCommand);
		echo '<script language="javascript">';
		if($result==MYSQL_COMMAND_OK)
		{
			echo ' alert(\'บันทึกข้อมูลเรียบร้อย\');';
		}else{
			echo ' alert(\'ไม่สามารถบันทึกข้อมูลได้\');';
		};
		echo '</script>';
		echo '<script language="javascript">window.location = "' . $url . '"</script>';
	};
	/*
	function sql_insert_id($key,$table)
	{
		$select = "SELECT max(".$key.") from ".$table;
		$load = mysql_query($select);
		$id = mysql_fetch_array($load,null,MYSQL_NUM);
		return $id[0];
	}*/
	/////////////////////// Update Data ////////////////////////////
	function autoUpdate($table,$data,$col,$key,$url)
	{
		$str="select * from " . $table;
		$result=mysql_query($str);
		$numField=mysql_num_fields($result);
		$sqlCommand="update ".$table." set ";
		switch($table)
		{
			case 'tb_thaiship_porttype':
				$numField++;
				break;
			case 'tb_thaiship_portshiptype':
				$numField++;
				break;
			case 'tb_thaiship_portfunction':
				$numField++;
				break;
			case 'tb_thaiship_portarea':
				$numField++;
				break;
			case 'tb_thaiship_portcompany':
				$numField++;
				break;
			case 'tb_thaiship_measure':
				$numField++;
				break;
			case 'tb_thaiship_builder':
				$numField++;
				break;
		}
		for($i=1;$i<$numField-1;$i++)
		{
			//if (($i!=$numField-5) && ($i!=$numField-4))
			$sqlCommand .= mysql_field_name($result,$i) . "='" . $data[$i-1] . "'";
				//echo $sqlCommand.'<br>';
				if ($i!=count($data)) $sqlCommand.= ",";
		}
		$sqlCommand=substr($sqlCommand,0,-1);
		$sqlCommand.=" where " . $col . "='" . $key . "'";
		//echo $sqlCommand.'<br>';
		$result=mysql_query($sqlCommand);
		//echo '<script language="javascript">alert(\'เนเธเนเนเธเธเนเธญเธกเธนเธฅเน€เธฃเธตเธขเธเธฃเนเธญเธข\')</script>';
		//header("location: $url");
		echo '<script language="javascript">';
		if($result==MYSQL_COMMAND_OK)
		{
			echo 'alert(\'แก้ไขข้อมูลเรียบร้อย\');';
			//echo 'window.location = "' . $url;
		}else{
			echo 'alert(\'ไม่สามารถแก้ไขข้อมูลได้\');';
		};
		echo '</script>';
		echo '<script language="javascript">window.location = "' . $url . '"</script>';
	};
	//////////////////////// Delete Data ////////////////////////////
	function autoDelete($table,$col,$key,$url)
	{
		/*$str="select * from " . $table;
		$result=mysql_query($str);
		$numField=mysql_num_fields($result);*/
		$sqlCommand="delete from " . $table . " where " . $col . "=" . $key;
		//echo $sqlCommand;
		$result=mysql_query($sqlCommand);
		if($result==MYSQL_COMMAND_OK)
		{
			echo '<script language="javascript">alert(\'ลบข้อมูลเรียบร้อย\')</script>';
		}else{
			echo '<script language="javascript">alert(\'ไม่สามารถลบข้อมูลได้\')</script>';
		};
		echo '<script language="javascript">window.location = "' . $url . '"</script>';
	};



	/////////////////////////// Show Data ///////////////////////////
	function showData($table,$col,$key)
	{
		$str="select * from " . $table;
		//echo $str;
		$result=mysql_query($str);
		$data=mysql_num_fields($result);
		switch($table) {
			case 'tb_thaiship_portshiptype':$data++;
				break;
			case 'tb_thaiship_measure':$data++;
				break;
			case 'tb_thaiship_porttype':$data++;
				break;
			case 'tb_thaiship_portfunction':$data++;
				break;
			case 'tb_thaiship_portarea':$data++;
				break;
			case 'tb_thaiship_portcompany':$data++;
				break;
		}
		$str2="select ";
		for($i=0;$i<$data-1;$i++)
		{
			$str2.=mysql_field_name($result,$i).',';
		};
		$str2=substr($str2,0,-1);
		$str2.=" from " . $table . " where " . $col . "='" . $key . "'";
		//echo $str2;
		$result=mysql_query($str2);
		$data=mysql_fetch_array($result);
		return ($data);
	};

	function showDataNews($table,$cmd,$col,$key,$id)
	{
		//$showrecs=$_SESSION["showrecs"];
		$showrecs=showrecs;
		if($_GET[page]!='')
		{
			$page=$_GET[page];
		}else{
			$page=1;
		};
		$key=" = ".$key;
		$str=$cmd . " and " . $col . $key . " order by " . $id . " desc";
		//echo $str;
		$result=mysql_query($str);
		$allrows=mysql_num_rows($result);
		if ($allrows%$showrecs!=0){
			$pagecount=intval($allrows/$showrecs)+1;
		}else{
			$pagecount=intval($allrows/$showrecs);
		};
		$startrec=$showrecs*($page-1);

		if($startrec<$allrows){@mysql_data_seek($result,$startrec);}
		//echo $showrecs.'------'.$startrec;
		$reccount=min($showrecs*$page,$count);
		$numfield=mysql_num_fields($result);

		switch($table){
			case 'view_thaiship_measure':
				//$numfield-=1;
				break;
			case 'tb_watermeter':
				$numfield-=1;
				break;
			case 'tb_watermeterrid':
				$numfield-=1;
				break;
			case 'tb_news':
				$numfield-=1;
				break;
		}

		//$colName=getColName($table);
		echo '<fieldset><legend><h4>ข่าวประชาสัมพันธ์</h4></legend>';
		echo showpagenav($page,$pagecount,$url);


		echo '<table class="grid">';
		/*
		echo '<thead><tr><td colspan="3"></td>';
		for($i=0;$i<$numfield;$i++)
		{
			echo '<th>' . $colName[$i].'</th>';
		}
		echo '</tr></thead>';
		*/
		$j=(($page-1)*$showrecs)+1;
		//while($data_row=mysql_fetch_array($result))
		for($k=0;$k<$showrecs;$k++)
		{
			$data_row=mysql_fetch_array($result);
			if($data_row!=null)
			{
				echo '<tr class="';
				echo ($j%2)==1?'odd">':'even">';
				echo '<td width="100%"><table border="1" width="100%"><tr><td align="right" width="15%"><b>วันที่</b></td><td align="left">'.$data_row[1].'</td></tr>';
				echo '<tr><td align="right"><b>หัวข้อข่าว</b></td><td align="left">'.$data_row[2].'</td></tr>';
				echo '<tr><td align="right"><b>เนื้อหา</b></td><td align="left">'.$data_row[3].'</td></tr>';
				echo '<tr><td align="right" valign="top"><b>รูป</b></td><td align="left">';
				echo ($data_row[4]!='')?'<a href="./uploads/'.$data_row[4].'" target="_blank"><img src="./uploads/'.$data_row[4].'" width="100%"></a></br>':'';
				echo ($data_row[5]!='')?'<a href="./uploads/'.$data_row[5].'" target="_blank"><img src="./uploads/'.$data_row[5].'" width="100%"></a></br>':'';
				echo ($data_row[6]!='')?'<a href="./uploads/'.$data_row[6].'" target="_blank"><img src="./uploads/'.$data_row[6].'" width="100%"></a></br>':'';
				echo ($data_row[7]!='')?'<a href="./uploads/'.$data_row[7].'" target="_blank"><img src="./uploads/'.$data_row[7].'" width="100%"></a></br>':'';
				echo ($data_row[8]!='')?'<a href="./uploads/'.$data_row[8].'" target="_blank"><img src="./uploads/'.$data_row[8].'" width="100%"></a></br>':'';
				echo ($data_row[9]!='')?'<a href="./uploads/'.$data_row[9].'" target="_blank"><img src="./uploads/'.$data_row[9].'" width="100%"></a></br>':'';
				echo ($data_row[10]!='')?'<a href="./uploads/'.$data_row[10].'" target="_blank"><img src="./uploads/'.$data_row[10].'" width="100%"></a></br>':'';
				echo ($data_row[11]!='')?'<a href="./uploads/'.$data_row[11].'" target="_blank"><img src="./uploads/'.$data_row[11].'" width="100%"></a></br>':'';
				echo ($data_row[12]!='')?'<a href="./uploads/'.$data_row[12].'" target="_blank"><img src="./uploads/'.$data_row[12].'" width="100%"></a></br>':'';
				echo ($data_row[13]!='')?'<a href="./uploads/'.$data_row[13].'" target="_blank"><img src="./uploads/'.$data_row[13].'" width="100%"></a></br>':'';
				echo '</td></tr>';
				echo '</table></td></tr><tr><td colspan="2">&nbsp;</td></tr>';
				$j++;
			};
		};
		echo '</table></fieldset>';
	}
	///////////////////////////// Search /////////////////////////////
	function searchData($table,$col,$key,$url,$sort)
	{
		$str="select * from " . $table . " where " . $col . " like '%" . $key . "%' and retire='0' order by ".$sort." asc";
		//echo $str;
		$result=mysql_query($str);
		$numfield=mysql_num_fields($result);
		$colName=getColName($table);
		echo '<fieldset><legend><h3>เธเธฅเธเธฒเธฃเธเนเธเธซเธฒ</h3></legend>';
		echo '<table>';
		echo '<thead><tr>';
		for($i=0;$i<$numfield-5;$i++)
		{
			echo '<th>' . $colName[$i] . '</th>';
		}
		echo '</tr></thead>';
		$j=1;
		while($data_row=mysql_fetch_array($result))
		{
			echo '<tr class="';
			echo ($j%2)==1?'odd">':'even">';
			for($i=0;$i<$numfield-5;$i++)
			{
				if (($data_row[$i])!=''){
					if(is_numeric($data_row[$i]))
					{
						if($i==0)
							echo '<td align="right">' . $j . '</td>';
						else
							echo '<td align="right">' . $data_row[$i] . '</td>';
					}else{
						echo '<td>' . $data_row[$i] . '</td>';
					};
				}else{
					echo '<td>&nbsp;</td>';}
			};
			echo '<td><a href="' . $url . $data_row[0].'">เน€เธฅเธทเธญเธ</a></td>';
			echo '</tr>';
			$j++;
		};
		echo '</table></fieldset>';
	};

	function searchData2($table,$cmd,$col,$key,$id,$url)
	{
		$act=substr($id,0,strpos($id,'.')+1) . "retire='0'";
		$str=$cmd . " and " . $col . " like '%" . $key . "%' and " . $act . " order by " . $id . " asc";
		echo $str;
		$result=mysql_query($str);
		$numfield=mysql_num_fields($result);
		$colName=getColName($table);
		echo '<fieldset><legend><h3>เธเธฅเธเธฒเธฃเธเนเธเธซเธฒ</h3></legend><table>';
		echo '<thead><tr>';
		for($i=0;$i<$numfield-5;$i++)
		{
			//echo '<td>' . mysql_field_name($result,$i) . '</td>';
			echo '<th>' . $colName[$i] . '</th>';
		}
		echo '</tr></thead>';
		$j=1;
		while($data_row=mysql_fetch_array($result))
		{
			echo '<tr class="';
			echo ($j%2)==1?'odd">':'even">';
			for($i=0;$i<$numfield-5;$i++)
			{
				if (($data_row[$i])!=''){
					if(is_numeric($data_row[$i]))
					{
						if($i==0)
							echo '<td align="right">' . $j . '</td>';
						else
							echo '<td align="right">' . $data_row[$i] . '</td>';
					}else{
						echo '<td>' . $data_row[$i] . '</td>';
					};
					//echo '<td>' . $data_row[$i] . '</td>';
				}else{
					echo '<td>&nbsp;</td>';}
			};
			if($url!='')
				echo '<td><a href="' . $url . $data_row[0].'">เน€เธฅเธทเธญเธ</a></td>';
			echo '</tr>';
			$j++;
		};
		echo '</table></fieldset>';
	};

	function searchData3($table,$cmd,$col,$key,$id,$url)
	{
		$act=substr($col,strpos($col,'.')+1);
		if(($act=='p_name')||($act=='des_area'))
		{
			$key=" like '%".$key."%'";
		}else{
			$str="select ".$act." from ".$table;
			//echo $str;
			$res = mysql_query($str);

			//echo "Title field type: ", mysql_field_type($res,0);
			switch(mysql_field_type($res,0)){
				case 'int4':if($key==''){
								$key=" >=0";
							}else{
								$key=" <=".$key;
							};
							break;
				default:$key=" like '%".$key."%'";
			};
		};

		$act=substr($id,0,strpos($id,'.')+1) . "retire='0'";
		$str=$cmd . " and " . $col . $key . " and " . $act . " order by " . $id . " asc";
		//echo $str;
		$result=mysql_query($str);
		$numfield=mysql_num_fields($result);
		$colName=getColName($table);
		echo '<fieldset><legend><h3>เธเธฅเธเธฒเธฃเธเนเธเธซเธฒ</h3></legend>';
		echo '<table>';
		echo '<thead><tr>';
		for($i=0;$i<$numfield-5;$i++)
		{
			echo '<th>' . $colName[$i] . '</th>';
		}
		echo '</tr></thead>';
		$j=1;
		while($data_row=mysql_fetch_array($result))
		{
			echo '<tr class="';
			echo ($j%2)==1?'odd">':'even">';
			for($i=0;$i<$numfield-5;$i++)
			{
				if (($data_row[$i])!=''){
					if(is_numeric($data_row[$i]))
					{
						if($i==0)
							echo '<td align="right">' . $j . '</td>';
						else
							echo '<td align="right">' . $data_row[$i] . '</td>';
					}else{
						echo '<td>' . $data_row[$i] . '</td>';
					};
				}else{
					echo '<td>&nbsp;</td>';}
			};
			echo '<td><a href="' . $url . $data_row[0].'">เน€เธฅเธทเธญเธ</a></td>';
			echo '</tr>';
			$j++;
		};
		echo '</table></fieldset>';
	};
	///////////////////////////////////// Search With A/E/D //////////////////////////////////////
	function searchDataAED($table,$cmd,$col,$key,$id,$url)
	{
		$showrecs=$_SESSION["showrecs"];
		if($_GET[page]!='')
		{
			$page=$_GET[page];
		}else{
			$page=1;
		};
		if($col!='')
			$act=substr($col,strpos($col,'.')+1);
		else
			$act='*';
		if(($act=='p_name')||($act=='des_area'))
		{
			$key=" like '%".$key."%'";
		}else{
			$str="select ".$act." from ".$table;
			//echo $str;
			$res = mysql_query($str);

			//echo "Title field type: ", mysql_field_type($res,0);
			switch(mysql_field_type($res,0)){
				case 'int4':if($key==''){
								$key=" >=0";
							}else{
								$key=" <=".$key;
							};
							break;
				default:$key=" like '%".$key."%'";
			};
		};

		$act=substr($id,0,strpos($id,'.')+1) . "retire='0'";
		if($col!='')
		{
			$str=$cmd . " and " . $col . $key . " and " . $act . " order by " . $id . " asc";
		}else{
			$str=$cmd . " and " . $act . " order by " . $id . " asc";
		};
		//echo $str;
		$result=mysql_query($str);
		$allrows=mysql_num_rows($result);
		//echo $allrows.'==========='.$showrecs;
		if ($allrows%$showrecs!=0){
			$pagecount=intval($allrows/$showrecs)+1;
		}else{
			$pagecount=intval($allrows/$showrecs);
		};
		$startrec=$showrecs*($page-1);
		//echo $showrecs.'------'.$startrec;
		if($startrec<$allrows){@mysql_result_seek($result,$startrec);}
		$reccount=min($showrecs*$page,$count);
		$numfield=mysql_num_fields($result);
		$colName=getColName($table);
		echo '<fieldset><legend><h3>เธเธฅเธเธฒเธฃเธเนเธเธซเธฒ</h3></legend>';
		showpagenav($page,$pagecount,$url);
		echo '<table>';
		echo '<thead><tr><td></td><td></td><td></td>';
		for($i=0;$i<$numfield-5;$i++)
		{
			echo '<th>' . $colName[$i] . '</th>';
		}
		$j=(($page-1)*$showrecs)+1;
		//while($data_row=mysql_fetch_array($result))
		for($k=0;$k<$showrecs;$k++)
		{
			if($data_row!=null)
			{
				echo '<tr class="';
				echo ($j%2)==1?'odd">':'even">';
				echo '<td><a href="'.$url.$data_row[0].'&select=view">View</a></td><td><a href="'.$url.$data_row[0].'&select=edit">Edit</a></td><td><a href="'.$url.$data_row[0].'&select=del">Delete</a></td>';
				for($i=0;$i<$numfield-5;$i++)
				{
					if (($data_row[$i])!=''){
						if(is_numeric($data_row[$i]))
						{
							if($i==0)
								echo '<td align="right">' . $j . '</td>';
							else
								echo '<td align="right">' . $data_row[$i] . '</td>';
						}else{
							echo '<td>' . $data_row[$i] . '</td>';
						};
					}else{
						echo '<td>&nbsp;</td>';}
				};
				//echo '<td><a href="' . $url . $data_row[0].'">เน€เธฅเธทเธญเธ</a></td>';
				echo '</tr>';
				$j++;
			};
		};
		echo '</table></fieldset>';
	};

	function searchDataAED1($table,$col,$key,$url,$sort)
	{
		$showrecs=$_SESSION["showrecs"];
		if($_GET[page]!='')
		{
			$page=$_GET[page];
		}else{
			$page=1;
		};
		$key=" like '%".$key."%'";
		$str="select * from " . $table . " where " . $col . $key . " and retire='0' order by ".$sort." asc";
		echo $str;
		$result=mysql_query($str);
		$allrows=mysql_num_rows($result);
		//echo $allrows.'==========='.$showrecs;
		if ($allrows%$showrecs!=0){
			$pagecount=intval($allrows/$showrecs)+1;
		}else{
			$pagecount=intval($allrows/$showrecs);
		};
		$startrec=$showrecs*($page-1);
		//echo $showrecs.'------'.$startrec;
		if($startrec<$allrows){@mysql_result_seek($result,$startrec);}
		$reccount=min($showrecs*$page,$count);
		$numfield=mysql_num_fields($result);

		$colName=getColName($table);
		echo '<fieldset><legend><h4>เธเธฅเธเธฒเธฃเธเนเธเธซเธฒ</h4></legend>';
		showpagenav($page,$pagecount,$url);
		echo '<table width="';
		if(((strlen(implode(",",$colName))+1)*8)<500)
			echo '100%';
		else
			echo ((strlen(implode(",",$colName))+1)*8);
		echo '">';
		echo '<thead><tr><td></td><td></td><td></td>';
		for($i=0;$i<$numfield-5;$i++)
		{
			echo '<th width="'.((strlen($colName[$i])+1)*8).'">' . $colName[$i] . '</th>';
		}
		echo '</tr></thead>';
		$j=(($page-1)*$showrecs)+1;
		//while($data_row=mysql_fetch_array($result))
		for($k=0;$k<$showrecs;$k++)
		{
			$data_row=mysql_fetch_Array($result);
			if($data_row!=null)
			{
				echo '<tr class="';
				echo ($j%2)==1?'odd">':'even">';
				echo '<td><a href="'.$url.$data_row[0].'&select=view">View</a></td><td><a href="'.$url.$data_row[0].'&select=edit">Edit</a></td><td><a href="'.$url.$data_row[0].'&select=del">Delete</a></td>';
				for($i=0;$i<$numfield-5;$i++)
				{
					if (($data_row[$i])!=''){
						if(is_numeric($data_row[$i]))
						{
							if($i==0)
								echo '<td align="right">' . $j . '</td>';
							else
								echo '<td align="right">' . $data_row[$i] . '</td>';
						}else{
							echo '<td>' . $data_row[$i] . '</td>';
						};
					}else{
						echo '<td>&nbsp;</td>';}
				};
				//echo '<td><a href="' . $url . $data_row[0].'">เน€เธฅเธทเธญเธ</a></td>';
				echo '</tr>';
				$j++;
			};
		};
		echo '</table></fieldset>';
	};
	function searchDataAED2($table,$cmd,$col,$key,$id,$url)
	{
		//$showrecs=$_SESSION["showrecs"];
		$showrecs=showrecs;
		if($_GET[page]!='')
		{
			$page=$_GET[page];
		}else{
			$page=1;
		};
		$key=" = ".$key;
		$str=$cmd . " and " . $col . $key . " order by " . $id . " desc";
		//echo $str;
		$result=mysql_query($str);
		$allrows=mysql_num_rows($result);
		if ($allrows%$showrecs!=0){
			$pagecount=intval($allrows/$showrecs)+1;
		}else{
			$pagecount=intval($allrows/$showrecs);
		};
		$startrec=$showrecs*($page-1);

		if($startrec<$allrows){@mysql_data_seek($result,$startrec);}
		//echo $showrecs.'------'.$startrec;
		$reccount=min($showrecs*$page,$count);
		$numfield=mysql_num_fields($result);

		switch($table){
			case 'view_thaiship_measure':
				//$numfield-=1;
				break;
			case 'tb_watermeter':
				$numfield-=1;
				$title='ข้อมูลระดับน้ำ';
				$datefield=1;
				break;
			case 'tb_watermeterrid':
				$numfield-=1;
				$datefield=1;
				$title='ข้อมูลระดับน้ำ';
				break;
			case 'tb_news':
				$numfield-=1;
				$datefield=1;
				$title='ข่าวประชาสัมพันธ์';
				break;
		}

		$colName=getColName($table);
		echo '<fieldset><legend><h4>'.$title.'</h4></legend>';
		echo showpagenav($page,$pagecount,$url);
		echo '<table class="grid">';
		echo '<thead><tr><td colspan="3"></td>';
		for($i=0;$i<$numfield;$i++)
		{
			if($i==$datefield)
			{
				echo '<th>วันที่</th><th>เวลา</th>';
			}else{
				echo '<th>' . $colName[$i].'</th>';
			};
		}
		echo '</tr></thead>';
		$j=(($page-1)*$showrecs)+1;
		//while($data_row=mysql_fetch_array($result))
		for($k=0;$k<$showrecs;$k++)
		{
			$data_row=mysql_fetch_array($result);
			if($data_row!=null)
			{
				echo '<tr class="';
				echo ($j%2)==1?'odd">':'even">';
				echo '<td align="center"><a href="'.$url.$data_row[0].'&select=view"><img src="./images/view_action.png" alt="View"/></a></td><td align="center"><a href="'.$url.$data_row[0].'&select=edit"><img src="./images/edit_action.png" alt="Edit"/></a></td><td align="center"><a href="'.$url.$data_row[0].'&select=del"><img src="./images/delete_action.png" alt="Delete"/></a></td>';
				for($i=0;$i<$numfield;$i++)
				{
					if (($data_row[$i])!=''){
						if(is_numeric($data_row[$i]))
						{
							if($i==0)
								echo '<td align="right">' . $j . '</td>';
							else
								echo '<td align="right">' . $data_row[$i] . '</td>';
						}else{
							if(($table=='view_thaiship_measure')&&($i==($numfield-1)))
							{
								echo '<td><a href="./uploads/' . $data_row[$i] . '" target="_blank">'.$data_row[$i].'</a></td>';
							}else{
								if($i==$datefield)
								{
									$datetime=explode(" ",$data_row[$i]);
									echo '<td>' . $datetime[0] . '</td><td>' . $datetime[1] . '</td>';
								}else{
									echo '<td>' . $data_row[$i] . '</td>';
								};
							}
						};
					}else{
						echo '<td>&nbsp;</td>';}
				};
				//echo '<td><a href="' . $url . $data_row[0].'">เน€เธฅเธทเธญเธ</a></td>';
				echo '</tr>';
				$j++;
			};
		};
		echo '</table></fieldset>';
	};

	function searchDataAED2NO($table,$cmd,$col,$key,$id,$url)
	{
		//$showrecs=$_SESSION["showrecs"];
		$showrecs=showrecs;
		if($_GET[page]!='')
		{
			$page=$_GET[page];
		}else{
			$page=1;
		};
		//$key=" = ".$key;
		//$str=$cmd . " and " . $col . $key . " order by " . $id . " desc";
		$str=$cmd . " order by " . $id;
		//echo $str;
		$result=mysql_query($str);
		$allrows=mysql_num_rows($result);
		if ($allrows%$showrecs!=0){
			$pagecount=intval($allrows/$showrecs)+1;
		}else{
			$pagecount=intval($allrows/$showrecs);
		};
		$startrec=$showrecs*($page-1);

		if($startrec<$allrows){@mysql_data_seek($result,$startrec);}
		//echo $showrecs.'------'.$startrec;
		$reccount=min($showrecs*$page,$count);
		$numfield=mysql_num_fields($result);

		switch($table){
			case 'view_thaiship_measure':
				break;
			case 'tb_sattelite':
				$title='ตารางแสดงปริมาณน้ำฝน';
				break;
			case 'tb_radarpimaia':
				$title='ตารางแสดงปริมาณน้ำฝนจากสถานีวัดน้ำฝน';
				break;
			case 'tb_radarpimait':
				$title='ตารางแสดงปริมาณน้ำฝนลุ่มน้ำ';
				break;
			case 'tb_radarsurina':
				$title='ตารางแสดงปริมาณน้ำฝนจากสถานีวัดน้ำฝน';
				break;
			case 'tb_radarsurint':
				$title='ตารางแสดงปริมาณน้ำฝนลุ่มน้ำ';
				break;
			case 'tb_telemetry':
				$title='ตารางแสดงปริมาณน้ำฝน';
				break;
			case 'tb_news':
				$numfield-=1;
				$datefield=1;
				$title='ข่าวประชาสัมพันธ์';
				break;
		}

		$colName=getColName($table);
		echo '<fieldset><legend><h4>'.$title.'</h4></legend>';
		//echo showpagenav($page,$pagecount,$url);
		echo '<table class="grid">';
		echo '<thead><tr>';
		for($i=0;$i<$numfield;$i++)
		{
			echo '<th>' . $colName[$i].'</th>';
		}
		echo '</tr></thead>';
		$j=(($page-1)*$showrecs)+1;
		//while($data_row=mysql_fetch_array($result))
		for($k=0;$k<$showrecs;$k++)
		{
			$data_row=mysql_fetch_array($result);
			if($data_row!=null)
			{
				echo '<tr class="';
				echo ($j%2)==1?'odd">':'even">';
				//echo '<td align="center"><a href="'.$url.$data_row[0].'&select=view"><img src="./images/view_action.png" alt="View"/></a></td><td align="center"><a href="'.$url.$data_row[0].'&select=edit"><img src="./images/edit_action.png" alt="Edit"/></a></td><td align="center"><a href="'.$url.$data_row[0].'&select=del"><img src="./images/delete_action.png" alt="Delete"/></a></td>';
				for($i=0;$i<$numfield;$i++)
				{
					if (($data_row[$i])!=''){
						if(is_numeric($data_row[$i]))
						{
							if($i==0)
								echo '<td align="right">' . $j . '</td>';
							else
								echo '<td align="right">' . $data_row[$i] . '</td>';
						}else{
							if(($table=='view_thaiship_measure')&&($i==($numfield-1)))
							{
								echo '<td><a href="./uploads/' . $data_row[$i] . '" target="_blank">'.$data_row[$i].'</a></td>';
							}else{
								if($i==$datefield)
								{
									$datetime=explode(" ",$data_row[$i]);
									echo '<td>' . $datetime[0] . '</td><td>' . $datetime[1] . '</td>';
								}else{
									echo '<td>' . $data_row[$i] . '</td>';
								};
							}
						};
					}else{
						echo '<td>&nbsp;</td>';}
				};
				//echo '<td><a href="' . $url . $data_row[0].'">เน€เธฅเธทเธญเธ</a></td>';
				echo '</tr>';
				$j++;
			};
		};
		echo '</table>';
		echo showpagenav($page,$pagecount,$url);
		echo '</fieldset>';
	};

	function searchDataHydro4WaterLevel($table,$cmd,$col,$key,$id,$url)
	{
		//$showrecs=$_SESSION["showrecs"];
		$showrecs=showrecs;
		if($_GET[page]!='')
		{
			$page=$_GET[page];
		}else{
			$page=1;
		};
		//$key=" = ".$key;
		//$str=$cmd . " and " . $col . $key . " order by " . $id . " desc";
		$str=$cmd . " order by " . $id;
		//echo $str.'</br>';
		$result=mysql_query($str);
		$allrows=mysql_num_rows($result);
		if ($allrows%$showrecs!=0){
			$pagecount=intval($allrows/$showrecs)+1;
		}else{
			$pagecount=intval($allrows/$showrecs);
		};
		$startrec=$showrecs*($page-1);

		if($startrec<$allrows){@mysql_data_seek($result,$startrec);}
		//echo $showrecs.'------'.$startrec;
		$reccount=min($showrecs*$page,$count);
		$numfield=mysql_num_fields($result);

		switch($table){
			case 'view_thaiship_measure':
				break;
			case 'tb_sattelite':
				$title='ตารางแสดงปริมาณน้ำฝน';
				break;
			case 'tb_radarpimaia':
				$title='ตารางแสดงปริมาณน้ำฝนจากสถานีวัดน้ำฝน';
				break;
			case 'tb_radarpimait':
				$title='ตารางแสดงปริมาณน้ำฝนลุ่มน้ำ';
				break;
			case 'tb_radarsurina':
				$title='ตารางแสดงปริมาณน้ำฝนจากสถานีวัดน้ำฝน';
				break;
			case 'tb_radarsurint':
				$title='ตารางแสดงปริมาณน้ำฝนลุ่มน้ำ';
				break;
			case 'tb_telemetry':
				$title='ตารางแสดงปริมาณน้ำฝน';
				break;
			case 'tb_news':
				$numfield-=1;
				$datefield=1;
				$title='ข่าวประชาสัมพันธ์';
				break;
		}

		$colName=array('ลำดับ','วันที่');
		$str="select distinct tr_code from tb_telerid_hydro4runoff order by tr_code asc";
		$result_code=mysql_query($str);
		while($data_code=mysql_fetch_array($result_code))
		{
			array_push($colName,$data_code[0]);
		}
		//$colName=getColName($table);

		echo '<fieldset><legend><h4>Hydro4 ระดับน้ำ ม. (ร.ส.ม.)</h4></legend>';
		//echo showpagenav($page,$pagecount,$url);
		echo '<table class="grid">';
		echo '<thead><tr>';
		for($i=0;$i<count($colName);$i++)
		{
			echo '<th>' . $colName[$i].'</th>';
		}
		echo '</tr></thead>';
		$j=(($page-1)*$showrecs)+1;
		//while($data_row=mysql_fetch_array($result))
		for($k=0;$k<$showrecs;$k++)
		{
			$data_row=mysql_fetch_array($result);
			if($data_row!=null)
			{
				echo '<tr class="';
				echo ($j%2)==1?'odd">':'even">';
				//echo '<td align="center"><a href="'.$url.$data_row[0].'&select=view"><img src="./images/view_action.png" alt="View"/></a></td><td align="center"><a href="'.$url.$data_row[0].'&select=edit"><img src="./images/edit_action.png" alt="Edit"/></a></td><td align="center"><a href="'.$url.$data_row[0].'&select=del"><img src="./images/delete_action.png" alt="Delete"/></a></td>';
				for($i=0;$i<count($colName);$i++)
				{
					if($i==0)
								echo '<td align="right">' . $j . '</td>';
					if($i==1)
								echo '<td align="right" width="100px">' . $data_row[0] . '</td>';
					if($i>1)
					{
						$str="select tr_water from tb_telerid_hydro4runoff where tr_date='".$data_row[0]."' and tr_code='".$colName[$i]."'";
						//echo $str.'</br>';
						$result_value=mysql_query($str);
						$values=mysql_fetch_array($result_value);
						echo '<td align="right">' . $values[0] . '</td>';
					}
				};
				//echo '<td><a href="' . $url . $data_row[0].'">เน€เธฅเธทเธญเธ</a></td>';
				echo '</tr>';
				$j++;
			};
		};
		echo '</table>';
		echo showpagenav($page,$pagecount,$url);
		echo '</fieldset>';
	};

	function searchDataHydro4Discharge($table,$cmd,$col,$key,$id,$url)
	{
		//$showrecs=$_SESSION["showrecs"];
		$showrecs=showrecs;
		if($_GET[page]!='')
		{
			$page=$_GET[page];
		}else{
			$page=1;
		};
		//$key=" = ".$key;
		//$str=$cmd . " and " . $col . $key . " order by " . $id . " desc";
		$str=$cmd . " order by " . $id;
		//echo $str.'</br>';
		$result=mysql_query($str);
		$allrows=mysql_num_rows($result);
		if ($allrows%$showrecs!=0){
			$pagecount=intval($allrows/$showrecs)+1;
		}else{
			$pagecount=intval($allrows/$showrecs);
		};
		$startrec=$showrecs*($page-1);

		if($startrec<$allrows){@mysql_data_seek($result,$startrec);}
		//echo $showrecs.'------'.$startrec;
		$reccount=min($showrecs*$page,$count);
		$numfield=mysql_num_fields($result);

		$colName=array('ลำดับ','วันที่');
		$str="select distinct tr_code from tb_telerid_hydro4runoff order by tr_code asc";
		$result_code=mysql_query($str);
		while($data_code=mysql_fetch_array($result_code))
		{
			array_push($colName,$data_code[0]);
		}
		//$colName=getColName($table);

		echo '<fieldset><legend><h4>Hydro4 ปริมาณน้ำ ลบ.ม./วิ</h4></legend>';
		//echo showpagenav($page,$pagecount,$url);
		echo '<table class="grid">';
		echo '<thead><tr>';
		for($i=0;$i<count($colName);$i++)
		{
			echo '<th>' . $colName[$i].'</th>';
		}
		echo '</tr></thead>';
		$j=(($page-1)*$showrecs)+1;
		for($k=0;$k<$showrecs;$k++)
		{
			$data_row=mysql_fetch_array($result);
			if($data_row!=null)
			{
				echo '<tr class="';
				echo ($j%2)==1?'odd">':'even">';
				for($i=0;$i<count($colName);$i++)
				{
					if($i==0)
								echo '<td align="right">' . $j . '</td>';
					if($i==1)
								echo '<td align="right" width="100px">' . $data_row[0] . '</td>';
					if($i>1)
					{
						$str="select tr_flow from tb_telerid_hydro4runoff where tr_date='".$data_row[0]."' and tr_code='".$colName[$i]."'";
						//echo $str.'</br>';
						$result_value=mysql_query($str);
						$values=mysql_fetch_array($result_value);
						echo '<td align="right">' . $values[0] . '</td>';
					}
				};
				echo '</tr>';
				$j++;
			};
		};
		echo '</table>';
		echo showpagenav($page,$pagecount,$url);
		echo '</fieldset>';
	};

	function searchDataHydro3WaterLevel($table,$cmd,$col,$key,$id,$url)
	{
		//$showrecs=$_SESSION["showrecs"];
		$showrecs=showrecs;
		if($_GET[page]!='')
		{
			$page=$_GET[page];
		}else{
			$page=1;
		};
		//$key=" = ".$key;
		//$str=$cmd . " and " . $col . $key . " order by " . $id . " desc";
		$str=$cmd . " order by " . $id;
		//echo $str.'</br>';
		$result=mysql_query($str);
		$allrows=mysql_num_rows($result);
		if ($allrows%$showrecs!=0){
			$pagecount=intval($allrows/$showrecs)+1;
		}else{
			$pagecount=intval($allrows/$showrecs);
		};
		$startrec=$showrecs*($page-1);

		if($startrec<$allrows){@mysql_data_seek($result,$startrec);}
		//echo $showrecs.'------'.$startrec;
		$reccount=min($showrecs*$page,$count);
		$numfield=mysql_num_fields($result);

		$colName=array('ลำดับ','วันที่');
		$str="select distinct tr_code from tb_telerid_hydro3 order by tr_code asc";
		$result_code=mysql_query($str);
		while($data_code=mysql_fetch_array($result_code))
		{
			array_push($colName,$data_code[0]);
		}
		//$colName=getColName($table);

		echo '<fieldset><legend><h4>Hydro3  ระดับน้ำ ม. (ร.ส.ม.)</h4></legend>';
		echo '<table class="grid">';
		echo '<thead><tr>';
		for($i=0;$i<count($colName);$i++)
		{
			echo '<th>' . $colName[$i].'</th>';
		}
		echo '</tr></thead>';
		$j=(($page-1)*$showrecs)+1;
		for($k=0;$k<$showrecs;$k++)
		{
			$data_row=mysql_fetch_array($result);
			if($data_row!=null)
			{
				echo '<tr class="';
				echo ($j%2)==1?'odd">':'even">';
				for($i=0;$i<count($colName);$i++)
				{
					if($i==0)
								echo '<td align="right">' . $j . '</td>';
					if($i==1)
								echo '<td align="right" width="100px">' . $data_row[0] . '</td>';
					if($i>1)
					{
						$str="select tr_water from tb_telerid_hydro3 where tr_date='".$data_row[0]."' and tr_code='".$colName[$i]."'";
						//echo $str.'</br>';
						$result_value=mysql_query($str);
						$values=mysql_fetch_array($result_value);
						echo '<td align="right">' . $values[0] . '</td>';
					};
				}
				echo '</tr>';
				$j++;
			};
		};
		echo '</table>';
		echo showpagenav($page,$pagecount,$url);
		echo '</fieldset>';
	};

	function searchDataHydro3Discharge($table,$cmd,$col,$key,$id,$url)
	{
		//$showrecs=$_SESSION["showrecs"];
		$showrecs=showrecs;
		if($_GET[page]!='')
		{
			$page=$_GET[page];
		}else{
			$page=1;
		};
		//$key=" = ".$key;
		//$str=$cmd . " and " . $col . $key . " order by " . $id . " desc";
		$str=$cmd . " order by " . $id;
		//echo $str.'</br>';
		$result=mysql_query($str);
		$allrows=mysql_num_rows($result);
		if ($allrows%$showrecs!=0){
			$pagecount=intval($allrows/$showrecs)+1;
		}else{
			$pagecount=intval($allrows/$showrecs);
		};
		$startrec=$showrecs*($page-1);

		if($startrec<$allrows){@mysql_data_seek($result,$startrec);}
		//echo $showrecs.'------'.$startrec;
		$reccount=min($showrecs*$page,$count);
		$numfield=mysql_num_fields($result);

		$colName=array('ลำดับ','วันที่');
		$str="select distinct tr_code from tb_telerid_hydro3 order by tr_code asc";
		$result_code=mysql_query($str);
		while($data_code=mysql_fetch_array($result_code))
		{
			array_push($colName,$data_code[0]);
		}
		//$colName=getColName($table);

		echo '<fieldset><legend><h4>Hydro3  ปริมาณน้ำ ลบ.ม./วิ</h4></legend>';
		//echo showpagenav($page,$pagecount,$url);
		echo '<table class="grid">';
		echo '<thead><tr>';
		for($i=0;$i<count($colName);$i++)
		{
			echo '<th>' . $colName[$i].'</th>';
		}
		echo '</tr></thead>';
		$j=(($page-1)*$showrecs)+1;
		for($k=0;$k<$showrecs;$k++)
		{
			$data_row=mysql_fetch_array($result);
			if($data_row!=null)
			{
				echo '<tr class="';
				echo ($j%2)==1?'odd">':'even">';
				for($i=0;$i<count($colName);$i++)
				{
					if($i==0)
								echo '<td align="right">' . $j . '</td>';
					if($i==1)
								echo '<td align="right" width="100px">' . $data_row[0] . '</td>';
					if($i>1)
					{
						$str="select tr_flow from tb_telerid_hydro3 where tr_date='".$data_row[0]."' and tr_code='".$colName[$i]."'";
						//echo $str.'</br>';
						$result_value=mysql_query($str);
						$values=mysql_fetch_array($result_value);
						echo '<td align="right">' . $values[0] . '</td>';
					}
				};
				echo '</tr>';
				$j++;
			};
		};
		echo '</table>';
		echo showpagenav($page,$pagecount,$url);
		echo '</fieldset>';
	};

	///////////////////////////////////// Search With A/E/D //////////////////////////////////////
	function searchDataV($table,$cmd,$col,$key,$id,$url)
	{
		$showrecs=$_SESSION["showrecs"];
		if($_GET[page]!='')
		{
			$page=$_GET[page];
		}else{
			$page=1;
		};
		if($col!='')
			$act=substr($col,strpos($col,'.')+1);
		else
			$act='*';
		if(($act=='p_name')||($act=='des_area'))
		{
			$key=" like '%".$key."%'";
		}else{
			$str="select ".$act." from ".$table;
			//echo $str;
			$res = mysql_query($str);

			//echo "Title field type: ", mysql_field_type($res,0);
			switch(mysql_field_type($res,0)){
				case 'int4':if($key==''){
								$key=" >=0";
							}else{
								$key=" <=".$key;
							};
							break;
				default:$key=" like '%".$key."%'";
			};
		};

		$act=substr($id,0,strpos($id,'.')+1) . "retire='0'";
		if($col!='')
		{
			$str=$cmd . " and " . $col . $key . " and " . $act . " order by " . $id . " asc";
		}else{
			$str=$cmd . " and " . $act . " order by " . $id . " asc";
		};
		//echo $str;
		$result=mysql_query($str);
		$allrows=mysql_num_rows($result);
		//echo $allrows.'==========='.$showrecs;
		if ($allrows%$showrecs!=0){
			$pagecount=intval($allrows/$showrecs)+1;
		}else{
			$pagecount=intval($allrows/$showrecs);
		};
		$startrec=$showrecs*($page-1);
		//echo $showrecs.'------'.$startrec;
		if($startrec<$allrows){@mysql_result_seek($result,$startrec);}
		$reccount=min($showrecs*$page,$count);
		$numfield=mysql_num_fields($result);
		$colName=getColName($table);
		echo '<fieldset><legend><h3>เธเธฅเธเธฒเธฃเธเนเธเธซเธฒ</h3></legend>';
		showpagenav($page,$pagecount,$url);
		echo '<table>';
		echo '<thead><tr><td></td>';
		for($i=0;$i<$numfield-5;$i++)
		{
			echo '<th>' . $colName[$i] . '</th>';
		}
		$j=(($page-1)*$showrecs)+1;
		//while($data_row=mysql_fetch_array($result))
		for($k=0;$k<$showrecs;$k++)
		{
			if($data_row!=null)
			{
				echo '<tr class="';
				echo ($j%2)==1?'odd">':'even">';
				echo '<td><a href="'.$url.$data_row[0].'&select=view">View</a></td>';
				for($i=0;$i<$numfield-5;$i++)
				{
					if (($data_row[$i])!=''){
						if(is_numeric($data_row[$i]))
						{
							if($i==0)
								echo '<td align="right">' . $j . '</td>';
							else
								echo '<td align="right">' . $data_row[$i] . '</td>';
						}else{
							echo '<td>' . $data_row[$i] . '</td>';
						};
					}else{
						echo '<td>&nbsp;</td>';}
				};
				//echo '<td><a href="' . $url . $data_row[0].'">เน€เธฅเธทเธญเธ</a></td>';
				echo '</tr>';
				$j++;
			};
		};
		echo '</table></fieldset>';
	};

	function searchDataV1($table,$col,$key,$url,$sort)
	{
		//$showrecs=$_SESSION["showrecs"];
		if($_GET[page]!='')
		{
			$page=$_GET[page];
		}else{
			$page=1;
		};
		//$act=substr($col,strpos($col,'.')+1);
		if(($act=='p_name')||($act=='des_area'))
		{
			$key=" like '%".$key."%'";
		}else{
			$str="select ".$col." from ".$table;
			//echo $str;
			$res = mysql_query($str);

			//echo "Title field type: ", mysql_field_type($res,0);
			switch(mysql_field_type($res,0)){
				case 'int4':if($key==''){
								$key=" >=0";
							}else{
								$key=" <=".$key;
							};
							break;
				case 'float8':if($key==''){
								$key=" >=0";
							}else{
								$key=" <=".$key;
							};
							break;
				case 'date':if($key==''){
								$key=" >='1990-01-01'";
								}else{
									$key=" >=".$key;
								};
								break;
				default:$key=" like '%".$key."%'";
			};
		};

		$str="select * from " . $table . " where " . $col . $key . " order by ".$sort." asc";
		//echo $str;
		$result=mysql_query($str);
		$allrows=mysql_num_rows($result);
		//echo $allrows.'==========='.$showrecs;
		if ($allrows%$showrecs!=0){
			$pagecount=intval($allrows/$showrecs)+1;
		}else{
			$pagecount=intval($allrows/$showrecs);
		};
		$startrec=$showrecs*($page-1);
		//echo $showrecs.'------'.$startrec;
		if($startrec<$allrows){@mysql_result_seek($result,$startrec);}
		$reccount=min($showrecs*$page,$count);
		$numfield=mysql_num_fields($result);
		$colName=getColName($table);
		echo '<fieldset><legend><h3>เธเธฅเธเธฒเธฃเธเนเธเธซเธฒ</h3></legend>';
		showpagenav($page,$pagecount,$url);
		echo '<table>';
		echo '<thead><tr><td></td>';
		for($i=0;$i<$numfield-5;$i++)
		{
			echo '<th>' . $colName[$i] . '</th>';
		}
		echo '</tr></thead>';
		$j=(($page-1)*$showrecs)+1;
		//while($data_row=mysql_fetch_array($result))
		for($k=0;$k<$showrecs;$k++)
		{
			$data_row=mysql_fetch_Array($result);
			if($data_row!=null)
			{
				echo '<tr class="';
				echo ($j%2)==1?'odd">':'even">';
				echo '<td><a href="'.$url.$data_row[0].'&select=view">View</a></td>';
				for($i=0;$i<$numfield-5;$i++)
				{
					if (($data_row[$i])!=''){
						if(is_numeric($data_row[$i]))
						{
							if($i==0)
								echo '<td align="right">' . $j . '</td>';
							else
								echo '<td align="right">' . $data_row[$i] . '</td>';
						}else{
							echo '<td>' . $data_row[$i] . '</td>';
						};
					}else{
						echo '<td>&nbsp;</td>';}
				};
				//echo '<td><a href="' . $url . $data_row[0].'">เน€เธฅเธทเธญเธ</a></td>';
				echo '</tr>';
				$j++;
			};
		};
		echo '</table></fieldset>';
	};

	//function searchDataV2($table,$cmd,$col,$key,$id,$url)
	function searchDataV2($table,$col,$key,$id,$url)
	{
		$showrecs=showrecs;

		if($_GET[page]!='')
		{
			$page=$_GET[page];
		}else{
			$page=1;
		};

		$cols=explode("|",$col);
		$str="select ".$id." from ".$table;
		//echo $str;
		$res = mysql_query($str);
		$a=mysql_field_type($res,0);

		//echo "Title field type: ", mysql_field_type($res,0);
		switch($a){
			case 'int4':if($key==''){
							$key=" >=0";
						}else{
							$key=" =".$key;
						};
						break;
			case 'float8':if($key==''){
							$key=" >=0";
						}else{
							$key=" <=".$key;
						};
						break;
			case 'date':if($key==''){
							$key=" >='1990-01-01'";
							}else{
								$key=" >=".$key;
							};
							break;
			default:$key=" like '%".$key."%'";
		};

		//$act=substr($id,0,strpos($id,'.')+1) . "retire='0'";
		$str="select " . $col . " from " . $table . " order by " . $id . " asc";
		//echo $str;
		$result=mysql_query($str);
		$allrows=mysql_num_rows($result);
		if ($allrows%$showrecs!=0){
			$pagecount=intval($allrows/$showrecs)+1;
		}else{
			$pagecount=intval($allrows/$showrecs);
		};
		$startrec=$showrecs*($page-1);
		if($startrec<$allrows){@mysql_result_seek($result,$startrec);}
		$reccount=min($showrecs*$page,$count);
		//$gentable='<fieldset><legend><h3>เธเธฅเธเธฒเธฃเธเนเธเธซเธฒ</h3></legend>';
		$gentable='<h3>เธเธฅเธเธฒเธฃเธเนเธเธซเธฒ</h3>';

		return $str;

		$gentable.=showpagenav($page,$pagecount,$url);
		$gentable.= '<table>';
		$gentable.= '<thead><tr><td></td>';

		for($i=0;$i<count($cols);$i++)
		{
			$gentable.= '<th>' . $cols[$i] . '</th>';
		}
		$gentable.= '</tr></thead>';
		$j=(($page-1)*$showrecs)+1;
		//while($data_row=mysql_fetch_array($result))
		for($k=0;$k<$showrecs;$k++)
		{
			$data_row=mysql_fetch_Array($result);
			if($data_row!=null)
			{
				$gentable.= '<tr class="';
				($j%2)==1?$gentable.= 'odd">':$gentable.= 'even">';
				$gentable.= '<td><a href="'.$url.$data_row[0].'&select=view">View</a></td>';
				for($i=0;$i<$numfield-5;$i++)
				{
					if (($data_row[$i])!=''){
						if(is_numeric($data_row[$i]))
						{
							if($i==0)
								$gentable.= '<td align="right">' . $j . '</td>';
							else
								$gentable.= '<td align="right">' . $data_row[$i] . '</td>';
						}else{
							$gentable.= '<td>' . $data_row[$i] . '</td>';
						};
					}else{
						$gentable.= '<td>&nbsp;</td>';}
				};
				//$gentable.= '<td><a href="' . $url . $data_row[0].'">เน€เธฅเธทเธญเธ</a></td>';
				$gentable.= '</tr>';
				$j++;
			};
		};
		$gentable.= '</table></fieldset>';

		return 'aaaa';
	};

	function searchDataV3($table,$cmd,$col,$key,$id,$url)
	{
		$showrecs=$_SESSION["showrecs"];
		if($_GET[page]!='')
		{
			$page=$_GET[page];
		}else{
			$page=1;
		};
		$act=substr($col,strpos($col,'.')+1);
		if(($act=='p_name')||($act=='des_area'))
		{
			$key=" like '%".$key."%'";
		}else{
			$str="select ".$act." from ".$table;
			//echo $str;
			$res = mysql_query($str);

			//echo "Title field type: ", mysql_field_type($res,0);
			switch(mysql_field_type($res,0)){
				case 'int4':if($key==''){
								$key=" >=0";
							}else{
								$key=" <=".$key;
							};
							break;
				case 'float8':if($key==''){
								$key=" >=0";
							}else{
								$key=" <=".$key;
							};
							break;
				case 'date':if($key==''){
								$key=" >='1990-01-01'";
								}else{
									$key=" >=".$key;
								};
								break;
				default:$key=" like '%".$key."%'";
			};
		};

		$act="retire='0'";
		$str=$cmd . " " . $col . $key . " and " . $act . " order by " . $id . " asc";
		//echo $str;
		$result=mysql_query($str);
		$allrows=mysql_num_rows($result);
		if ($allrows%$showrecs!=0){
			$pagecount=intval($allrows/$showrecs)+1;
		}else{
			$pagecount=intval($allrows/$showrecs);
		};
		$startrec=$showrecs*($page-1);
		//echo $showrecs.'------'.$startrec;
		if($startrec<$allrows){@mysql_result_seek($result,$startrec);}
		$reccount=min($showrecs*$page,$count);
		$numfield=mysql_num_fields($result);
		$colName=getColName($table);
		echo '<fieldset><legend><h3>เธเธฅเธเธฒเธฃเธเนเธเธซเธฒ</h3></legend>';
		showpagenav($page,$pagecount,$url);
		echo '<table>';
		echo '<thead><tr><td></td>';
		for($i=0;$i<$numfield-5;$i++)
		{
			echo '<th>' . $colName[$i] . '</th>';
		}
		echo '</tr></thead>';
		$j=(($page-1)*$showrecs)+1;
		//while($data_row=mysql_fetch_array($result))
		for($k=0;$k<$showrecs;$k++)
		{
			$data_row=mysql_fetch_Array($result);
			if($data_row!=null)
			{
				echo '<tr class="';
				echo ($j%2)==1?'odd">':'even">';
				echo '<td><a href="'.$url.$data_row[0].'&select=view">View</a></td>';
				for($i=0;$i<$numfield-5;$i++)
				{
					if (($data_row[$i])!=''){
						if(is_numeric($data_row[$i]))
						{
							if($i==0)
								echo '<td align="right">' . $j . '</td>';
							else
								echo '<td align="right">' . $data_row[$i] . '</td>';
						}else{
							echo '<td>' . $data_row[$i] . '</td>';
						};
					}else{
						echo '<td>&nbsp;</td>';}
				};
				//echo '<td><a href="' . $url . $data_row[0].'">เน€เธฅเธทเธญเธ</a></td>';
				echo '</tr>';
				$j++;
			};
		};
		echo '</table></fieldset>';
	};

	//////////////////////////// Page ////////////////////////////////
	function showpagenav($page,$pagecount,$url)
	{
		$maxcol=15;
		$gentable.= '<table class="bd" border="0" cellspacing="1" cellpadding="4"><tr>';
		if($page > 1)
			$gentable.= '<td><a href="'.$url.'&page='.($page-1).'">&lt;&lt;&nbsp;Prev</a>&nbsp;</td>';
		$pagerange=pagerange;

		if($pagecount > 1){
			if ($pagecount % $pagerange != 0){
				$rangecount = intval($pagecount / $pagerange) + 1;
			}else{
				$rangecount = intval($pagecount / $pagerange);
			};
			$cutpage=1;
			for($i=1;$i<$rangecount+1;$i++){
				$startpage=(($i-1)*$pagerange)+1;
				$count=min($i*$pagerange,$pagecount);
				if((($page>=$startpage)&&($page<=($i*$pagerange)))){
					for($j=$startpage;$j<$count+1;$j++){
						if($j==$page){
							$gentable.= '<td><b>'.$j.'</b></td>';
						}else{
							$gentable.= '<td><a href="'.$url.'&page='.$j.'">'.$j.'</a></td>';
							$cutpage++;
						};
					};
				}else{
					$gentable.= '<td><a href="'.$url.'&page='.$startpage.'">'.$startpage .'-' .$count.'</a></td>';
					if(($cutpage%$maxcol)==0 && $cutpage!=1)
					{
						$gentable.='</tr><tr>';
					}
				};
				$cutpage++;
			};
		};
		if($page<$pagecount){
			$gentable.= '<td>&nbsp;<a href="'.$url.'&page='.($page+1).'">Next&nbsp;&gt;&gt;</a>&nbsp;</td>';
		}
		$gentable.= '</tr></table>';

		return $gentable;
	}
	/////////////////////// Check Duplicate //////////////////////////
	function checkDup($table,$data)
	{
		$str="select * from " . $table . " where ";
		foreach($data as $col=>$key)
		{
				//$str .= $col . "='" . $key . "' and ";
				$str .= $col . "='" . $key . "'";
		}
		//$str.="retire='0'";
		//echo $str;
		$result=mysql_query($str);
		if (empty($result))
			return(0);
		$num=mysql_fetch_array($result);
		return($num[0]);
	};
	//////////////////////////// Alert Dup ////////////////////////////
	function alertDup($str)
	{
		echo '<script language="javascript">alert("' . $str . '")</script>';
	};
	//////////////////////////// AddList //////////////////////////////
	function addList($table,$field)
	{
		$tmp=array();
		$tmp=explode(",",$field);
		$str="select " . $field . " from " . $table . " where retire='0' order by ".$tmp[0]." asc";
		$result=mysql_query($str);
		$data=array();
		while($a=mysql_fetch_array($result))
		{
			$t=array_push($data,$a[0]);
		};
		return($data);
	};
	function addList2($table,$field)
	{
		$tmp=array();
		$tmp=explode(",",$field);
		$str="select " . $field . " from " . $table . " order by ".$tmp[0]." asc";
		$result=mysql_query($str);
		$data=array();
		while($a=mysql_fetch_array($result))
		{
			$t=array_push($data,$a);
		};
		return($data);
	};
	function addList3($str)
	{
		$result=mysql_query($str);
		$data=array();
		while($a=mysql_fetch_array($result))
		{
			$t=array_push($data,$a);
		};
		return($data);
	};
	/////////////////////////// AddSearch ///////////////////////////
	function addSearch($table)
	{
		$str="select * from " . $table . " where retire='0'";
		//echo $str;
		$result=mysql_query($str);
		$numField=mysql_num_fields($result);
		//$sqlCommand="update ".$table . " set ";
		$colName=getColName($table);
		$data=array();
		for($i=1;$i<$numField-5;$i++)
		{
			$tmp=array(mysql_field_name($result,$i)=>$colName[$i]);
			$data=array_merge($data,$tmp);
		}
		foreach($data as $col=>$key)
		{
			echo '<option value="' . $col . '">' . $key . '</option>';
		};
	};

	function addSearch2($table,$name)
	{
		$str="select * from " . $table;
		//alertDup($str);
		$result=mysql_query($str);
		$numField=mysql_num_fields($result);
		switch($table){
			case 'tb_thaiship_measure':
				$numField-=1;
				break;
			case 'tb_thaiship_builder':
				$numField-=1;
				break;
		}
		$fieldname=array();
		$fieldname=explode(',',$name);
		$colName=getColName($table);
		$data=array();
		for($i=1;$i<$numField;$i++)
		{
			$tmp=array($fieldname[$i]=>$colName[$i]);
			$data=array_merge($data,$tmp);
		}
		foreach($data as $col=>$key)
		{
			echo '<option value="' . $col . '">' . $key . '</option>';
		};
	};
	/////////////////////////// Upload //////////////////////////////
	function findexts ($filename)
	{
	$filename = strtolower($filename) ;
	$exts = split("[/\\.]", $filename) ;
	$n = count($exts)-1;
	$exts = $exts[$n];
	return $exts;
	};
	function uploadfile ($files,$filename,$folder,$hfiles)
	{
		if(($_FILES[$files]['name']!='')||($_FILES[$files]['name']=='\'\''))
		{
			if((findexts($_FILES[$files]['name'])=='jpg')&&($_FILES[$files]['size']<=512000))
			{
				strrpos($_POST[$files],'/');
				//$_FILES['m_pix']['name']=$_POST[m_name].'.'.findexts ($_FILES['m_pix']['name']) ;
				$_FILES[$files]['name']=$filename.'.'.findexts($_FILES[$files]['name']);
				$uploadfolder .= $_SERVER["DOCUMENT_ROOT"]."/pcd/images/".$folder."/".$_FILES[$files]['name']; // เนเธเธฅเน€เธ”เธญเธฃเนเน€เธญเธฒเนเธงเนเน€เธเนเธเนเธเธฅเน เน€เธเนเธ upload/
				//echo $uploadfolder;
				copy($_FILES[$files]['tmp_name'],$uploadfolder);

				return "images/".$folder."/".$_FILES[$files]['name'];
			}else{
			};
		}else{
			return $_POST[$hfiles];
		};
	};
	//////////////////////// Compare Date //////////////////////////
	function compareDate($date1,$date2)
	{
		return (str_replace("-","",$date1)-str_replace("-","",$date2));
	}
	//////////////////////// Convert Month /////////////////////////
	function convmonth($data)
	{
		switch($data)
		{
			case '01': $tmp='เธกเธเธฃเธฒเธเธก';
					break;
			case '02': $tmp='เธเธธเธกเธ เธฒเธเธฑเธเธเน';
					break;
			case '03': $tmp='เธกเธตเธเธฒเธเธก';
					break;
			case '04': $tmp='เน€เธกเธฉเธฒเธขเธ';
					break;
			case '05': $tmp='เธเธคเธฉเธ เธฒเธเธก';
					break;
			case '06': $tmp='เธกเธดเธ–เธธเธเธฒเธขเธ';
					break;
			case '07': $tmp='เธเธฃเธเธเธฒเธเธก';
					break;
			case '08': $tmp='เธชเธดเธเธซเธฒเธเธก';
					break;
			case '09': $tmp='เธเธฑเธเธขเธฒเธขเธ';
					break;
			case '10': $tmp='เธ•เธธเธฅเธฒเธเธก';
					break;
			case '11': $tmp='เธเธคเธฉเธเธดเธเธฒเธขเธ';
					break;
			case '12': $tmp='เธเธฑเธเธงเธฒเธเธก';
					break;
		};
		return ($tmp);
	}
	//////////////////////////// Month /////////////////////////////
	function callDate($name,$data,$event)
	{
		echo '<select name="'.$name.'" id="'.$name.'" '.$event.'>';
		for($i=1;$i<=31;$i++)
		{
			$date=(strlen($i)==1)?'0'.$i:$i;
			$select=($date==$data)?'selected="selected"':'';
			if(strlen($i)==1){
				echo '<option value="0'.$i.'" '.$select.'>0'.$i.'</option>';
			}else{
				echo '<option value="'.$i.'" '.$select.'>'.$i.'</option>';
			};
		};
		echo '</select>';
	}
	function callMonth($name,$data,$event)
	{
		echo '<select name="'.$name.'" id="'.$name.'" '.$event.'>';
		$select=($data=='01')?'selected="selected"':'';
		echo '<option value="01" '.$select.'>เธกเธเธฃเธฒเธเธก</option>';
		$select=($data=='02')?'selected="selected"':'';
		echo '<option value="02" '.$select.'>เธเธธเธกเธ เธฒเธเธฑเธเธเน</option>';
		$select=($data=='03')?'selected="selected"':'';
		echo '<option value="03" '.$select.'>เธกเธตเธเธฒเธเธก</option>';
		$select=($data=='04')?'selected="selected"':'';
		echo '<option value="04" '.$select.'>เน€เธกเธฉเธฒเธขเธ</option>';
		$select=($data=='05')?'selected="selected"':'';
		echo '<option value="05" '.$select.'>เธเธคเธฉเธ เธฒเธเธก</option>';
		$select=($data=='06')?'selected="selected"':'';
		echo '<option value="06" '.$select.'>เธกเธดเธ–เธธเธเธฒเธขเธ</option>';
		$select=($data=='07')?'selected="selected"':'';
		echo '<option value="07" '.$select.'>เธเธฃเธเธเธฒเธเธก</option>';
		$select=($data=='08')?'selected="selected"':'';
		echo '<option value="08" '.$select.'>เธชเธดเธเธซเธฒเธเธก</option>';
		$select=($data=='09')?'selected="selected"':'';
		echo '<option value="09" '.$select.'>เธเธฑเธเธขเธฒเธขเธ</option>';
		$select=($data=='10')?'selected="selected"':'';
		echo '<option value="10" '.$select.'>เธ•เธธเธฅเธฒเธเธก</option>';
		$select=($data=='11')?'selected="selected"':'';
		echo '<option value="11" '.$select.'>เธเธคเธฉเธเธดเธเธฒเธขเธ</option>';
		$select=($data=='12')?'selected="selected"':'';
		echo '<option value="12" '.$select.'>เธเธฑเธเธงเธฒเธเธก</option>';
		echo '</select>';
	}

	function callYear($name,$start,$end,$data,$event)
	{
		echo '<select name="'.$name.'" id="'.$name.'" '.$event.'>';
		for($i=$start;$i<=$end;$i++)
		{
			$select=($i==$data)?'selected="selected"':'';
			echo '<option value="'.$i.'" '.$select.'>'.$i.'</option>';
		}
		echo '</select>';
	}

	function callYearDown($name,$start,$end,$data,$event)
	{
		echo '<select name="'.$name.'" id="'.$name.'" '.$event.'>';
		for($i=$end;$i>=$start;$i--)
		{
			$select=($i==$data)?'selected="selected"':'';
			echo '<option value="'.$i.'" '.$select.'>'.$i.'</option>';
		}
		echo '</select>';
	}

	function numDay($month,$year)
	{
		switch($month)
		{
			case '01': $tmp=31;
					break;
			case '02':
					if(($year%4)==0)
						$tmp=29;
					else
						$tmp=28;
					break;
			case '03': $tmp=31;
					break;
			case '04': $tmp=30;
					break;
			case '05': $tmp=31;
					break;
			case '06': $tmp=30;
					break;
			case '07': $tmp=31;
					break;
			case '08': $tmp=31;
					break;
			case '09': $tmp=30;
					break;
			case '10': $tmp=31;
					break;
			case '11': $tmp=30;
					break;
			case '12': $tmp=31;
					break;
		};
		return ($tmp);

	}

	function DateDiff($strDate1,$strDate2)
	{
		return (strtotime($strDate2) - strtotime($strDate1))/  ( 60 * 60 * 24 );  // 1 day = 60*60*24
	}
	function TimeDiff($strTime1,$strTime2)
	{
		return (strtotime($strTime2) - strtotime($strTime1))/  ( 60 * 60 ); // 1 Hour =  60*60
	}
	function DateTimeDiff($strDateTime1,$strDateTime2)
	{
		return (strtotime($strDateTime2) - strtotime($strDateTime1))/  ( 60 * 60 ); // 1 Hour =  60*60
	}

	//echo "Date Diff = ".DateDiff("2008-08-01","2008-08-31")."<br>";
	//echo "Time Diff = ".TimeDiff("00:00","19:00")."<br>";
	//echo "Date Time Diff = ".DateTimeDiff("2008-08-01 00:00","2008-08-01 19:00")."<br>";

	////////////////////// Get Column Name /////////////////////////
	function getColName($table)
	{
		switch ($table)
		{
			case "tb_sattelite":
				$data=array("ลำดับที่","ลุ่มน้ำย่อย","วันที่ เวลา","ปริมาณฝน (มม.)");
				return($data);
			case "tb_radarpimaia":
				$data=array("ลำดับที่","สถานีวัดน้ำฝน","วันที่ เวลา","ปริมาณฝน (มม.)");
				return($data);
			case "tb_radarpimait":
				$data=array("ลำดับที่","สถานีวัดน้ำฝน","วันที่ เวลา","ปริมาณฝน (มม.)");
				return($data);
			case "tb_radarsurina":
				$data=array("ลำดับที่","สถานีวัดน้ำฝน","วันที่ เวลา","ปริมาณฝน (มม.)");
				return($data);
			case "tb_radarsurint":
				$data=array("ลำดับที่","สถานีวัดน้ำฝน","วันที่ เวลา","ปริมาณฝน (มม.)");
				return($data);
			case "tb_telemetry":
				$data=array("ลำดับที่","วันที่ เวลา","ระดับน้ำ (ม.รทก.)","ปริมาณการไหลของน้ำ (ลบ.ม./วินาที)");
				return($data);
			case "tb_news":
				$data=array("ลำดับที่","วันที่ เวลา","หัวข้อข่าว");
				return($data);
		};
	};


//////////////////////////////////////////////////////////////////

function insertChat($input,$userid,$username)
{
			$sqlCommand = "INSERT INTO unknown_inputs (input, userid, username) VALUES ('" . $input . "', '". $userid ."', '". $username ."')";


//echo $sqlCommand . "</BR>";

			$result=mysql_query($sqlCommand);

			if (!$result) {
				$message  = 'Invalid query: ' . mysql_error() . "\n";
				$message .= 'Whole query: ' . $sqlCommand;
				die($message);
				return false;
			}else{
				return true;
			}

}

function showDataAIML()
{
	 $str = "SELECT pattern,template FROM aiml WHERE 1 LIMIT 10";

	 $result=mysql_query($str);
	 $data=mysql_num_fields($result);
	 $allrows=mysql_num_rows($result);


	 echo "total : " . $allrows;


	 for($i=0;$i<$allrows;$i++)
	 {
			 $data_row=mysql_fetch_array($result);

		 echo "Pattern = " . $data_row["pattern"] . " template : " . $data_row["template"]. "</BR>";

	 }


}

?>
