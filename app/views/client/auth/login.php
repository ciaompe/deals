{% extends 'includes/client/default.php' %}

{% block title %} Login {% endblock %}

{% block header %}

{% endblock %}

{% block content %}

<section id="page" class="mTop-30 mBtm-50">
    <div class="row">
        <div class="col-sm-12">
        	<div class="panel-body frameLR bg-white shadow">
				<div class="tabbable responsive" id="tabs" role="tabpanel">
						<!-- TAB -->
						<ul class="nav nav-tabs">
							<li role="presentation" class="active">
								<a href="{{urlFor('auth_login')}}">Login</a>
							</li>
							<li role="presentation">
								<a href="{{urlFor('auth_register')}}">Manually Register</a>
							</li>
							<li role="presentation">
								<a  href="{{urlFor('auth_forgot')}}">Forgot Password ?</a>
							</li>
						</ul>
						<!-- Tab NAV -->

						<div class="tab-content">

							<div class="tab-pane active">
								<div class="wrap">

					                <div class="col-md-6 ">
					                	<div>
											<h3>Social Sign in</h3>
											<hr style="margin-bottom: 25px;">
										</div>
										
										<p>
					                        Do you happen to be a user of any of the following social networks ? If so you can simply login to the web site and have your account automatically created just by authenticating via your preffered web site of choice.
					                    </p>

										<a href="{{urlFor('auth_social', {'provider': 'Facebook'})}}" class="btn btn-block btn-social btn-facebook">
										<i class="fa fa-facebook"></i> Sign in with Facebook
										</a>

										<a href="{{urlFor('auth_social', {'provider': 'Google'} )}}" class="btn btn-block btn-social btn-google-plus">
										<i class="fa fa-google-plus"></i> Sign in with Google
										</a>

										<a href="{{urlFor('auth_social', {'provider': 'Twitter'} )}}" class="btn btn-block btn-social btn-twitter">
										<i class="fa fa-twitter"></i> Sign in with Twitter
										</a>

										<p class="mTop-30">
					                        We do not collect any information related to you from the social networks. This is stirtly for Authentication purposes.
					                    </p>
					                </div>

					                <div class="col-md-5 col-md-offset-1">
										<div>
											<h3>Sign in</h3>
											<hr style="margin-bottom: 25px;">
										</div>

										<form action="{{urlFor('auth_login')}}" method="POST">

											<div class="form-group{% if errors.has('identifier') %} has-error{% endif %}">
												<label class="control-label" for="identifier">Email: </label>
												<input type="email" class="form-control" name="identifier" value="{% if request.post('identifier') %}{{request.post('identifier')}}{% endif %}">
												{% if errors.has('identifier') %} 
													<span class="help-block">{{errors.first('identifier') }}</span>
												{% endif %}
											</div>

						                    <div class="form-group{% if errors.has('password') %} has-error{% endif %}">
												<label class="control-label" for="password">Password: </label>
												<input  type="password" class="form-control" name="password">
												{% if errors.has('password') %} 
													<span class="help-block">{{errors.first('password') }}</span>
												{% endif %}
											</div>

						                    <div class="form-group">
												<input type="checkbox" name="remember_me" style="margin-right:10px;"><label for="remember_me">Remember me</label>
											</div>

											{% if showCapcha %}
												<!-- Google Recapcha -->
												<div class="form-group">
													<div class="g-recaptcha" data-theme="light" data-sitekey="{{sitekey}}"></div>

													{% if capcha is not empty %} 
														{% for value in capcha%}
														<span class="help-block" style="color:#a94442">please verify your humanity</span>
														{% endfor %}
													{% endif %}
												</div>
												<!-- End Capcha -->
											{% endif %}

											<input type="hidden" name="csrf_token" value="{{csrf_token}}">
											
											<div class="form-group">
							                    <button type="submit" class="btn btn-success btn-md btn-raised ripple-effect">
							                        Login
							                    </button>
						                    </div>

						                </form>

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
