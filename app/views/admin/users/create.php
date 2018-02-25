{% extends 'includes/admin/default_two.php' %}

{% block title %} Create User {% endblock %}


{% block pageheader %}

	<div class="page-header page-header-green-sea">
	  <h1><i class="fa fa-sort"></i>Deals</h1>
	</div>
	<!-- END PAGE HEADER -->

	<ol class="breadcrumb">
	  <li><a href="{{urlFor('admin_home')}}">Home</a></li>
	  <li><a href="{{urlFor('admin_user_manage')}}">Users</a></li>
	  <li class="active">Create User</li>
	</ol>

	<!-- END BREADCRUMB -->
{% endblock %}


{% block content %}

		<div class="row">
			<div class="col-md-6">
				<div class="widget">
					<div class="widget-content-white glossed padded">

						<h3 class="form-title form-title-first"><i class="fa fa-terminal"></i>Create User</h3>

					    <form class="create_form" action="{{urlFor('admin_create_user')}}" method="POST">

					    	<div class="form-group{% if errors.has('first_name') %} has-error{% endif %}">
								<label class="control-label" for="first_name">First Name: </label>
								<input type="text" class="form-control" name="first_name" value="{% if request.post('first_name') %}{{request.post('first_name')}}{%endif%}">
								{% if errors.has('first_name') %} 
									<span class="help-block">{{errors.first('first_name') }}</span>
								{% endif %}
							</div>

							<div class="form-group{% if errors.has('last_name') %} has-error{% endif %}">
								<label class="control-label" for="last_name">Last Name: </label>
								<input type="text" class="form-control" name="last_name" value="{% if request.post('last_name') %}{{request.post('last_name')}}{%endif%}">
								{% if errors.has('last_name') %} 
									<span class="help-block">{{errors.first('last_name') }}</span>
								{% endif %}
							</div>

							<div class="form-group{% if errors.has('email') %} has-error{% endif %}">
								<label class="control-label" for="email">Email: </label>
								<input type="text" class="form-control" name="email" value="{% if request.post('email') %}{{request.post('email')}}{%else%}{{user.email}}{%endif%}">
								{% if errors.has('email') %} 
									<span class="help-block">{{errors.first('email') }}</span>
								{% endif %}
							</div>
												
							<div class="form-group{% if errors.has('password') %} has-error{% endif %}">
								<label class="control-label" for="password">Password: </label>
								<input  type="password" class="form-control" name="password">
								{% if errors.has('password') %} 
									<span class="help-block">{{errors.first('password') }}</span>
								{% endif %}
							</div>

							<div class="form-group{% if errors.has('role') %} has-error{% endif %}">
								<label class="control-label" for="role">User role : </label>
								<select name="role" id="role" class="form-control">
									<option value="">Select a user role</option>
									<option value="admin">Administrator</option>
									<option value="staff">Staff</option>
								</select>
								{% if errors.has('role') %} 
									<span class="help-block">{{errors.first('role') }}</span>
								{% endif %}
							</div>

							<input type="hidden" name="csrf_token" value="{{csrf_token}}">
							<button class="btn btn-primary" type="submit">Create</button>

						</form>
					</div>
				</div>

			</div>
    	</div>

{% endblock %}

{% block footer %}
<script>
	
	$(function() {

			$('.create_form').formValidation({
		        framework: 'bootstrap',
		        icon: {
		            valid: 'null',
		            invalid: 'null',
		            validating: 'null'
		        },
		        fields: {
					
					first_name: {
		            	validators: {
		                   notEmpty: {
		                        message: 'First Name is required'
		                    }, 
		                   stringLength: {
		               			max: 50,
		                        message: 'First name must be less than than 50 characters',
		                   }
		                   
		                }
		            },

		            last_name: {
		            	validators: {
		                   notEmpty: {
		                        message: 'Last Name is required'
		                    }, 
		                   stringLength: {
		               			max: 50,
		                        message: 'Last name must be less than than 50 characters',
		                   }
		                   
		                }
		            },

		            email: {
		            	validators: {
		                   notEmpty: {
		                        message: 'Email address is required'
		                    }, 
		                   emailAddress: {
		                        message: 'Enter valid email address'
		                   },
		                   remote: {
				                message: 'Email address is already taken',
				                url: "{{ urlFor('admin_user_email_check') }}",
				                type: 'POST',
				                data: {'email':$('input[name=email]').val(), 'csrf_token': $('input[name=csrf_token]').val()},
				            }
		                }
		            },
		            
		            password: {
		            	validators: {
		                   notEmpty: {
		                        message: 'Password is required'
		                    }, 
		                   stringLength: {
		               			min: 6,
		                        message: 'Password must be greater than 6 characters',
		                   }
		                   
		                }
		            },
		            role: {
		            	validators: {
		                   notEmpty: {
		                        message: 'User role is required'
		                   }
		                }
		            },
		            

		        }
    		});
		});

</script>
{% endblock %}