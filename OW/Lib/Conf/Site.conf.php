<?php
	return [
		"Parameter"	=>	[
			"model"		=>	"m",
			"Controll" 	=>	"c",
			"Method"	=>	"a"
		],
		"Session"	=>	"start",//session开启为start，关闭为stop
		"Debug"		=>	true,
		"Time_setting"	=>	"PRC",//时区设置，亚洲
		"Url_mode"	=>	"s",//设置url访问模式，pathinfo模式
		"Site_Path"	=>	[
			"FRAMEWORK_FUN"		=>	FRAMEWORK_ROOT.'/Lib/Functions',
			"FRAMEWORK_CORE"	=>	FRAMEWORK_ROOT.'/Lib/Core',
			"FRAMEWORK_CONF"	=>	FRAMEWORK_ROOT.'/Lib/Conf',
			"FRAMEWORK_CACHE"	=>	ROOT_PATH.'/Cache',
			"FRAMEWORK_CACHE_TEMPLET"	=>	ROOT_PATH.'/Cache/Templet',
			"FRAMEWORK_TEMPLET"	=>	ROOT_PATH.'/Templet',
			"FRAMEWORK_LOG"		=>	ROOT_PATH.'/Cache/log/php.log'
		]
	];
?>