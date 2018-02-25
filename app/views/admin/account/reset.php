{% extends 'includes/admin/default.php' %}

{% block title %} Reset Password {% endblock %}

{% block header %}
{% endblock %}

{% block content %}
	
	<div class="content-wrapper bold-shadow">
	    <div class="content-inner">
	       <div class="main-content main-content-grey-gradient no-page-header">
	         <div class="main-content-inner">	

				    <form action="{{urlFor('admin_reset')}}?token={{token|url_encode}}" method="POST">

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

						<button type="submit" class="btn btn-primary btn-md">Update</button>
					</form>
				</div>
			</div>
    	</div>   	
	</div>

{% endblock %}