<?php
include_once __DIR__ . '/init.php';

$ignored_files = array();
$ignored_files[] = 'node_modules/*';
$ignored_files[] = 'vendor/*';
$ignored_files[] = '.git/*';

if (($_ENV['S3_BKUP_ACCESS_KEY'] ?? false) && ($_ENV['S3_BKUP_BUCKET_NAME'] ?? false) && ($_ENV['S3_BKUP_HOST_BUCKET'] ?? false) && ($_ENV['S3_BKUP_SECRET_KEY'] ?? false) && ($_ENV['S3_BKUP_HOST_BASE'] ?? false) && defined('ABSOLUTE_PATH')) {

	linux_command('s3cmd sync -r --delete-removed --exclude "' . implode('" --exclude "', $ignored_files) . '" --host="' . $_ENV['S3_BKUP_HOST_BASE'] . '" --access_key="' . $_ENV['S3_BKUP_ACCESS_KEY'] . '" --secret_key="' . $_ENV['S3_BKUP_SECRET_KEY'] . '" --host-bucket="' . $_ENV['S3_BKUP_HOST_BUCKET'] . '" ' . ABSOLUTE_PATH . '/ s3://' . $_ENV['S3_BKUP_BUCKET_NAME']);

	if (($_ENV['DB_NAME'] ?? false) && ($_ENV['DB_USER'] ?? false) && ($_ENV['DB_PASS'] ?? false)) {
		$backupfile = '/tmp/backup-' . $_ENV['DB_NAME'] . '.sql.gz';
		linux_command('mysqldump --no-tablespaces -u' . $_ENV['DB_USER'] . ' -p' . $_ENV['DB_PASS'] . ' ' . $_ENV['DB_NAME'] . ' | gzip > ' . $backupfile);
		linux_command('s3cmd --host="' . $_ENV['S3_BKUP_HOST_BASE'] . '" --access_key="' . $_ENV['S3_BKUP_ACCESS_KEY'] . '" --secret_key="' . $_ENV['S3_BKUP_SECRET_KEY'] . '" --host-bucket="' . $_ENV['S3_BKUP_HOST_BUCKET'] . '" put ' . $backupfile . ' s3://' . $_ENV['S3_BKUP_BUCKET_NAME'] . '/');
		linux_command('rm ' . $backupfile);
	}

	$dash->push_content(array('type' => $type, 'ignored_files' => json_encode($ignored_files)));

	echo '<h3>Backup successfully initiated. You can close this window.</h3>';
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