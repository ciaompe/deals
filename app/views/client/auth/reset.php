{% extends 'includes/client/default.php' %}

{% block title %} Reset Password {% endblock %}

{% block header %}
{% endblock %}

{% block content %}
	
	<section id="page" class="mTop-30 mBtm-50">
	    <div class="row">
	        <div class="col-sm-12">
	        	<div class="panel-body frameLR bg-white shadow">
					
					 <div class="col-md-5">
						<div>
							<h3>Reset Password</h3>
							<hr style="margin-bottom: 25px;">
						</div>

						 <form action="{{urlFor('auth_reset')}}?token={{token|url_encode}}" method="POST">
							<div class="form-group{% if errors.has('password') %} has-error{% endif %}">
								<label class="control-label" for="password">Password: </label>
								<input  type="password" class="form-control" name="password">
								{% if errors.has('password') %} 
									<span class="help-block">{{errors.first('password') }}</span>
								{% endif %}
							</div>

							<div class="form-group{% if errors.has('password_confirm') %} has-error{% endif %}">
								<label class="control-label" for="password_confirm">Confirm Password: </label>
								<input  type="password" class="form-control" name="password_confirm">
								{% if errors.has('password_confirm') %} 
									<span class="help-block">{{errors.first('password_confirm') }}</span>
								{% endif %}
							</div>

							<input type="hidden" name="csrf_token" value="{{csrf_token}}">

							<div class="form-group">
			                    <button type="submit" class="btn btn-success btn-md btn-raised ripple-effect">
			                        Save Changes
			                    </button>
		                    </div>

							
						</form>

					 </div>

	        	</div>
	        </div>
	    </div>
    </section>
	
{% endblock %}