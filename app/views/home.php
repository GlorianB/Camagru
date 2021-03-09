<?php require_once "require/head.php";?>
<body class="homebody">
    <?php require_once "require/header.php";?>
    <div class="content">
        <h2>Browse</h2>
        <p>Welcome<?php if (isset($_SESSION["loggedin"])){echo " " . $_SESSION["name"] . " ";}?> !</p>
        <a href="<?php if (!isset($_SESSION["loggedin"])){echo "/camagru/login";} else {echo "/camagru/image";}?>" class="green-button">Upload image</a>
        <div>
            <?php require_once "require/browse.php";?>
        </div>
    </div>
    <?php if (ceil($data["page"]["total_page"] / $data["page"]["num_elem"]) > 0): ?>
        <ul class="pagination">
	    <?php if ($data["page"]["page_number"] > 1): ?>
	        <li class="prev"><a href="/camagru/home/index/<?php echo $data["page"]["page_number"]-1 ?>">Prev</a></li>
	    <?php endif; ?>

	    <?php if ($data["page"]["page_number"] > 3): ?>
	        <li class="start"><a href="/camagru/home/index/1">1</a></li>
	        <li class="dots">...</li>
	    <?php endif; ?>

	    <?php if ($data["page"]["page_number"]-2 > 0): ?><li class="page"><a href="/camagru/home/index/<?php echo $data["page"]["page_number"]-2 ?>"><?php echo $data["page"]["page_number"]-2 ?></a></li><?php endif; ?>
	    <?php if ($data["page"]["page_number"]-1 > 0): ?><li class="page"><a href="/camagru/home/index/<?php echo $data["page"]["page_number"]-1 ?>"><?php echo $data["page"]["page_number"]-1 ?></a></li><?php endif; ?>

	        <li class="currentpage"><a href="/camagru/home/index/<?php echo $data["page"]["page_number"] ?>"><?php echo $data["page"]["page_number"] ?></a></li>

	    <?php if ($data["page"]["page_number"]+1 < ceil($data["page"]["total_page"] / $data["page"]["num_elem"])+1): ?><li class="page"><a href="/camagru/home/index/<?php echo $data["page"]["page_number"]+1 ?>"><?php echo $data["page"]["page_number"]+1 ?></a></li><?php endif; ?>
	    <?php if ($data["page"]["page_number"]+2 < ceil($data["page"]["total_page"] / $data["page"]["num_elem"])+1): ?><li class="page"><a href="/camagru/home/index/<?php echo $data["page"]["page_number"]+2 ?>"><?php echo $data["page"]["page_number"]+2 ?></a></li><?php endif; ?>

	    <?php if ($data["page"]["page_number"] < ceil($data["page"]["total_page"] / $data["page"]["num_elem"])-2): ?>
	        <li class="dots">...</li>
	        <li class="end"><a href="/camagru/home/index/<?php echo ceil($data["page"]["total_page"] / $data["page"]["num_elem"]) ?>"><?php echo ceil($data["page"]["total_page"] / $data["page"]["num_elem"]) ?></a></li>
	    <?php endif; ?>

	    <?php if ($data["page"]["page_number"] < ceil($data["page"]["total_page"] / $data["page"]["num_elem"])): ?>
	        <li class="next"><a href="/camagru/home/index/<?php echo $data["page"]["page_number"]+1 ?>">Next</a></li>
	    <?php endif; ?>
        </ul>
	<?php endif; ?>
</body>
<?php require_once "require/footer.php"?>