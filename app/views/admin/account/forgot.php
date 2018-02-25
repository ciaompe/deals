{% extends 'includes/admin/default.php' %}

{% block title %} Forgot Password ? {% endblock %}

{% block header %}

{% endblock %}

{% block content %}
	
	<div class="content-wrapper bold-shadow">
	    <div class="content-inner">
	       <div class="main-content main-content-grey-gradient no-page-header">
	         <div class="main-content-inner">

			    <form action="{{urlFor('admin_forgot')}}" method="POST">

					<div class="form-group{% if errors.has('email') %} has-error{% endif %}">
						<label class="control-label" for="email">Email: </label>
						<input type="text" class="form-control" name="email">
						{% if errors.has('email') %} 
							<span class="help-block">{{errors.first('email') }}</span>
						{% endif %}
					</div>

					<input type="hidden" name="csrf_token" value="{{csrf_token}}">

					<button type="submit" class="btn btn-primary btn-md">Reset</button>

				</form>

			 </div>
        </div>
      </div>
    </div>

{% endblock %}