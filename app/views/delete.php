<?php require_once "require/head.php";?>
<body class="homebody">
    <?php require_once "require/header.php";?>
    <div class="content">
        <h2>Delete Image #<?=$data['image_id']?></h2>
        <div class="yesno">
            <p>Are you sure you want to delete <?=$data["image_title"]?>?</p>
            <a class="green-button" href="/camagru/image/deleted/<?=$data['image_id']?>">Yes</a>
            <a class="green-button" href="/camagru/">No</a>
        </div>
    </div>
</body>
<?php require_once "require/footer.php"?>