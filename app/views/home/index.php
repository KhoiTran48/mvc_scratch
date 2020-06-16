<h1>Hello World</h1>
<h3>Name: <?php echo $name; ?></h3>
<h3>Age: <?php echo $age; ?></h3>
<?php
    $this->renderPartial("partials/header", array("title" => "Title ne"));
?>
