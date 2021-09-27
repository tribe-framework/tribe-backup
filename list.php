<?php
include_once __DIR__ . '/init.php';

if (($_ENV['S3_BKUP_ACCESS_KEY'] ?? false) && ($_ENV['S3_BKUP_BUCKET_NAME'] ?? false) && ($_ENV['S3_BKUP_HOST_BUCKET'] ?? false) && ($_ENV['S3_BKUP_SECRET_KEY'] ?? false) && ($_ENV['S3_BKUP_HOST_BASE'] ?? false) && defined('ABSOLUTE_PATH') && ($_ENV['DB_NAME'] ?? false) && ($_ENV['DB_USER'] ?? false) && ($_ENV['DB_PASS'] ?? false)) {

	echo linux_command('s3cmd ls s3://' . $_ENV['S3_BKUP_BUCKET_NAME'] . ' --host="' . $_ENV['S3_BKUP_HOST_BASE'] . '" --access_key="' . $_ENV['S3_BKUP_ACCESS_KEY'] . '" --secret_key="' . $_ENV['S3_BKUP_SECRET_KEY'] . '" --host-bucket="' . $_ENV['S3_BKUP_HOST_BUCKET'] . '";');

} else {
	echo '<h3>S3 credentials have not been added to the dot-env file. Contact your development team.</h3>';
}

function linux_command($cmd) {
	ob_start();
	passthru($cmd . ' > /dev/null 2>&1 &');
	$tml = ob_get_contents();
	ob_end_clean();
	return $tml;
}
?>