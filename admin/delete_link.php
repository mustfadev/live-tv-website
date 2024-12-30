<?php
include_once('includes/config.php');
include_once('includes/session_check.php');

// التحقق من وجود معرف الرابط في الرابط
if (isset($_GET['link_id'])) {
    $link_id = $_GET['link_id'];

    // إجراء استعلام للتحقق من الرابط قبل الحذف
    $stmt_check_link = $connect->prepare("SELECT * FROM tbl_channel_links WHERE link_id = ?");
    
    if ($stmt_check_link === false) {
        die("Error preparing the query: " . $connect->error);
    }

    $stmt_check_link->bind_param("i", $link_id);
    $stmt_check_link->execute();
    $link_result = $stmt_check_link->get_result();

    if ($link_result->num_rows > 0) {
        // الرابط موجود في قاعدة البيانات، قم بالحذف
        $stmt_delete = $connect->prepare("DELETE FROM tbl_channel_links WHERE link_id = ?");
        
        if ($stmt_delete === false) {
            die("Error preparing delete query: " . $connect->error);
        }

        $stmt_delete->bind_param("i", $link_id);
        
        if ($stmt_delete->execute()) {
            $channel_id = $link_result->fetch_assoc()['channel_id']; // الحصول على ID القناة
            header("Location: edit_links.php?id=$channel_id"); // إعادة تحميل الصفحة بعد حذف الرابط
            exit;
        } else {
            die("Error deleting the link.");
        }
    } else {
        die("Link not found.");
    }
} else {
    die("Invalid request.");
}
?>
