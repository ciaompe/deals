{% extends 'includes/admin/default_two.php' %}

{% block title %} Update Source {% endblock %}

{% block header %}

{% endblock %}

{% block pageheader %}

	<div class="page-header page-header-green-sea">
	  <h1><i class="fa fa-sort"></i>Sources</h1>
	</div>
	<!-- END PAGE HEADER -->

	<ol class="breadcrumb">
	  <li><a href="{{urlFor('admin_home')}}">Home</a></li>
	  <li><a href="{{urlFor('admin_deals_source_manage')}}">Sources</a></li>
	  <li class="active">{{source.name}}</li>
	</ol>

	<!-- END BREADCRUMB -->
{% endblock %}


{% block content %}

	<div class="row">

			
		<div class="col-md-6">
			<div class="widget">
				<div class="widget-content-white glossed padded">

					<h3 class="form-title form-title-first"><i class="fa fa-terminal"></i>Update Source {% if admin.isAdmin() %}<a href="{{urlFor('admin_deals_source_delete', {'id' : source.id })}}" class="btn btn-danger delete delete-btn pull-right"><i class="fa fa-trash-o"></i></a>{% endif %}</h3>

				    <form class="create_source_form" action="{{urlFor('admin_deals_source_update', {'id':source.id})}}" method="POST">

				    	<div class="form-group{% if errors.has('name') %} has-error{% endif %}">
							<label class="control-label" for="name">Source Name: </label>
							<input type="text" class="form-control" name="name" placeholder="ebay" value="{% if request.post('name') %}{{request.post('name')}}{%else%}{{source.name}}{%endif%}">
							{% if errors.has('name') %} 
								<span class="help-block">{{errors.first('name') }}</span>
							{% endif %}
						</div>
						<div class="form-group{% if errors.has('url') %} has-error{% endif %}">
							<label class="control-label" for="url">Source Url: </label>
							<input type="text" class="form-control" name="url" placeholder="http://ebay.com" value="{% if request.post('url') %}{{request.post('url')}}{%else%}{{source.url}}{%endif%}">
							{% if errors.has('url') %} 
								<span class="help-block">{{errors.first('url') }}</span>
							{% endif %}
						</div>

						<input type="hidden" name="csrf_token" value="{{csrf_token}}">
						<button type="submit" class="btn btn-primary">Update</button>
						

					</form>

				</div>
	    	</div>
	    </div>

	    <div style="width:200px; float:left; margin-left:15px;">
			<div class="widget">
			  		<div class="widget-content-white glossed padded">

						<h3 class="form-title form-title-first"><i class="fa fa-image"></i>LOGO</h3>

						<div class="deals_source_image" id="crop-source-logo">
							<div class="source_logo avatar-view" title="Change the logo">
							
								{% if source_logo is defined %}

									{% if source_logo is not null %}	
									 	<img src="{{source_logo}}">
									{% else %}
										<img src="{{source.getLogo(baseUrl, images, true)}}">
									{% endif %}
									
								{% else %}
									<img src="{{source.getLogo(baseUrl, images, true)}}">
								{% endif %}

							</div>
							{% include 'admin/deals/logoCrop.php' %}
						</div>

						<small style="margin-top:7px; display:block">Image Size : <b>{{config.source.width}} * {{config.source.height}}</b></small>

					</div>
			</div>
		</div>

   </div>

	
{% endblock %}

{% block footer %}

	<script type="text/javascript">
		$(function() {

			$('.create_source_form').formValidation({
		        framework: 'bootstrap',
		        icon: {
		            valid: 'null',
		            invalid: 'null',
		            validating: 'null'
		        },
		        fields: {

		        	name: {
		            	validators: {
		                    notEmpty: {
		                        message: 'Source name is required'
		                    },
		                    stringLength: {
		               			max: 20,
		                        message: 'Source name is too large',
		                   	}
		                   
		                }
		            },

		            url: {
		            	validators: {
		                    notEmpty: {
		                        message: 'Source url is required'
		                    },
		                   	uri: {
		                        message: 'Source url is not valid'
		                    }
		                }
		            },
		     
		        }
    		});

			$(".select-image").click(function() {
		     	$("#avatarInput").click();
		 	});

		 	$('.delete').click(function(e) {
					e.preventDefault();
					var url = $(this).attr('href');
					swal({   
						title: "Are you sure?",   
						text: "You will not be able to recover all deals belongs to this source",   
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

			return new CropAvatar($('#crop-source-logo'));

		});
	</script>

{% endblock %}