{% extends 'includes/admin/default_two.php' %}

{% block title %} Edit User {% endblock %}

{% block pageheader %}

	<div class="page-header page-header-green-sea">
	  <h1><i class="fa fa-sort"></i>Deals</h1>
	</div>
	<!-- END PAGE HEADER -->

	<ol class="breadcrumb">
	  <li><a href="{{urlFor('admin_home')}}">Home</a></li>
	  <li><a href="{{urlFor('admin_user_manage')}}">Users</a></li>
	  <li class="active">{{user.getName()}}</li>
	</ol>

	<!-- END BREADCRUMB -->

{% endblock %}

{% block content %}

		{% if user.isBan() %}
		<div class="alert alert-warning">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<strong>Warning! </strong>User is banned
		</div>
		{% endif %}

		<div class="row">
			<div class="col-md-6">
				
				<div class="widget">
					<div class="widget-content-white glossed padded">

						<h3 class="form-title form-title-first"><i class="fa fa-terminal"></i>Update User</h3>

					    <form class="update_form" action="{{urlFor('admin_users_update_post', {id: user.id} ) }}" method="POST">

					    	<div class="form-group{% if errors.has('first_name') %} has-error{% endif %}">
								<label class="control-label" for="first_name">First Name: </label>
								<input type="text" class="form-control" name="first_name" value="{% if request.post('first_name') %}{{request.post('first_name')}}{%else%}{{user.f_name}}{%endif%}">
								{% if errors.has('first_name') %} 
									<span class="help-block">{{errors.first('first_name') }}</span>
								{% endif %}				
							</div>

							<div class="form-group{% if errors.has('last_name') %} has-error{% endif %}">
								<label class="control-label" for="last_name">Last Name: </label>
								<input type="text" class="form-control" name="last_name" value="{% if request.post('last_name') %}{{request.post('last_name')}}{%else%}{{user.l_name}}{%endif%}">
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
							
							{% if user.isLocalUser() %}					
							<div class="form-group{% if errors.has('password') %} has-error{% endif %}">
								<label class="control-label" for="password">Password: </label>
								<input  type="password" class="form-control" name="password">
								{% if errors.has('password') %} 
									<span class="help-block">{{errors.first('password') }}</span>
								{% endif %}
							</div>

							<div class="form-group">
								<label class="control-label" for="role">User role : </label>
								<select name="role" id="role" class="form-control">
									<option value="">Select a user role</option>
									<option value="admin" {% if (user.isAdmin())%} selected{% endif %}>Administrator</option>
									<option value="staff" {% if (user.isStaff())%} selected{% endif %}>Staff</option>
								</select>
							</div>

							{% endif %}

							<input type="hidden" name="csrf_token" value="{{csrf_token}}">
							<button class="btn btn-primary" type="submit">Update</button>

						</form>
					</div>
				</div>
			</div>

			{% if user.isLocalUser() and user.hasAvatar() %}
			<div class="col-md-6">
				<div class="widget" style="width:264px;">
				  		<div class="widget-content-white glossed padded">
	  						<h3 class="form-title form-title-first"><i class="fa fa-image"></i>Avatar</h3>
	  						<div class="deals_source_image">
	                  			<img style="max-width:200px" src="{{user.getAvatar(baseUrl, images, true)}}" alt="propic">
	  						</div>
	  						<a href="{{urlFor('admin_users_reset_propic', {'id':user.id})}}" class="btn btn-danger btn-md" style="margin-top:12px;">Reset</a>
	  			  		</div>
		        </div>
			</div>
			{% else %}
			<div class="col-md-6">
				<div class="widget">
					<div class="widget-content-white glossed padded">

						<h3 class="form-title form-title-first"><i class="fa fa-pencil-square"></i> User Actions</h3>

						{% if (user.isBan())%}
							<a href="{{urlFor('admin_users_unban', {id: user.id} )}}" class="btn btn-warning"><i class="fa fa-ban"></i> Unban</a>
						{% else %}
							<a href="{{urlFor('admin_users_ban', {id: user.id} )}}" class="btn btn-warning"><i class="fa fa-ban"></i> Ban</a>
						{% endif %}

						{% if (not user.isActivated())%}
							<a href="{{urlFor('admin_users_activate', {id: user.id} )}}" class="btn btn-success"><i class="fa fa-check-square-o"></i> Activate</a>	
						{% endif %}

						<a href="{{urlFor('admin_users_delete', {id: user.id} )}}" class="btn btn-danger delete"><i class="fa fa-trash-o"></i> Delete</a>

					</div>
				</div>
			</div>
			{% endif %}

		</div>
			
		{% if user.isLocalUser() and user.hasAvatar() %}
		<div class="row">
			<div class="col-md-6">
				<div class="widget">
					<div class="widget-content-white glossed padded">

						<h3 class="form-title form-title-first"><i class="fa fa-pencil-square"></i> User Actions</h3>

						{% if (user.isBan())%}
							<a href="{{urlFor('admin_users_unban', {id: user.id} )}}" class="btn btn-warning"><i class="fa fa-ban"></i> Unban</a>
						{% else %}
							<a href="{{urlFor('admin_users_ban', {id: user.id} )}}" class="btn btn-warning"><i class="fa fa-ban"></i> Ban</a>
						{% endif %}

						{% if (not user.isActivated())%}
							<a href="{{urlFor('admin_users_activate', {id: user.id} )}}" class="btn btn-success"><i class="fa fa-check-square-o"></i> Activate</a>	
						{% endif %}

						<a href="{{urlFor('admin_users_delete', {id: user.id} )}}" class="btn btn-danger delete"><i class="fa fa-trash-o"></i> Delete</a>

					</div>
				</div>
			</div>
		</div>
		{% endif %}

{% endblock %}

{% block footer %}
<script>
	
	$(function() {

		$('.update_form').formValidation({
	        framework: 'bootstrap',
	        icon: {
	            valid: 'null',
	            invalid: 'null',
	            validating: 'null'
	        },
	        fields: {

	        	first_name: {
	            	validators: {
	                   stringLength: {
	               			max: 50,
	                        message: 'First name must be less than than 50 characters',
	                   }
	                   
	                }
	            },

	            last_name: {
	            	validators: {
	                   stringLength: {
	               			max: 50,
	                        message: 'Last name must be less than than 50 characters',
	                   }
	                   
	                }
	            },
	            password: {
	            	validators: {
	                   stringLength: {
	               			min: 6,
	                        message: 'Password must be greater than 6 characters',
	                   }
	                   
	                }
	            },

	            email: {
	            	validators: {
	                   emailAddress: {
	                        message: 'Enter valid email address'
	                   },
	                   remote: {
			                message: 'Email address is already taken',
			                url: "{{ urlFor('admin_user_update_email_check') }}",
			                type: 'POST',
			                data: {'email':$('input[name=email]').val(), 'csrf_token': $('input[name=csrf_token]').val(), 'id': "{{user.id}}" },
			           }
	                }
	            }
	        }
    	});

		$('.delete').click(function(e) {
			e.preventDefault();
			var url = $(this).attr('href');
			swal({   
				title: "Are you sure?",   
				text: "You will not be able to recover this user again",   
				type: "warning",   
				showCancelButton: true,   
				confirmButtonColor: "#DD6B55",   
				confirmButtonText: "Yes, delete it!",   
				cancelButtonText: "No, cancel !",   
				closeOnConfirm: true,   
				closeOnCancel: true
			},
			function(isConfirm){   
				if (isConfirm) { 
			    	window.location.href = url;
			    } 
			});

		});

	});

	
</script>
{% endblock %}