{% extends 'includes/admin/default_two.php' %}

{% block title %} Advertise {% endblock %}

{% block header %}{% endblock %}

{% block pageheader %}

	<div class="page-header page-header-green-sea">
	  <h1><i class="fa fa-sort"></i>Advertise</h1>
	</div>
	<!-- END PAGE HEADER -->

	<ol class="breadcrumb">
	  <li><a href="{{urlFor('admin_home')}}">Home</a></li>
	  <li class="active">Advertise</li>
	</ol>

	<!-- END BREADCRUMB -->
{% endblock %}

{% block content %}

	<div class="widget">
		<h3 class="section-title first-title" style="border-bottom:none !important"><i class="fa fa-table"></i> 
		Ad Units 
		<a href="{{urlFor('admin_advertise_new')}}" class="btn btn-sm btn-primary pull-right"><i class="fa fa-plus" style="font-size:13px;"></i> New Unit</a></h3>
		<div class="widget-content-white glossed">
		    <div class="padded">
				<table id="users" class="table table-striped table-bordered table-hover">
			        <thead>
			            	<th>Size</th>
			                <th>Type</th>
			                <th>Created At</th>
			                <th>Action</th>
			        </thead>

			        <tbody>
			        	
			        	{% for unit in units%}
							<tr>
								<td>{{unit.adsize.name}}</td>
								<td>{{unit.type}}</td>
								<td>{{unit.created_at.diffForHumans()}}</td>
								<td><a href="{{urlFor('admin_advertise_update', {'id' : unit.id})}}" class="btn btn-primary btn-xs" style="margin-right:5px;"><i class="fa fa-pencil-square-o"></i> Edit</a><a href="{{urlFor('admin_advertise_delete', {'id' : unit.id})}}" class="btn btn-danger btn-xs delete"><i class="fa fa-trash-o"></i> delete</a></td>
							</tr>
						{% else %}
							<tr>
								No data in server
							</tr>
			        	{% endfor %}

			        </tbody>
			       
			    </table>
		    </div>
		</div>
	</div>


{% endblock %}

{% block footer %}
		
	<script type="text/javascript">

		$('.delete').click(function(e) {
			e.preventDefault();
			var url = $(this).attr('href');
			swal({   
				title: "Are you sure?",   
				text: "You will not be able to recover this Ad again",   
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

	</script>

{% endblock %}