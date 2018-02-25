{% extends 'includes/admin/default_two.php' %}

{% block title %} Create Deal {% endblock %}

{% block header %}
<link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css" rel="stylesheet" />
{% endblock %}

{% block pageheader %}

	<div class="page-header page-header-green-sea">
	  <h1><i class="fa fa-sort"></i>Deals</h1>
	</div>
	<!-- END PAGE HEADER -->

	<ol class="breadcrumb">
	  <li><a href="{{urlFor('admin_home')}}">Home</a></li>
	  <li><a href="{{urlFor('admin_deals_manage')}}">Deals</a></li>
	  <li class="active">Create Deal</li>
	</ol>

	<!-- END BREADCRUMB -->
{% endblock %}


{% block content %}

<div class="row">

	<form class="add_deal_form" action="{{urlFor('admin_deals_create')}}" method="POST">

	<div class="col-md-6">
			<div class="widget">
				<div class="widget-content-white glossed padded">

					 <h3 class="form-title form-title-first"><i class="fa fa-terminal"></i>Create Deal</h3>

					<div class="form-group{% if errors.has('source') %} has-error{% endif %}">
						<label class="control-label" for="source">Deal source : </label>

						<select name="source" class="form-control">
							<option value=""></option>
							{% for source in sources %}
							    <option value="{{source.id}}" {% if (request.post('source') == source.id) %}selected {%endif%}>{{source.name}}</option>
							{% endfor %}
						</select>
						
						{% if errors.has('source') %} 
							<span class="help-block">{{errors.first('source') }}</span>
						{% endif %}
					</div>

					<div class="form-group{% if errors.has('category') %} has-error{% endif %}">
						<label class="control-label" for="category">Deal category : </label>

						{% macro recursiveCategory(category, postCat, level = 0) %}

							{% set isSet = '' %}

							{% for catId in postCat %}
								{% if (catId == category.id) %}
									{% set isSet = 'selected' %}
								{% endif %}
							{% endfor %}

							<option value="{{category.id}}"{{isSet}}>
							{% for i in range(0, level) %}
								{% if i != 0 %}
									-
								{% endif %}
							{% endfor %}
							{{category.name}}
							</option>

							{% if category.children|length %}
									{% for child in category.children %}
						               {{ _self.recursiveCategory(child, postCat, level + 1) }}
						            {% endfor %}
							{% endif %}

						{% endmacro %}
						
						<select id="category" name="category[]" class="form-control" multiple>
							<option value=""></option>
							{% if categories %}
								{% for category in categories %}
						            {{ _self.recursiveCategory(category, request.post('category')) }}
						        {% endfor %}
							{% endif %}
						</select>
						
						{% if errors.has('category') %} 
							<span class="help-block">{{errors.first('category') }}</span>
						{% endif %}
					</div>

					<div class="form-group{% if errors.has('title') %} has-error{% endif %}">
						<label class="control-label" for="title">Deal title : </label>

						<input type="text" class="form-control" name="title" value="{% if request.post('title')%}{{request.post('title')}}{%endif%}">
						
						{% if errors.has('title') %} 
							<span class="help-block">{{errors.first('title') }}</span>
						{% endif %}
					</div>

					<div class="form-group{% if errors.has('description') %} has-error{% endif %}">
						<label class="control-label" for="description">Deal description : </label>
						
						<textarea id="dealDis" class="form-control" name="description">{% if request.post('description') %}{{request.post('description')}}{%endif%}</textarea>
						

						{% if errors.has('description') %} 
							<span class="help-block">{{errors.first('description') }}</span>
						{% endif %}
					</div>

					<div class="form-group{% if errors.has('price') %} has-error{% endif %}">
						<label class="control-label" for="price">Deal Price: </label>

						<input type="text" class="form-control" name="price" value="{% if request.post('price')%}{{request.post('price')}}{%endif%}">
						
						{% if errors.has('price') %} 
							<span class="help-block">{{errors.first('price') }}</span>
						{% endif %}
					</div>

					<div class="form-group{% if errors.has('discounts') %} has-error{% endif %}">
						<label class="control-label" for="discount">Discount Rate %: (optional) </label>

						<input type="text" class="form-control" name="discount" value="{% if request.post('discount')%}{{request.post('discount')}}{%endif%}">
						
						{% if errors.has('price') %} 
							<span class="help-block">{{errors.first('discount') }}</span>
						{% endif %}
					</div>

					<div class="form-group{% if errors.has('url') %} has-error{% endif %}">
						<label class="control-label" for="url">Deal Url : </label>

						<input type="text" class="form-control" name="url" value="{% if request.post('url')%}{{request.post('url')}}{%endif%}">
						
						{% if errors.has('price') %} 
							<span class="help-block">{{errors.first('url') }}</span>
						{% endif %}
					</div>

					<div class="form-group{% if errors.has('type') %} has-error{% endif %}">
						<label class="control-label" for="type">Deal type : </label>

						<select name="type" id="deal_type" class="form-control">
							<option value=""></option>
							<option value="fixed" {% if (request.post('type') == "fixed") %}selected {% endif %}>Fixed Deal</option>
							<option value="count" {% if (request.post('type') == "count") %}selected {% endif %}>Count Down Deal</option>
						</select>
						
						{% if errors.has('type') %} 
							<span class="help-block">{{errors.first('type') }}</span>
						{% endif %}
					</div>

					<div class="form-group{% if errors.has('expirey') %} has-error{% endif %}">

						<label class="control-label" for="expirey">Deal expirey : </label>

						<div class='input-group date' id='datetimepicker1'>
		                    <input type='text' name="expirey" class="form-control" {% if request.post('expirey') %}value="{{request.post('expirey')}}"{%endif%}/>
		                    <span class="input-group-addon">
		                        <span class="glyphicon glyphicon-calendar"></span>
		                    </span>
		                </div>

						{% if errors.has('expirey') %} 
							<span class="help-block">{{errors.first('expirey') }}</span>
						{% endif %}

					</div>

					<input type="hidden" name="csrf_token" value="{{csrf_token}}">

					<div class="form-group deal-form-but">
						<button type="submit" class="btn btn-primary">Create</button>
						<span class="deal-featured"><input type="checkbox" name="featured" {% if request.post('featured') == 'on' %} checked {% endif %}> Featured</span>
					</div>

				</div>
			</div>
	</div>

	<div class="col-xs-12 col-sm-12 col-md-6">
		<div class="widget">
			<div class="widget-content-white glossed padded">
				 <h3 class="form-title form-title-first"><i class="fa fa-image"></i>Images</h3>
				 <div id="dealImages" class="dropzone"></div>
				 <small style="margin-top:7px; display:block">Image Size : <b>{{config.deal.image.width}} * {{config.deal.image.height}}</b></small>
			</div>

		</div>
	</div>


	</form>	

	{% if request.post('deal_images') %}
		{% for dimage in request.post('deal_images') %}
			<input type="hidden" name="tmp_deal_img[]" class="tmp_deal_img" value="{{dimage}}"> 
		{% endfor %}
	{% endif %}


</div>

{% endblock %}


{% block footer %}

	<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script>

	<script type="text/javascript">

	$('#category').select2();

	$(document).ready(function() {
	    $('#dealDis').summernote({
			height: 300,
			minHeight: null,
			maxHeight: null,
			toolbar: [
			    ['style', ['bold', 'italic', 'underline', 'clear']],
			    ['fontsize', ['fontsize']],
			    ['color', ['color']],
			    ['para', ['ul', 'ol', 'paragraph']],
			]
		});
	});


	$(function () {

		$('.add_deal_form')
			.formValidation({
		        framework: 'bootstrap',
		        icon: {
				    valid: 'null',
				    invalid: 'null',
				    validating: 'null'
				},
		        fields: {
		        	source: {
		            	validators: {
		                    notEmpty: {
		                        message: 'Source is required'
		                    }
		                   
		                }
		            },
		            'category[]': {
		            	validators: {
		                    notEmpty: {
		                       message: 'Please select 1 or more categories.'
		                    }
		                }
		            },
		            title: {
		            	validators: {
		                    notEmpty: {
		                        message: 'Title is required',
		                    },
		                    stringLength: {
		               			max: 250,
		                        message: 'Title must be a maximum of 250 characters',
		                   	},
		                   	stringLength: {
		               			min: 20,
		                        message: 'Title must be a minimum of 20 characters',
		                   	}
		                }
		            },
		            price: {
		            	validators: {
		                    notEmpty: {
		                        message: 'Deal price is required'
		                    },
		                    numeric: {
		                    	message: 'Deal price must be valid numeric characters'
		                    }
		                }
		            },
		            discount: {
		            	validators: {
		                    numeric: {
		                    	message: 'Discount must be valid numeric characters'
		                    },
		                    between: {
		                        min: 0,
		                        max: 100,
		                        message: 'Discount must be between 1% and 100%'
		                    }
		                }
		            },
		            url: {
		            	validators: {
		            		notEmpty: {
		                        message: 'Deal url is required'
		                	},
		                    url: {
		                    	message: 'Deal url is not valid'
		                    }
		                }
		            },

		            type: {
		            	validators: {
		                    notEmpty: {
		                        message: 'Deal type is required'
		                    }
		                }
		            },
		            expirey: {
		            	validators: {
		            		callback: {
	                            message: 'Deal expirey is required',
	                            callback: function(value, validator, $field) {
	                                var type = $('.add_deal_form').find('[name="type"]:checked').val();
	                                return (type != 'count') ? true : (value !== '');
	                            }
		            		}
		            	}
		            }
		     
		        }
    	}).on('change', '[name="type"]', function(e) {

            $('.add_deal_form').formValidation('revalidateField', 'expirey');

        }).on('success.field.fv', function(e, data) {
            if (data.field === 'expirey') {
                var type = $('.add_deal_form').find('[name="type"]:checked').val();
                if (type !== 'count') {
                    data.element.closest('.form-group').removeClass('has-success');
                    data.element.data('fv.icon').hide();
                }
            }
        });


        $('#datetimepicker1').datetimepicker({
          	format: 'DD-MM-YYYY HH:mm',        
        });

        Dropzone.autoDiscover = false;
		Dropzone.options.myAwesomeDropzone = false;

		$("div#dealImages").dropzone({ 
			url: "{{urlFor('admin_deals_upload')}}",
			method: 'POST',
			addRemoveLinks: true,
			maxFiles:5,
			acceptedFiles: "image/*",
			maxFilesize: 2,
			thumbnailWidth: 120,
		    thumbnailHeight:120,
		    uploadMultiple:false,
		    parallelUploads: 1,
		    dictDefaultMessage: "Drag or select deal images here",

		    init: function() {
		    	var thisDropzone = this;

		    	$(".tmp_deal_img").each(function(index, value) {

				    var mockFile = { name: $(this).val(), size: 2000, type: 'image/jpeg' };

				    thisDropzone.options.maxFiles = thisDropzone.options.maxFiles - 1;

				    thisDropzone.options.addedfile.call(thisDropzone, mockFile);
    				thisDropzone.options.thumbnail.call(thisDropzone, mockFile, '{{tmp}}'+$(this).val()+'_thumbnail.jpg');

    				thisDropzone.options.complete.call(thisDropzone, mockFile);
    				thisDropzone.emit("complete", mockFile);

    				$(mockFile.previewElement).append('<input type="hidden" class="imageName" name="deal_images[]" value="'+$(this).val()+'"/>');
				});
		    },

			maxfilesexceeded : function(file) 
			{
				this.removeFile(file);
				swal("ERROR", "Images limit exceeded, you cannot upload more than 5 images","error");
			},
			sending: function(file, xhr, formData) 
			{	
		        formData.append("csrf_token", $('[name=csrf_token').val());
		    },
		    success: function(file, response)
		    {	
		        obj = JSON.parse(response);
		        if(obj.error) {
		        	swal("ERROR", obj.msg ,"error");
		        	this.removeFile(file);
		        } else {
		        	file.previewElement.querySelector("img").src =  '{{tmp}}'+obj.filename+'_thumbnail.jpg';
		        	$(file.previewTemplate).append('<input type="hidden" class="imageName" name="deal_images[]" value="'+obj.filename+'"/>');		
		        }
		       		
		    },
		    removedfile: function(file) 
		    {
		    	var imageid = $(file.previewTemplate).children('.imageName').val();

                $.ajax({
                    type: 'POST',
                    url: "{{urlFor('admin_deals_upload_tmp_image_delete')}}",
                    data: {'id' : imageid, 'csrf_token' : $('input[name=csrf_token]').val() },
	    		});

	    		var _ref;
	    		return (_ref = file.previewElement) != 'null' ? _ref.parentNode.removeChild(file.previewElement) : void 0;   
            }
        });

    });

	</script>

{% endblock %}