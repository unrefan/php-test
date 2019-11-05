<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Test</title>
</head>
<body>
<?php
    require_once ('../bootstrap.php');

    use App\FileLoader;

    var_dump($_SERVER['REQUEST_URI']);

    FileLoader::render($_SERVER['REQUEST_URI'], '../data');

?>
</body>
</html>
