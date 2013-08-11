<?php
	/**
	 * 获取mongodb的监控指标数据
	 *
	 **/
	
	$starttime = microtime(TRUE);
	
	$cmd = "echo 'db.adminCommand({serverStatus:1})'|mongo 192.168.213.129";
	$result = `$cmd`;
	$startPos = strpos($result, '{');
	$endPos = strrpos($result, '}');
	
	$jsonData = substr($result, $startPos, $endPos-$startPos+1);

	$jsonData = str_replace(array('NumberLong', '(', ')', 'ISODate'), '', $jsonData);
	
	$retData = json_decode($jsonData, true);
	
	echo microtime(TRUE) - $starttime;
	$starttime = microtime(TRUE);
	
	
	$mongoClient = new mongo('mongodb://192.168.213.129:27017', array('connect'=>true));
	$retData = $mongoClient->selectDb('admin')->command(array('serverStatus'=>1));

	$data['insert']	=	$retData['opcounters']['insert'];
	$data['query']	=	$retData['opcounters']['query'];
	$data['update']	=	$retData['opcounters']['update'];
	$data['delete']	=	$retData['opcounters']['delete'];
	$data['getmore']= 	$retData['opcounters']['getmore'];
	$data['command']= 	$retData['opcounters']['command'];
	
	$data['conn'] 	= 	$retData['connections']['current'];
	$data['netIn'] 	= 	$retData['network']['bytesIn'];
	$data['netOut'] 	= 	$retData['network']['bytesOut'];
	
	
	$data['res'] 	= 	$retData['mem']['resident'];
	$data['mapped']	= 	$retData['mem']['mapped'];
	$data['vsize']	= 	$retData['mem']['virtual'];
	
	var_dump($data);

	echo microtime(TRUE) - $starttime;
	
	exit();
	
	/*
	$headers = array(
			'insert', 'query' , 'update', 'delete', 'getmore',
			'command', 'flushes', 'mapped', 'vsize', 'res', 'faults', 'locked',
			'idxmiss', 'qr|qw', 'ar|aw', 'netIn', 'netOut', 'conn', 'set', 'repl', 'time'
	);
	
	$items = array('query', 'insert', 'update', 'delete', 'command', 'getmore', 'netIn', 'netOut', 'conn', 'vsize', 'faults', 'res', 'mapped');


	$cmd = "mongostat -h 192.168.213.129 --rowcount 1";
	$result = `$cmd`;
	
	$result = explode("\n", trim($result));

	$data = array_filter(explode(" ", $result[2]), 'filter_empty');

	$data = array_combine($headers, $data);

	$data = array_intersect_key($data, array_flip($items));
	
	var_dump($data);
	
	function filter_empty($value) {
		return $value !== '';
	}
	*/