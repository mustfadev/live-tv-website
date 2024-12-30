<?php
include_once('includes/config.php');
include_once('includes/session_check.php');

// التحقق من وجود معرف القناة في الرابط
if (isset($_GET['id'])) {
    $channel_id = $_GET['id'];

    // جلب معلومات القناة من قاعدة البيانات
    $stmt = $connect->prepare("SELECT * FROM tbl_channel WHERE id = ?");
    $stmt->bind_param("i", $channel_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $channel = $result->fetch_assoc();
    } else {
        die("Channel not found.");
    }

    // جلب الروابط الخاصة بالقناة
    $stmt_links = $connect->prepare("SELECT * FROM tbl_channel_links WHERE channel_id = ?");
    $stmt_links->bind_param("i", $channel_id);
    $stmt_links->execute();
    $links_result = $stmt_links->get_result();
}

// إضافة رابط جديد
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_link'])) {
    $link_name = $_POST['link_name'];
    $link_url = $_POST['link_url'];

    // إضافة رابط جديد للقناة
    $stmt_add_link = $connect->prepare("INSERT INTO tbl_channel_links (channel_id, link_name, link_url) VALUES (?, ?, ?)");
    $stmt_add_link->bind_param("iss", $channel_id, $link_name, $link_url);
    
    if ($stmt_add_link->execute()) {
        header("Location: edit_links.php?id=$channel_id"); // إعادة تحميل الصفحة بعد إضافة الرابط
        exit;
    } else {
        die("Error adding the link.");
    }
}



?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Links for Channel: <?php echo $channel['channel_name']; ?></title>
    <link rel="stylesheet" href="assets/css/styles.min.css" />
</head>

<body>
    <div class="container">
        <h1>Edit Links for Channel: <?php echo $channel['channel_name']; ?></h1>

        <!-- Form to add new link -->
        <form method="POST" action="" class="mb-4">
            <div class="form-group">
                <label for="link_name">Link Name:</label>
                <input type="text" id="link_name" name="link_name" class="form-control" required />
            </div>
            <div class="form-group">
                <label for="link_url">Link URL:</label>
                <input type="url" id="link_url" name="link_url" class="form-control" required />
            </div>
            <button type="submit" name="add_link" class="btn btn-success">Add Link</button>
        </form>

        <h3>Existing Links:</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Link Name</th>
                    <th>Link URL</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($links_result->num_rows > 0): ?>
                    <?php while ($link = $links_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $link['link_name']; ?></td>
                            <td><a href="<?php echo $link['link_url']; ?>" target="_blank"><?php echo $link['link_url']; ?></a></td>
                            <td>

                                <!-- زر الحذف مع التأكيد -->
                                <a href="delete_link.php?link_id=<?php echo $link['link_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this link?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">No links available for this channel.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>

</html>

<?php $connect->close(); ?>