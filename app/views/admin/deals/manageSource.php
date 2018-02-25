{% extends 'includes/admin/default_two.php' %}

{% block title %} Mange Source {% endblock %}

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
	  <li class="active">Manage Source</li>
	</ol>

	<!-- END BREADCRUMB -->
{% endblock %}


{% block content %}

	<div class="widget">

		 <h3 class="section-title first-title" style="border-bottom:none !important"><i class="fa fa-table"></i> Source Manage
		<a href="{{urlFor('admin_deals_source_create')}}" class="btn btn-sm btn-primary pull-right"><i class="fa fa-plus" style="font-size:13px;"></i> New Source</a>
		 </h3>

		
		<div class="widget-content-white glossed">
		    <div class="padded">
				<table id="dealSource" class="table table-striped table-bordered table-hover datatable">
			        <thead>
			            	<th>Logo</th>
			                <th>Name</th>
			                <th>URL</th>
			                <th>Action</th>
			        </thead>

			 	<input type="hidden" name="csrf_token" value="{{csrf_token}}">
			       
			    </table>
	    	</div>
	    </div>

    </div>

{% endblock %}


{% block footer %}

	<script type="text/javascript">

		$(function() {

		    var table = $('#dealSource').DataTable({
		    	"pageLength": 10,
		    	"processing": true,
				"serverSide": true,
				"ajax": {
				    url: "{{urlFor('admin_deals_source_manage')}}",
				    type: "POST",
				    data: function(data) {
				    	data.csrf_token = $('input[name=csrf_token]').val();
				    },
				    error: function(){ 
						$(".source-error").html("");
						$("#dealSource").append('<tbody class="source-error"><tr><th colspan="6">No data found in the server</th></tr></tbody>');
						$("#dealSource_processing").css("display","none");
							
					}
				},
				fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
			        
			       $('td:eq(0)', nRow).html('<img width="32" height="32" src="'+aData[0]+'">');
			       $('td:eq(1)', nRow).html(aData[1]);
			       $('td:eq(2)', nRow).html(aData[2]);
			       $('td:eq(3)', nRow).html('<a onclick="addURL(this)" href="{{urlFor("admin_deals_source_update", {"id" : "" })}}" class="btn btn-xs btn-warning" data-id="'+aData[3]+'"><i class="fa fa-pencil-square-o"></i> Edit</a>');
			    }

		    });
		});

		function addURL(element) {
		  	$(element).attr('href', function(e) {
			   return this.href + $(this).data('id');
			});
		}

	</script>
{% endblock %}