<?php
    $data = json_decode(file_get_contents($json), true)[$page];
    $image = $data["hero"];
    $text = $data["text"];
    $overview = $data["overview"];
?>
<form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post" enctype="multipart/form-data" class="row g-3 mt-1">
    <div class="col-6">
        <div class='ratio ratio-16x9'>
            <img src="<?php echo image($image, "heros", 460, 260); ?>" alt="hero" class="img-thumbnail object-fit-cover">
        </div>
    </div>
    <div class="col-6">
        <div class="form-floating">
            <input type="file" id="image" name="image" class="form-control" accept="image/*">
            <label for="image">Image upload (16x9)</label>
        </div>
        <div class="form-floating mt-3">
            <input type="text" id="text" name="text" class="form-control" placeholder="Hero Text" autocomplete="off" value="<?php echo $text; ?>" required>
            <label for="text">Hero Text</label>
        </div>
    </div>
    <div class="col-12">
        <div class="form-floating">
            <textarea id="overview" name="overview" class="form-control" placeholder="Overview" style="height: 100px; resize:none" autocomplete="off"><?php echo $overview; ?></textarea>
            <label for="overview">Overview</label>
        </div>
    </div>
    <div class="col-2"><button type="submit" class="btn btn-primary w-100">Save</button></div>
    <div class="col-10"><?php include_once "php/response-2.php"; ?></div>
</form>