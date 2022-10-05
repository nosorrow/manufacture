<?php $valid = (sessionData('_previous') == site_url('test')) ? 'is-valid' : ''; ?>
<div class="container">
    <div class="row justify-content-md-center">
        <div class="col-md-12">
					<form method="post" action="<?php echo site_url('search'); ?>" enctype="multipart/form-data">
                <?php csrf_field(); ?>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email"
                           value="<?php echo old('email'); ?>"
                           class="form-control <?php echo ($errors->has('email')) ? 'is-invalid' : $valid; ?>">
                    <small id="emailHelp" class="invalid-feedback">
                        <?php echo($errors->first('email')); ?>
                    </small>
                </div>
                <div class="form-group">
                    <label for="pass">Pass</label>
                    <input type="text" name="pass"
                           value="<?= old('pass') ?>"
                           class="form-control <?php echo ($errors->has('pass')) ? 'is-invalid' : $valid; ?>">
                    <small id="passlHelp" class="invalid-feedback">
                        <?php echo($errors->first('pass')); ?>
                    </small>
                </div>
                <p></p><?= flash('msg') ?></p>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>
