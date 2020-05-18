<style>
    @import url('https://fonts.googleapis.com/css?family=Montserrat');
    .hello {
        font-family: 'Montserrat', sans-serif;
        margin:100px auto;
        width:800px;
        color: grey;
    }
</style>
<div class="hello">
    <h1 style="text-align: center;font-size: 4rem;">Welcome in PHP Manufacture</h1>
	<?php if (get_cookie('manufacture')): ?>
	<?php endif ?>
    <?php if (isset($key)):?>
    <?= alert('danger', '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> The API key not found in config.php! You can use key bellow')?>
    <div class="text-center" style="background: #000; color: #fff; padding: 15px">
        <?= $this->key ?>
    </div>
    <?php endif ?>

</div>
