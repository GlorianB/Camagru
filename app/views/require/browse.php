<main class="browse">
<?php foreach ($data as $image_id => $image_info):?>
<?php if ($image_id !== "icons" && $image_id !== "page"):?>
        <section class="image">
            <a href="<?php if (!isset($_SESSION["loggedin"])){echo "/camagru/login";} else {echo "";}?>" class="image-link">
                <div class="image-container">
                    <img src=<?="/camagru/public/assets/img/" . $image_info["path"]?> alt="<?=$image_info["alt"]?>" 
                    title="<?=$image_info["name"]?>" data-id="<?=$image_id?>" data-date="<?=$image_info["date"]?>" 
                    data-username="<?=$image_info["user"]?>" data-userid="<?=$image_info["user_id"]?>" data-likes=<?=$image_info["likes"]?>
                    data-comments="<?=$image_info["comments"]?>" data-liked="<?=$image_info["liked"]?>" height=240px width=360px>
                </div>
            </a>
    </section>
    <?php endif?>
<?php endforeach?>
</main>
<div class="image-popup"></div>
<?php $session_value=(isset($_SESSION['loggedin']))?$_SESSION['loggedin']:false; ?>
<script>
    var session_state='<?php echo $session_value;?>';
</script>
<script src=<?php echo $this->js_popup_href; ?>></script>

