<div class="container">
    <h1>DataBase Testing</h1>
    <div class="row">
        <div class="col-md-10">
            <?php foreach ($cities as $city):?>
                <p><?= $city['name'] ?></p>
            <?php endforeach ?>
            <nav aria-label="Page navigation example">
                <?= $link ?>
            </nav>
        </div>
    </div>
</div>