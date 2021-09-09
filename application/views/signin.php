<?php if($this->session->userdata('UserID')==false) { ?>
<div class="section bg-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8 text-center">

                <?php if($this->session->userdata('message')) { ?>
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <?php echo $this->session->userdata('message'); 
						 $this->session->unset_userdata('message');
						?>
                </div>
                <?php } ?>
                <?php if ($this->session->userdata('exception')) { ?>
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <?php echo $this->session->userdata('exception');
						 $this->session->unset_userdata('exception');
						 ?>
                </div>
                <?php } ?>
                <?php if (validation_errors()) { ?>
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <?php echo validation_errors(); ?>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="form-wrapper m-auto">
                <div class="form-container my-4">
                    <div class="panel">
                        <div class="panel-header text-center mb-3">
                            <h3 class="fs-24"><?php echo display('signin_to_your_account') ?></h3>
                            <p class="text-muted text-center mb-0"><?php echo display('nice_to_see_you') ?></p>
                        </div>
                        <?php echo form_open('loginsubmit'); ?>
                        <div class="form-group">
                            <input type="email" class="form-control is-invalid" id="emial" name="email"
                                placeholder="Enter email">
                            <div class="invalid-feedback"><?php echo display('enter_your_valid_email') ?></div>
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" id="pass" name="password"
                                placeholder="Password">
                        </div>

                        <button type="submit"
                            class="btn btn-primary btn-block"><?php echo display('sign_in') ?></button>
                        <?php echo form_close() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } else{
    $this->session->set_flashdata('exception',display('please_logout'));
    redirect('');
} ?>