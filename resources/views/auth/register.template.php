<div class="text-bg-dark">
    <div class="container">
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h1 class="fw-bold fs-2 text-dark-emphasis">Sign Up</h1>
                        <p class="lead fs-6">Submit the form to sign up for an account.</p>

                        <form method="POST" action="<?php echo route('/register-user'); ?>">
                            <input type="hidden" name="_method" value="post" />
                            <div class="row mb-3">
                                <div class="col-sm-12 col-md-6">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" name='name' class="form-control" autocomplete required />
                                    <span class="text-danger fs-sm"><?=error('name')?></span>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" name='email' class="form-control" autocomplete required />
                                    <span class="text-danger fs-sm"><?=error('email')?></span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-12 col-md-6">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" name='password' class="form-control" required />
                                    <span class="text-danger fs-sm"><?=error('password')?></span>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <label for="confirm-password" class="form-label">Confirm Password</label>
                                    <input type="password" name='confirm-password' class="form-control" required />
                                </div>
                            </div>
                            <div class="row mb-0">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-dark col-sm-6 col-md-6">
                                        Sign Up
                                    </button>
                                </div>
                            </div>
                            <p class="lead fs-6 my-3">Already have an account? <a href="<?php echo route('/login') ?>">Login</a></p>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>