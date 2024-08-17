

<DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>قائمة الملفات</title>
    <style>
body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #141414;
            direction: rtl;
            text-align: right;
            padding: 20px;
            color: #fff;
        }
        h1, h2 {
            color: #fff;
            margin-bottom: 20px;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            display: flex;
            align-items: center;
            margin: 5px 0;
            background: #1f1f1f;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        a {
            text-decoration: none;
            color: #fff;
            font-size: 18px;
            margin-right: 10px;
            flex-grow: 1;
        }
        a:hover {
            text-decoration: underline;
        }
        form {
            margin-top: 20px;
        }
        input[type="text"], textarea {
            width: 100%;
            max-width: 100%;
            min-width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-family: 'Courier New', Courier, monospace;
            box-sizing: border-box;
            margin-bottom: 20px;
            background-color: #1f1f1f;
            color: #fff;
        }
        textarea {
            height: 300px;
        }
        .icon-button, .add-button {
            background-color: transparent;
            border: none;
            cursor: pointer;
            font-size: 24px;
            color: #fff;
            margin: 0 10px;
            transition: color 0.3s, transform 0.3s;
        }

.icon-button:hover, .add-button:hover {
            color: #b9090b;
            transform: scale(1.1);
        }
        .message {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #1f1f1f;
            color: #4CAF50;
        }
        .form-container {
            background-color: #1f1f1f;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .file-actions {
            margin-right: auto;
        }
        .header-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .header-icons {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .header-icons button {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px;
            background-color: #282828;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            transition: background-color 0.3s;
        }
        .header-icons button:hover {
            background-color: #444;
        }
        /* Your existing CSS */
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function(){
            $('a[href="https://www.000webhost.com/?utm_source=000webhostapp&utm_campaign=000_logo&utm_medium=website&utm_content=footer_img"]').closest('footer').remove();
        });

        function toggleVisibility(id) {
            var element = document.getElementById(id);
            if (element.style.display === 'none' || element.style.display === '') {
                element.style.display = 'block';
            } else {
                element.style.display = 'none';
            }
        }

        function toggleFileList() {
            toggleVisibility('fileList');
        }

        function showCreateForm() {
            toggleVisibility('createForm');
        }

        function showCreateFolderForm() {
            toggleVisibility('createFolderForm');
        }

        function showRenameForm(filename) {
            document.getElementById('renameFormContainer').style.display = 'block';
            document.getElementById('oldFilename').value = filename;
            document.getElementById('newFilename').value = filename;
        }

        function showUploadForm() {
            toggleVisibility('uploadFormContainer');
        }

        function showLinkUploadForm() {
            toggleVisibility('linkUploadFormContainer');
        }

        function showUnzipForm() {
            toggleVisibility('unzipFormContainer');
        }

        function deleteFile(path) {
            if (confirm('هل أنت متأكد أنك تريد حذف هذا الملف؟')) {
                $.post('delete.php', { path: path }, function(response) {
                    alert(response);
                    location.reload(); // Reload the page after deletion
                }).fail(function() {
                    alert('حدث خطأ أثناء محاولة الحذف.');
                });
            }
        }
    </script>
</head>
<body>
    <div class="header-actions">
        <h1></h1>
        <div class="header-icons">
            <button class="icon-button" onclick="toggleFileList()"><i class="fas fa-cog"></i></button>
            <button class="add-button" onclick="showCreateForm()"><i class="fas fa-plus-circle"></i></button>
            <button class="add-button" onclick="showCreateFolderForm()"><i class="fas fa-folder-plus"></i></button>
            <button class="add-button" onclick="showUploadForm()"><i class="fas fa-upload"></i></button>
            <button class="add-button" onclick="showLinkUploadForm()"><i class="fas fa-link"></i></button>
            <button class="add-button" onclick="showUnzipForm()"><i class="fas fa-file-archive"></i></button>
        </div>
    </div>
    <ul id="fileList" style="display: none;">
        <?php
        $dir = isset($_GET['dir']) ? $_GET['dir'] : '.';
        if ($handle = opendir($dir)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    $path = "$dir/$entry";
                    if (is_dir($path)) {
                        echo "<li><a href=\"?dir=$path\"><i class='fas fa-folder'></i> $entry</a></li>";
                    } else {
                        echo "<li><a href=\"?file=$path\"><i class='fas fa-file-alt'></i> $entry</a>";
                        echo "<div class='file-actions'>";
                        echo "<button class='icon-button' onclick=\"showRenameForm('$path')\"><i class='fas fa-edit'></i></button>";
                        echo "<button class='icon-button' onclick=\"deleteFile('$path')\"><i class='fas fa-trash-alt'></i> </button>";
                        echo "</div></li>";
                    }
                }
            }
            closedir($handle);
        }
        ?>
    </ul>

    <div id="createForm" class="form-container" style="display:none;">
        <h2>إنشاء ملف جديد</h2>
        <form method='post'>
            <input type='hidden' name='dir' value='<?php echo $dir; ?>'>
            <input type='text' name='newfile' placeholder='اسم الملف' required><br>
            <textarea name='content' placeholder='اكتب هنا...' required></textarea><br>
            <button type='submit' class='icon-button'><i class='fas fa-save'></i> </button>
        </form>
    </div>

    <div id="createFolderForm" class="form-container" style="display:none;">
        <h2>إنشاء مجلد جديد</h2>
        <form method='post'>
            <input type='hidden' name='dir' value='<?php echo $dir; ?>'>
            <input type='text' name='newfolder' placeholder='اسم المجلد' required><br>
            <button type='submit' class='icon-button'><i class='fas fa-save'></i> </button>
        </form>
    </div>

    <div id="renameFormContainer" class="form-container" style="display:none;">
        <h2>تغيير اسم الملف</h2>
        <form method='post'>
            <input type='hidden' name='dir' value='<?php echo $dir; ?>'>
            <input type='hidden' name='oldFilename' id='oldFilename' required>
            <input type='text' name='newFilename' id='newFilename' required><br>
            <button type='submit' name='renameFile' class='icon-button'><i class='fas fa-save'></i> </button>
        </form>
    </div>

    <?php
    if (isset($_GET['file'])) {
        $file = $_GET['file'];
        if (file_exists($file)) {
            // عرض محتوى الملف
            $content = file_get_contents($file);
            echo "<div class='form-container'><h2>عرض وتعديل الملف: $file</h2>";
            echo "<form method='post'>";
            echo "<textarea name='content'>" . htmlspecialchars($content) . "</textarea><br>";
            echo "<input type='hidden' name='file' value='$file'>";
            echo "<button type='submit' class='icon-button'><i class='fas fa-save'></i> </button>";
            echo "</form></div>";
        }
    }

    if (isset($_POST['file']) && isset($_POST['content'])) {
        $file = $_POST['file'];
        $content = $_POST['content'];
        file_put_contents($file, $content);
        echo "<div class='message'>تم حفظ التعديلات على الملف: $file</div>";
    }

    if (isset($_POST['newfile']) && isset($_POST['content'])) {
        $newfile = $_POST['dir'] . '/' . $_POST['newfile'];
        if (!file_exists($newfile)) {
            $content = $_POST['content'];
            file_put_contents($newfile, $content);
            echo "<div class='message'>تم إنشاء الملف الجديد: $newfile</div>";
        } else {
            echo "<div class='message'>الملف $newfile موجود بالفعل.</div>";
        }
    }

    if (isset($_POST['newfolder'])) {
        $newfolder = $_POST['dir'] . '/' . $_POST['newfolder'];
        if (!file_exists($newfolder)) {
            mkdir($newfolder);
            echo "<div class='message'>تم إنشاء المجلد الجديد: $newfolder</div>";
        } else {
            echo "<div class='message'>المجلد $newfolder موجود بالفعل.</div>";
        }
    }

    if (isset($_POST['renameFile'])) {
        $oldFilename = $_POST['oldFilename'];
        $newFilename = $_POST['dir'] . '/' . $_POST['newFilename'];
        if (!file_exists($newFilename)) {
            rename($oldFilename, $newFilename);
            echo "<div class='message'>تم تغيير اسم الملف من $oldFilename إلى $newFilename</div>";
        } else {
            echo "<div class='message'>الملف $newFilename موجود بالفعل.</div>";
        }
    }
    ?>

    <div id="uploadFormContainer" class="form-container" style="display:none;">
        <h2>تحميل الملفات</h2>
        <form method='post' enctype='multipart/form-data'>
            <input type='hidden' name='dir' value='<?php echo $dir; ?>'>
            <input type='file' name='fileToUpload' id='fileToUpload' required><br>
            <button type='submit' name='uploadFile' class='icon-button'><i class='fas fa-upload'></i> </button>
        </form>
    </div>

    <div id="linkUploadFormContainer" class="form-container" style="display:none;">
        <h2>تحميل من رابط</h2>
        <form method='post'>
            <input type='hidden' name='dir' value='<?php echo $dir; ?>'>
            <input type='text' name='fileLink' placeholder='أدخل الرابط' required><br>
            <button type='submit' name='uploadLink' class='icon-button'><i class='fas fa-upload'></i> </button>
        </form>
    </div>

    <?php
    if (isset($_FILES['fileToUpload'])) {
        $targetDir = $_POST['dir'] . '/';
        $targetFile = $targetDir . basename($_FILES['fileToUpload']['name']);
        if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $targetFile)) {
            echo "<div class='message'>تم تحميل الملف: $targetFile</div>";
        } else {
            echo "<div class='message'>عذرًا، حدث خطأ أثناء تحميل الملف.</div>";
        }
    }

    if (isset($_POST['fileLink'])) {
        $fileLink = $_POST['fileLink'];
        $targetDir = $_POST['dir'] . '/';
        $targetFile = $targetDir . basename($fileLink);
        if (file_put_contents($targetFile, file_get_contents($fileLink))) {
            echo "<div class='message'>تم تحميل الملف من الرابط: $targetFile</div>";
        } else {
            echo "<div class='message'>عذرًا، حدث خطأ أثناء تحميل الملف من الرابط.</div>";
        }
    }
    ?>

    <div id="unzipFormContainer" class="form-container" style="display:none;">
        <h2>استخراج الملفات من أرشيف ZIP</h2>
        <form method='post' enctype='multipart/form-data'>
            <input type='hidden' name='dir' value='<?php echo $dir; ?>'>
            <input type='file' name='zipFileToUpload' id='zipFileToUpload' required><br>
            <button type='submit' name='unzipFile' class='icon-button'><i class='fas fa-file-archive'></i> </button>
        </form>
    </div>

    <?php
    if (isset($_FILES['zipFileToUpload'])) {
        $zipFilePath = $_FILES['zipFileToUpload']['tmp_name'];
        $zip = new ZipArchive;
        if ($zip->open($zipFilePath) === TRUE) {
            $zip->extractTo($_POST['dir']);
            $zip->close();
            echo "<div class='message'>تم استخراج الأرشيف بنجاح إلى {$_POST['dir']}</div>";
        } else {
            echo "<div class='message'>عذرًا، حدث خطأ أثناء استخراج الأرشيف.</div>";
        }
    }
    ?>
</body>
</html>
