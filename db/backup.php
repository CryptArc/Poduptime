<?php
include('config.php');
$keep = (60 * 60 * 6) * 1; 
$dump_date = date("Ymd_Hs");
$file_name = $backup_dir . "/dump_" . $dump_date . ".sql";
system("export PGPASSWORD=$pgpass && $pg_dump_dir/pg_dump --username=$pguser $pgdb >> $file_name");
echo "pg backup of $pgdb made";
$dirh = dir($backup_dir);
while($entry = $dirh->read()) {
$old_file_time = (date("U") - $keep);
$file_created = filectime("$backup_dir/$entry");
if ($file_created < $old_file_time && !is_dir($entry)) {
if(unlink("$backup_dir/$entry")) {
echo "Cleaned up old backups";
}
}
}
?>
