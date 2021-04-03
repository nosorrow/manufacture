<?php $valid = (sessionData('_previous') == site_url('test')) ? 'is-valid' : ''; ?>
<div class="container">
  <?php var_dump($_SESSION);?>
  <h3 class="text-center">Login form</h3>
  <div class="row justify-content-md-center">
    <div class="col-md-12">
      <form method="post" action="<?php echo site_url('store-blade'); ?>" enctype="multipart/form-data">
          <?php csrf(); ?>
        <div class="form-group">
          <label for="email">Email address</label>
          <input type="text" name="email"
                 class="form-control <?php echo ($errors->has('email')) ? 'is-invalid' : $valid; ?>"
                 id="email" placeholder="Enter email" value="<?php echo old('email'); ?>">
          <small id="emailHelp" class="invalid-feedback">
              <?php echo($errors->first('email')); ?>
          </small>
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input name="password" type="text" value="<?php echo old('password'); ?>"
                 class="form-control <?php echo ($errors->has('password')) ? 'is-invalid' : $valid; ?>"
                 id="password" placeholder="Password">
          <small id="emailHelp" class="invalid-feedback">
              <?php echo($errors->first('password')); ?>
          </small>
        </div>

        <div class="form-group">
          <label for="date">Date1</label>
          <input name="date1" type="date" value="<?php echo old('date1'); ?>"
                 class="form-control <?php echo ($errors->has('date1')) ? 'is-invalid' : $valid; ?>"
                 id="password" placeholder="Password">
          <small id="emailHelp" class="invalid-feedback">
              <?php echo($errors->first('date1')); ?>
          </small>
        </div>

        <div class="form-group">
          <label for="date">date2</label>
          <input name="date2" type="date" value="<?php echo old('date2'); ?>"
                 class="form-control <?php echo ($errors->has('date2')) ? 'is-invalid' : $valid; ?>"
                 id="password" placeholder="Password">
          <small id="emailHelp" class="invalid-feedback">
              <?php echo($errors->first('date2')); ?>
          </small>
        </div>

        <div class="form-group form-check">
          <input type="checkbox" name="agree" value="d23"
                 class="form-check-input <?php echo ($errors->has('agree')) ? 'is-invalid' : $valid; ?>"
                 id="exampleCheck1" <?php echo (old('agree')) ? 'checked' : ''; ?>>
          <label class="form-check-label" for="exampleCheck1">Check me out</label>
          <small class="invalid-feedback">
              <?php echo($errors->first('agree')); ?>
          </small>
        </div>

        <div class="form-group">
          <label for="file">Select file</label>
          <input type="file" name="file[]" multiple
                 class="form-control <?= ($errors->has('file')) ? 'is-invalid' : $valid ?>"
                 id="file">
          <small id="fileHelp" class="invalid-feedback">
              <?= $errors->first('file') ?>
          </small>
        </div>

        <div class="form-group">
          <label for="file1">Select file 1</label>
          <input type="file" name="file1[]" multiple
                 class="form-control <?= ($errors->has('file1')) ? 'is-invalid' : $valid ?>"
                 id="file1">
          <small id="fileHelp" class="invalid-feedback">
              <?= $errors->first('file1') ?>
          </small>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
      </form>
    </div>
  </div>
  <div class="row justify-content-md-center">
    <div class="col-md-12">
        <?php
        if ($errors->any()) {
            foreach ($errors->all() as $error) {
                echo '<li>' . $error . '</li>';
            }
        }; ?>
    </div>
  </div>
</div>
