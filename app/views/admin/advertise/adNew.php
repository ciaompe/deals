{% extends 'includes/admin/default_two.php' %}

{% block title %} New Ad Unit {% endblock %}

{% block header %}{% endblock %}

{% block pageheader %}

	<div class="page-header page-header-green-sea">
	  <h1><i class="fa fa-sort"></i>Advertise</h1>
	</div>

	<!-- END PAGE HEADER -->

	<ol class="breadcrumb">
	  <li><a href="{{urlFor('admin_home')}}">Home</a></li> 
	  <li><a href="{{urlFor('admin_advertise')}}">Advertise</a></li>
	  <li class="active">New Ad Unit</li>
	</ol>

	<!-- END BREADCRUMB -->

{% endblock %}

{% block content %}

		<div class="row">
			<div class="col-md-6">
				<div class="widget">
					<div class="widget-content-white glossed padded">

						<h3 class="form-title form-title-first"><i class="fa fa-terminal"></i>Create Ad unit</h3>

					    <form class="create_form" action="{{urlFor('admin_advertise_new')}}" method="POST">

							<div class="form-group{% if errors.has('type') %} has-error{% endif %}">
								<label class="control-label" for="type">Ad unit type : </label>
								<select name="type" id="type" class="form-control">
									<option value="">Select an ad unit type</option>
									<option value="code" {% if (request.post('type') == "code") %}selected{%endif%}>Ad code (Google or other)</option>
									<option value="image" {% if (request.post('type') == "image") %}selected{%endif%}>Image</option>
								</select>
								{% if errors.has('type') %} 
									<span class="help-block">{{errors.first('type') }}</span>
								{% endif %}
							</div>

							<div class="form-group{% if (errors.has('size')) or (uniterror is not null) %} has-error{% endif %}">
								<label style="margin-bottom:10px" class="control-label" for="size">Ad unit size : <a class="btn btn-xs btn-default" href="https://support.google.com/adsense/answer/6002621" target="_blank">Guide to ad sizes</a></label>
								<select name="size" id="size" class="form-control">
									<option value="">Select an ad unit size</option>

									{% for size in adsizes %}
										<option value="{{size.id}}" {% if (request.post('size') == size.id) %}selected {%endif%} data-awidth="{{size.width}}" data-aheight="{{size.height}}">{{size.name}}</option>
									{% endfor %}
									
								</select>
								{% if (errors.has('size')) or (uniterror is not null) %} 
									<span class="help-block">{{errors.first('size') }}{{uniterror}}</span>
								{% endif %}
							</div>

							<div class="form-group{% if errors.has('code') %} has-error{% endif %} code" style="display:none">
								<label class="control-label" for="size">Ad code : </label>
								
								<textarea name="code" class="form-control" cols="0" rows="0" style="width:100%; height:200px">{% if (request.post('code')) %}{{request.post('code')}}{%endif%}</textarea>

								{% if errors.has('code') %} 
									<span class="help-block">{{errors.first('code') }}</span>
								{% endif %}
							</div>

							<div class="form-group{% if errors.has('url') %} has-error{% endif %} image" style="display:none">
								<div class="form-group{% if errors.has('url') %} has-error{% endif %}">
									<label class="control-label" for="size">Ad redirect url : </label>
									
									<input type="text" name="url" class="form-control" value="{% if (request.post('url')) %}{{request.post('url')}}{%endif%}">

									{% if errors.has('code') %} 
										<span class="help-block">{{errors.first('url') }}</span>
									{% endif %}
								</div>
							</div>

							<input type="hidden" name="csrf_token" value="{{csrf_token}}">
							<button type="submit" class="btn btn-primary">Create</button>
							

						</form>

					</div>
				</div>
			</div>
			
			<div class="col-md-6 image" style="display:none">
				<div class="widget" style="width:300px;">
					<div class="widget-content-white glossed padded">
  						<h3 class="form-title form-title-first"><i class="fa fa-image"></i>Image</h3>

				  		<div class="deals_source_image" id="crop-ad-image">
  							<div class="source_logo avatar-view" title="upload ad image">
  									{% if adimg is defined %}
	  									{% if adimg is not null %}	
	  									 	<img src="{{adimg}}">
	  									{% endif %}
  									{% else %}
  										<img src="{{images}}/ad_default.png">
  									{% endif %}
  							</div>
  							{% include 'admin/advertise/image.php' with{'formUrl' : urlFor('admin_advertise_image_upload') } %}
  						</div>

  						<small style="margin-top:10px; display:block">Please choose an image with matching dimentions for the ad unit size for best results.</small>
  					</div>
		        </div>
			</div>
			

		</div>

{% endblock %}

{% block footer %}

	<script type="text/javascript">

		$(function() {

			$('.create_form')
				.destroy()
				.formValidation({
			        framework: 'bootstrap',
			        icon: {
					    valid: 'null',
					    invalid: 'null',
					    validating: 'null'
					},
			        fields: {

			        	type: {
			            	validators: {
			                    notEmpty: {
			                        message: 'Ad type is required'
			                    }
			                   
			                }
			            },

			            size: {
			            	validators: {
			                    notEmpty: {
			                        message: 'Ad size is required'
			                    }
			                }
			            },
			            code: {
			            	validators: {
			            		callback: {
		                            message: 'Ad code is required',
		                            callback: function(value, validator, $field) {
		                                var type = $('select[name=type]').val();
		                                return (type != 'code') ? true : (value !== '');
		                            }
			            		}
			            	}
			            },
			            url: {
			            	validators: {
			            		url: {
			                    	message: 'Ad url is not valid'
			                    },
			            		callback: {
		                            message: 'Ad url is required',
		                            callback: function(value, validator, $field) {
		                                var type = $('select[name=type]').val();
		                                return (type != 'image') ? true : (value !== '');
		                            }
			            		}

			            	}
			            },
			     
			        },
    			});

			$('#type').change(function() {

				var selected = $(this).find('option:selected');

				var value = selected.val();

				if(value === "") {
					$('.code').css('display', 'none');
					$('.image').css('display', 'none');
				}

				if(value === "code") {
					$('.code').css('display', 'block');
					$('.image').css('display', 'none');
				}

				if(value === "image") {
					$('.code').css('display', 'none');
					$('.image').css('display', 'block');
					$('#ad-type-input').val('image');
				}
			});

			if( $('#type').find('option:selected').val() === "code") {
				$('.code').css('display', 'block');
				$('.image').css('display', 'none');

			}
			if($('#type').find('option:selected').val() === "image") {
				$('.code').css('display', 'none');
				$('.image').css('display', 'block');
				$('#ad-type-input').val('image');
			}

			$('#size').change(function() {

				var selected = $(this).find('option:selected');

				var size = selected.val();
				var width = selected.data('awidth');
				var height = selected.data('aheight');
				
				$('#ad-size-input').val(size);

				$('#adWidth').val(width);
				$('#adHeight').val(height);
			});

			$(".select-image").click(function() {
		     	$("#avatarInput").click();
		 	});

		  	var img = new CropAvatar($('#crop-ad-image'), true);

		  	$('#crop-ad-image').click(function() {
	  		  	var adsize = $('#size').find('option:selected');
				$('#adWidth').val(adsize.data('awidth'));
				$('#adHeight').val(adsize.data('aheight'));
		  	});



		});

	</script>

{% endblock %}