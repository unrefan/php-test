<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Test</title>
</head>
<body>
<?php
    require_once ('../bootstrap.php');
    use App\PagesCreator;
    (new PagesCreator('data'))->read()->render();
?>
</body>
</html>
