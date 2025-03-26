<?php
$backupFile = 'backup-' . date('Y-m-d') . '.sql';
$command = "mysqldump -u root -p pendaftaran_siswa > $backupFile";
system($command);
echo "Backup berhasil! File: $backupFile";
?>