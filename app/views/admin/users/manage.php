{% extends 'includes/admin/default_two.php' %}

{% block title %} Manage Users {% endblock %}

{% block header %}
{% endblock %}


{% block pageheader %}

	<div class="page-header page-header-green-sea">
	  <h1><i class="fa fa-sort"></i>Deals</h1>
	</div>
	<!-- END PAGE HEADER -->

	<ol class="breadcrumb">
	  <li><a href="{{urlFor('admin_home')}}">Home</a></li>
	  <li><a href="{{urlFor('admin_user_manage')}}">Users</a></li>
	  <li class="active">Manage User</li>
	</ol>

	<!-- END BREADCRUMB -->
{% endblock %}

{% block content %}

	<div class="widget">

		<h3 class="section-title first-title" style="border-bottom:none !important"><i class="fa fa-table"></i> User Manage
		<a href="{{urlFor('admin_create_user')}}" class="btn btn-sm btn-primary pull-right"><i class="fa fa-plus" style="font-size:13px;"></i> New User</a>
		</h3>
		
		<div class="widget-content-white glossed">
		    <div class="padded">

				<table id="users" class="table table-striped table-bordered table-hover datatable">
			        <thead>
			            	<th>Avatar</th>
			                <th>Name</th>
			                <th>Email</th>
			                <th>Type</th>
			                <th>Action</th>
			        </thead>

			 	<input type="hidden" name="csrf_token" value="{{csrf_token}}">
			       
			    </table>

			    <div class="row table-foot-bottom">
					<div class="col-md-2">
						<select id="userType" name="userType" class="form-control">
	                		<option value="">Sort by user type</option>
	                		<option value="admin">Admin</option>
	                		<option value="staff">Staff</option>
	                		<option value="ban">Banned</option>
	                	</select>
					</div>
			    </div>

			</div>
		</div>
	</div>

{% endblock %}

{% block footer %}

	<script type="text/javascript">

		$(function() {

		    var table = $('#users').DataTable({
		    	"pageLength": 10,
		    	"processing": true,
				"serverSide": true,
				"ajax": {
				    url: "{{urlFor('admin_user_manage')}}",
				    type: "POST",
				    data: function(data) {
				    	data.csrf_token = $('input[name=csrf_token]').val();
				    	data.userType = $('#userType option:selected').val();
				    },
				    error: function(){ 
						$(".users-error").html("");
						$("#users").append('<tbody class="users-error"><tr><th colspan="6">No data found in the server</th></tr></tbody>');
						$("#users_processing").css("display","none");
							
					}
				},
				fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
			        
			       $('td:eq(0)', nRow).html('<img width="32" height="32" src="'+aData[0]+'">');
			       $('td:eq(1)', nRow).html(aData[1]);
			       $('td:eq(2)', nRow).html(aData[2]);
			       $('td:eq(3)', nRow).html('<span class="label label-default">'+aData[4]+'</span>');
			       $('td:eq(4)', nRow).html('<a onclick="addURL(this)" href="{{urlFor("admin_users_update", {"id" : "" })}}" class="btn btn-xs btn-warning" data-id="'+aData[5]+'"><i class="fa fa-pencil-square-o"></i> Edit</a>');
					
				   if (aData[6] == true) {
					  $(nRow).addClass('danger');
				   }
				   if (aData[7] == false) {
					  $(nRow).addClass('warning');
				   }
			    }
		    });
		 
		    $('#userType').on( 'change', function () {
			    table.ajax.reload();
			});

		});

		function addURL(element) {
		  	$(element).attr('href', function(e) {

			   return this.href + $(this).data('id');
			});
		}

	</script>

{% endblock %}