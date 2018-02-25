{% extends 'includes/admin/default_two.php' %}

{% block title %} Category Update {% endblock %}

{% block header %}{% endblock %}

{% block pageheader %}

	<div class="page-header page-header-green-sea">
	  <h1><i class="fa fa-sort"></i>Categories</h1>
	</div>
	<!-- END PAGE HEADER -->

	<ol class="breadcrumb">
	  <li><a href="{{urlFor('admin_home')}}">Home</a></li>
	  <li><a href="{{urlFor('admin_category_manager')}}">Category Manager</a></li>
	  <li class="active">Update</li>
	</ol>

	<!-- END BREADCRUMB -->
{% endblock %}

{% block content %}

<div class="row">

	<div class="col-xs-12 col-md-5">

		<div class="widget">
			<div class="widget-content-white glossed padded">

			<h3 class="form-title form-title-first"><i class="fa fa-terminal"></i>Update Category <a href="{{urlFor('admin_category_delete', {'id' : category.id })}}" class="btn btn-danger delete delete-btn pull-right"><i class="fa fa-trash-o"></i></a></h3>	

			 <form class="create_category" action="#" method="POST">
				<div class="form-group{% if errors.has('name') %} has-error{% endif %}">
					<label class="control-label" for="name">Category Name: </label>
					<input type="text" class="form-control" name="name" value="{% if request.post('name') %}{{request.post('name')}}{%else%}{{category.name}} {%endif%}">
					{% if errors.has('name') %} 
						<span class="help-block">{{errors.first('name') }}</span>
					{% endif %}
				</div>

				<div class="form-group{% if errors.has('description') %} has-error{% endif %}">
					<label class="control-label" for="description">Category Discription : </label>
					
					<textarea id="dealDis" class="form-control" name="description" style="width: 100%; height: 100px">{% if request.post('description') %}{{request.post('description')}}{%else%}{{category.dis}}{%endif%}</textarea>
					
					{% if errors.has('description') %} 
						<span class="help-block">{{errors.first('description') }}</span>
					{% endif %}
				</div>

				<input type="hidden" name="csrf_token" value="{{csrf_token}}">
			</form>

			<div class="form-group">
                <label class="control-label" for="icon">Category Image: (Optional)</label>

                <div class="deals_source_image" id="category-img" style="width:150px; height:150px; margin-top:10px;">

					<div class="source_logo avatar-view" title="Change the Image">
						<img src="{{category.getImage(baseUrl, images, true)}}">
					</div>
					{% set url = urlFor('admin_deals_update_category_image', {'id' : category.id}) %}

					{% include 'admin/categories/categoryImage.php' with {'formUrl': url} %}
				</div>

				<small style="margin-top:7px; display:block">Image Size : <b>{{config.category.width}} * {{config.category.height}}</b></small>
            </div>

			<hr style="border-bottom: 1px solid #ddd;-webkit-box-shadow: 0px 1px 0px 0px #ffffff;box-shadow: 0px 1px 0px 0px #ffffff;">
			<button class="btn btn-primary" id="create-category">Update</button>

			<span class="ajax-loader pull-right"></span>

			</div>
		</div>

	</div>

</div>

{% endblock %}


{% block footer %}

<script type="text/javascript">

$(function(){

	$('.create_category').formValidation({
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
                        message: 'Category name is required'
                    },
                    stringLength: {
               			max: 250,
		                message: 'Name must be a maximum of 250 characters',
                   	}
                   
                }
            },
            description: {
            	validators: {
                    notEmpty: {
                        message: 'Category Discription is required'
                    }
                }
            },
     
        }
	});

	$('#create-category').click(function(event) {
		event.preventDefault();
		$('.ajax-loader').css('display', 'block');
		$(".create_category").submit();
		if($('.create_category').data('formValidation').validate().isValid()){

			$.ajax({
		        type: "POST",
		        data: $(".create_category").serialize() + "&id={{category.id}}",
		        url: "{{urlFor('admin_category_update_post')}}",
		        success: function(data) {
		        	 obj = JSON.parse(data);
		        	 if (obj.status == 200) {
		        	 	$('.ajax-loader').css('display', 'none');
		        	 	swal({ 
						   title: "Success",
						   text: "Category updated successful",
						    type: "success" 
						  },
						  function(){
						    location.reload();
						});
		        	 }
		        },
		        error: function() {
		        	console.log('Server Error occured');
		        }
			});

		}
	});

	$('.delete-btn').click(function(e) {
			e.preventDefault();
			var url = $(this).attr('href');
			swal({   
				title: "Are you sure?",   
				text: "Please note that deals in this category will be unlinked upon deletion. Sub categories of this category will be deleted unlinking deals on sub categories.",   
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

	$(".select-image").click(function() {
     	$("#avatarInput").click();
 	});


	var catimg = new CropAvatar($('#category-img'));

});

</script>

{% endblock %}
