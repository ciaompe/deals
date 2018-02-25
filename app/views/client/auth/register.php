{% extends 'includes/client/default.php' %}

{% block title %} Sign Up {% endblock %}

{% block header %}{% endblock %}

{% block content %}

	<section id="page" class="mTop-30 mBtm-50">
    <div class="row">
        <div class="col-sm-12">
        	<div class="panel-body frameLR bg-white shadow">
				<div class="tabbable responsive" id="tabs" role="tabpanel">

						<!-- TAB -->
						<ul class="nav nav-tabs" role="tablist">
							<li role="presentation">
								<a href="{{urlFor('auth_login')}}">Login</a>
							</li>
							<li class="active" role="presentation">
								<a href="{{urlFor('auth_register')}}">Manually Register</a>
							</li>
							<li role="presentation">
								<a  href="{{urlFor('auth_forgot')}}">Forgot Password ?</a>
							</li>
						</ul>
						<!-- Tab NAV -->

						<div class="tab-content">

							<div class="tab-pane active">
								<div class="wrap ">

					                <div class="col-md-5">
										
										<div>
											<h3>Sign up</h3>
											<hr style="margin-bottom: 25px;">
										</div>

										<form action="{{urlFor('auth_register')}}" method="POST">
											
											<div class="form-group{% if errors.has('email') %} has-error{% endif %}">
												<label class="control-label" for="email">Email: </label>
												<input type="email" class="form-control" name="email" placeholder="johndoe@example.com" value="{% if request.post('email') %}{{request.post('email')}}{% endif %}">
												{% if errors.has('email') %} 
													<span class="help-block">{{errors.first('email') }}</span>
												{% endif %}
											</div>
																
											<div class="form-group{% if errors.has('password') %} has-error{% endif %}">
												<label class="control-label" for="password">Password: </label>
												<input  type="password" class="form-control" name="password" placeholder="********">
												{% if errors.has('password') %} 
													<span class="help-block">{{errors.first('password') }}</span>
												{% endif %}
											</div>

											<div class="form-group{% if errors.has('password_confirm') %} has-error{% endif %}">
												<label class="control-label" for="password_confirm">Confirm Password: </label>
												<input  type="password" class="form-control" name="password_confirm" placeholder="********">
												{% if errors.has('password_confirm') %} 
													<span class="help-block">{{errors.first('password_confirm') }}</span>
												{% endif %}
											</div>

											<div class="form-group">
												<div class="g-recaptcha" data-theme="light" data-sitekey="{{sitekey}}"></div>

												{% if capcha is not empty %} 
													{% for value in capcha%}
													<span class="help-block" style="color:#a94442">please verify your humanity</span>
													{% endfor %}
												{% endif %}
											</div>

											<input type="hidden" name="csrf_token" value="{{csrf_token}}">
										   
											<div class="form-group">
							                    <button type="submit" class="btn btn-success btn-md btn-raised ripple-effect">
							                        Register
							                    </button>
						                    </div>

										</form>
										<br>


					                </div>

					                 <div class="col-md-6 col-md-offset-1">

					                 	<div>
						                    <h3 class="dark-grey">Terms and Conditions</h3>
						                    <hr style="margin-bottom: 25px;">
					                    </div>

					                    <p>
					                        By clicking on "Register" you agree to The Company's' Terms and Conditions
					                    </p>
					                    <p>
					                        While rare, prices are subject to change based on exchange rate fluctuations - should such a fluctuation happen, we may request an additional payment. You have the option to request a full refund or to pay the new price. (Paragraph 13.5.8)
					                    </p>
					                    <p>
					                        Should there be an error in the description or pricing of a product, we will provide you with a full refund (Paragraph 13.5.6)
					                    </p>
					                    <p>
					                        Acceptance of an order by us is dependent on our suppliers ability to provide the product. (Paragraph 13.5.6)
					                    </p>
						             </div>

								</div>
								<!--/wrap -->
							</div>
							<!-- /tab pane -->

						</div>
						<!-- TAB CONTENT -->

				</div>
			</div>	
        </div>
    </div>
</section>

{% endblock %}
