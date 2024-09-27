<div class="text-bg-dark">
    <div class="container">
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h1 class="fw-bold fs-2 text-dark-emphasis">Login</h1>
                        <p class="lead fs-6">Submit the form to access your account.</p>

                        <form method="POST" action="<?php echo route('/login-user'); ?>">
                            <input type="hidden" name="_method" value="post" />
                            <div class="row mb-3">
                                <div class="col-sm-12 col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="text" name='email' class="form-control"/>
                                    <span class="text-danger fs-sm"><?=error('email')?></span>
                                 </div>
                                <div class="col-sm-12 col-md-6">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" name='password' class="form-control"/>
                                    <span class="text-danger fs-sm"><?=error('password')?></span>
                                </div>
                            </div>
                            <div class="row mb-0">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-dark col-sm-6 col-md-6">
                                        Login
                                    </button>
                                </div>
                            </div>
                            <p class="lead fs-6 my-3">Don't have an account with us? <a href="<?php echo route('/register') ?>">Sign Up</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>