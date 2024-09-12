<?php

$environment = 'developer';

require_once 'S3/vendor/autoload.php';

if ($environment === 'developer') {
    define('VIDEOCALL_S3_BUCKET', 'some bucket');
    define('VIDEOCALL_S3_REGION', 'ap-southeast-1');
    define('VIDEOCALL_S3_KEY', 'some key');
    define('VIDEOCALL_S3_SECRET', 'some secret');
} elseif ($environment === 'production') {
    define('VIDEOCALL_S3_BUCKET', 'some bucket');
    define('VIDEOCALL_S3_REGION', 'ap-southeast-1');
    define('VIDEOCALL_S3_KEY', 'some key');
    define('VIDEOCALL_S3_SECRET', 'some secret');
}

try {
	print_r($environment);
	echo "\n";

	$s3_video_call = [
	    'bucket' => VIDEOCALL_S3_BUCKET,
	    'region' => VIDEOCALL_S3_REGION,
	    'version' => 'latest',
	    'credentials' => [
	        'key'    => VIDEOCALL_S3_KEY,
	        'secret' => VIDEOCALL_S3_SECRET,
	    ]
	];

	$s3 = new Aws\S3\S3Client($s3_video_call);

	$ymd = date('Ymd/', time());

	$starttime = microtime(true);
	$content = file_get_contents('https://worldfonepro-center-uat.worldfone.cloud/playback?secret=r1d2426551f37e6ec8df3fbdsqqwq213de076&callid=6634bf8fa8ac293db20fb22d');
	$endtime = microtime(true);

	print_r('Time get content from WorldfonePro: ' . ($endtime - $starttime));
	echo "\n";

	print_r('Start S3: ' . 'test/' . $ymd . 'demo' . '/test.mp4');
	echo "\n";

	$starttime = microtime(true);
	$aws_object = $s3->putObject([
	    'Bucket'        => VIDEOCALL_S3_BUCKET,
	    'Key'           => 'test/' . $ymd . 'demo' . '/test.mp4',
	    'Body'          => $content,
	]);
	$endtime = microtime(true);

	print_r('Time putObject S3: ' . ($endtime - $starttime));
	echo "\n";

	$res = $aws_object->toArray();

	print_r($res);

} catch (AwsException $e) {
    // If an error occurs, retrieve and print the error response
    echo "Error: " . $e->getAwsErrorMessage() . PHP_EOL;
}

//or push all files in a folder

// foreach($dirPath as $key => $path){
// 	$files = scandir($path);  
// 	foreach ($files as $file) {
// 		$filePath = $path . '/' . $file;
// 		// print_r(file_get_contents($filePath));
// 		$file_parts = pathinfo($filePath)['extension'];
// 		if($file_parts == 'mp3'){
// 			$s3_path = $dateFolder.'/'.$key;
// 			// $bucket = 'iba-support-nonprod-occ-ap-southeast-1-654654209111/'.$s3_path;
// 			$bucket = S3_BUCKET;
// 			$aws_object = $s3->putObject([
// 				'Bucket'        => $bucket,
// 				'Key'           => str_replace("%","", $file),
// 				'Body'          => file_get_contents($filePath),
// 			]);
// 			$endtime = microtime(true);
// 			print_r('Time putObject S3: ' . ($endtime - $starttime));
// 			echo "\n";

// 			$res = $aws_object->toArray();

// 			print_r($res);
// 		}
// 	}
// }
