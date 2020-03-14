<html>

<body>
<script src="script.js"></script>

<?php
error_reporting(E_ALL);

$tagged_quiz_instances=-1;

include 'functions.php';

$content = file_get_contents("uterus-gefaesse1.txt");

$data = create_picture_quiz("", $content);

echo $data;

?>

</body>
</html>
