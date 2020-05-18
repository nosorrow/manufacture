<div class="container">
    <?php if ($paginator->getNumPages() > 1): ?>
        <div class="row">
            <div class="col-md-3">
                <div class="input-group mb-3">
                    <?php if ($paginator->getPrevUrl()): ?>

                        <div class="input-group-prepend">
                            <a href="<?php echo $paginator->getPrevUrl(); ?>" class="btn btn-outline-secondary">&laquoPrev</a>
                        </div>
                    <?php endif; ?>
                    <select class="form-control paginator-select-page" style="width: auto; cursor: pointer; -webkit-appearance: none; -moz-appearance: none; appearance: none;">
                        <?php foreach ($paginator->getPages() as $page): ?>
                            <?php if ($page['url']): ?>
                                <option value="<?php echo $page['url']; ?>"<?php if ($page['isCurrent']) echo ' selected'; ?>>
                                    Page <?php echo $page['num']; ?>
                                </option>
                            <?php else: ?>
                                <option disabled><?php echo $page['num']; ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                    <?php if ($paginator->getNextUrl()): ?>
                        <div class="input-group-prepend" id="button-addon3">
                            <a href="<?php echo $paginator->getNextUrl(); ?>" class="btn btn-outline-secondary">Next &raquo;</a>
                        </div>
                    <?php endif?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<script>
    $(function() {
        $('.paginator-select-page').on('change', function() {
            document.location = $(this).val();
        });
        // Workaround to prevent iOS from zooming the page when clicking the select list:
        $('.paginator-select-page')
            .on('focus', function() {
                if (/(iPad|iPhone|iPod)/g.test(navigator.userAgent)) {
                    $(this).css('font-size', '16px');
                }
            })
            .on('blur', function() {
                if (/(iPad|iPhone|iPod)/g.test(navigator.userAgent)) {
                    $(this).css('font-size', '');
                }
            })
        ;
    });
</script>