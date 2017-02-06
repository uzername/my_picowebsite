<?php
error_log(__FILE__.date(' Y-m-d H:m:s')." : ". "post query received" ."\n", 3, __DIR__."/"."debug.log");
error_log(__FILE__.date(' Y-m-d H:m:s')." : ". print_r($POST) ."\n", 3, __DIR__."/"."debug.log");
?>