
<!-- ******************** ADD THIS TO CRON JOB ****************** -->

<!-- php /home/username/public_html/onlineimageresizer.net/imr/delete_old_files.php >> /home/username/logs/delete_old_files.log 2>&1 -->
 
<!-- ******************************************************************* -->


<?php
$folder_path = '/home/freehpwc/onlineimageresizer.net/imr/uploads'; // Full path to your folder

if (is_dir($folder_path)) {
    $files = array_diff(scandir($folder_path), ['.', '..']); // Exclude special directories '.' and '..'

    foreach ($files as $file) {
        $file_path = $folder_path . '/' . $file;

        // If it's a file, delete it
        if (is_file($file_path)) {
            unlink($file_path);
            echo "Deleted file: $file_path\n";
        }

        // If it's a directory, recursively delete its contents
        if (is_dir($file_path)) {
            delete_folder($file_path);
        }
    }

    echo "Folder emptied successfully.";
} else {
    echo "The specified path is not a valid directory.";
}

// Recursive function to delete a folder and its contents
function delete_folder($folder) {
    $files = array_diff(scandir($folder), ['.', '..']);

    foreach ($files as $file) {
        $file_path = $folder . '/' . $file;

        if (is_file($file_path)) {
            unlink($file_path);
            echo "Deleted file: $file_path\n";
        } elseif (is_dir($file_path)) {
            delete_folder($file_path);
        }
    }

    // Remove the folder itself
    rmdir($folder);
    echo "Deleted folder: $folder\n";
}
?>
