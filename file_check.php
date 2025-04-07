<?php
    include_once("page_restriction.php");

    $file_hash = '';
    $comparison_result = '';

    if($_SERVER['REQUEST_METHOD'] === 'POST') 
    {
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) 
        {
            $uploaded_file = $_FILES['file']['tmp_name'];
            if(file_exists($uploaded_file)) 
            {
                $file_hash = hash_file('sha256', $uploaded_file);
            } 
            else 
            {
                $comparison_result = "Uploaded file does not exist.";
            }
        } 
        else 
        {
            $comparison_result = "File upload error.";
        }
    }

    if (!empty($_POST['compare_hash'])) 
    {
        $compare_hash = trim($_POST['compare_hash']);
        if(!empty($file_hash)) 
        {
            if(hash_equals($file_hash, $compare_hash)) 
            {
                $comparison_result = "HASH MATCHES THE FILE";
            } 
            else 
            {
                $comparison_result = "File has been modified.";
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Hash Checker</title>
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <style>
        body { padding: 2rem; }
        .result { margin-top: 1rem; }
        .match { color: green; }
        .mismatch { color: red; }
    </style>
</head>
<body>
    <h1>File Hash Checker</h1>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="file" class="form-label">Select File:</label>
            <input type="file" name="file" id="file" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Generated Hash:</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($file_hash) ?>" placeholder="Upload file to generate hash" disabled>
        </div>

        <div class="mb-3">
            <label for="compare_hash" class="form-label">Compare With (Original Hash):</label>
            <input type="text" name="compare_hash" id="compare_hash" class="form-control" value="<?= htmlspecialchars($_POST['compare_hash'] ?? '') ?>" placeholder="Place original hash here">
        </div>

        <input type="submit" value="Verify" class="btn btn-primary">
    </form>

    <?php if ($comparison_result): ?>
    <div class="result <?= strpos($comparison_result, 'MATCHES') !== false ? 'match' : 'mismatch' ?>">
        <?= htmlspecialchars($comparison_result) ?>
    </div>
    <?php endif; ?>
</body>
</html>