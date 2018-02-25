{% extends 'includes/client/default.php' %}

{% block title %} Forgot Password ? {% endblock %}

{% block header %}

{% endblock %}

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
							<li role="presentation">
								<a href="{{urlFor('auth_register')}}">Manually Register</a>
							</li>
							<li class="active" role="presentation">
								<a  href="{{urlFor('auth_forgot')}}">Forgot Password ?</a>
							</li>
						</ul>
						<!-- Tab NAV -->

						<div class="tab-content">

							<div class="tab-pane active">
								<div class="wrap ">

					                <div class="col-md-5">
										<div>
											<h3>Forgot Password ?</h3>
											<hr style="margin-bottom: 25px;">
										</div>

										<form action="{{urlFor('auth_forgot')}}" method="POST">
											<div class="form-group{% if errors.has('email') %} has-error{% endif %}">
												<label class="control-label" for="email">Email: </label>
												<input type="email" class="form-control" name="email">
												{% if errors.has('email') %} 
													<span class="help-block">{{errors.first('email') }}</span>
												{% endif %}
											</div>

											<input type="hidden" name="csrf_token" value="{{csrf_token}}">


											<div class="form-group">
							                    <button type="submit" class="btn btn-success btn-md btn-raised ripple-effect">
							                        Reset
							                    </button>
						                    </div>
										
										</form>	

					                </div>

					                <div class="col-md-6 col-md-offset-1">
					                	<div>
						                    <h3 class="dark-grey">The Procedure</h3>
						                    <hr style="margin-bottom: 25px;">
										</div>

					                    <p>
					                        Enter the email address you used when you created the account along with the last password you remember.
					                    </p>
					                    <p> Once the request is made to our automated system, It will analize the request before sending you an automated email with password reset instructions.</p>
					                    <p>
					                    	In case where a request is suspicious, human assistance will be required, the process will be kept on hold until one of our support technicians contact you through email.
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