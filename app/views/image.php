<?php require_once "require/head.php"?>
<body class="imagebody">
    <?php require_once "require/header.php"?>
    <div class="content imagecontent">
        <div class="cam" id="cam">
            <form id="file-form" action="/camagru/image/upload" method="post" enctype="multipart/form-data">
                <input type="file" id="myfile" name="myfile">
                <input class="green-button" type="submit" id="submit" name="submit" value="No Webcam ? Upload from file !">
            </form>
            <video autoplay="true" width="400" height="300" id="video"></video>
            <a id="capture-button" href="#" class="capture-button green-button">Capture !</a>
            <div class="canvas-wrapper">
                <canvas id="canvas" width="400" height="300"></canvas>
                <canvas id="canvas2" width="400" height="300"></canvas>
            </div>
            <form action="/camagru/image/save" method="post" id="img-form">
                <input type="text" name="img_name" id="img_name" placeholder="image name" required>
                <br>
                <label for="description">Description</label>
                <br>
                <textarea name="img_description" id="img_description" cols="30" rows="10" placeholder="image description" required></textarea>
                <input type="hidden" id="img_path" name="img_path">
                <input type="hidden" id="img_path2" name="img_path2">
                <input class="green-button" type="submit" id="submit-save" name="submit" value="Save !">
            </form>
        </div>
        <aside class="icons">
            <h2>Icons</h2>
            <?php if (!empty($data["icons"])):?>
                <?php foreach ($data["icons"] as $icon_path):?>
                    <section class="icon"><img src="/camagru/public/assets/icons/<?=$icon_path?>" alt="<?=explode(".", $icon_path)[0]?>" height="36" width="24" data-selected="no"></section>
                <?php endforeach?>
            <?php endif?>
        </aside>
        <aside class="miniatures">
            <h2>My images</h2>
            <?php if (!empty($data)):?>
                <div>
                    <?php require_once "require/browse.php"?>
                </div>
            <?php endif?>
        </div>
    </div>
</body>

<?php require_once "require/footer.php"?>