{% extends 'includes/admin/default_two.php' %}

{% block title %} Manage Deals {% endblock %}

{% block header %}
{% endblock %}

{% block pageheader %}

	<div class="page-header page-header-green-sea">
	  <h1><i class="fa fa-sort"></i>Deals</h1>
	</div>
	<!-- END PAGE HEADER -->

	<ol class="breadcrumb">
	  <li><a href="{{urlFor('admin_home')}}">Home</a></li>
	  <li><a href="{{urlFor('admin_deals_manage')}}">Deals</a></li>
	  <li class="active">Manage Deal</li>
	</ol>

	<!-- END BREADCRUMB -->
{% endblock %}

{% block content %}

	<div class="widget">

		<h3 class="section-title first-title" style="border-bottom:none !important"><i class="fa fa-table"></i> Deal Manage
		<a href="{{urlFor('admin_deals_create')}}" class="btn btn-sm btn-primary pull-right"><i class="fa fa-plus" style="font-size:13px;"></i> New Deal</a>
		</h3>
		
		<div class="widget-content-white glossed">
		    <div class="padded">

				<table id="deals" class="table table-striped table-bordered table-hover datatable">
				        <thead>
				            <th>Thumbnail</th>
				            <th>Source</th>
				            <th>Category</th>
				            <th>Title</th>
				            <th>Expire At</th>
				            <th>Action</th>
				        </thead>

				 		<input type="hidden" name="csrf_token" value="{{csrf_token}}">
				</table>

				<div class="row table-foot-bottom">

					<div class="col-md-2">
						<select class="form-control" id="source" name="source">
				            <option value="">Sort by source</option>
				            {% for source in sources %}
								<option value="{{source.id}}">{{source.name}}</option>
							{% endfor %}
				        </select>
					</div>

					<div class="col-md-2">
						{% macro recursiveCategory(category, level = 0) %}

							<option value="{{category.id}}">
							{% for i in range(0, level) %}
								{% if i != 0 %}
									-
								{% endif %}
							{% endfor %}
							{{category.name}}
							</option>

							{% if category.children|length %}
									{% for child in category.children %}
						               {{ _self.recursiveCategory(child, level + 1) }}
						            {% endfor %}
							{% endif %}

						{% endmacro %}

		         		<select class="form-control" id="category" name="category">
		                	<option value="">Sort by category</option>
		                	{% if categories %}
								{% for category in categories %}
						            {{ _self.recursiveCategory(category) }}
						        {% endfor %}
							{% endif %}
		                	
		                </select>

					</div>

					<div class="col-md-2">
						<select class="form-control" id="type" name="type">
				           <option value="">Sort by type</option>
				           <option value="featured">Featured Deal</option>
							<option value="fixed">Fixed Deal</option>
							<option value="count">Count Down Deal</option>
				        </select>
					</div>

				</div>

			</div>
		</div>

	</div>

{% endblock %}


{% block footer %}

<script src="https://cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js"></script>

<script type="text/javascript">

		$(function() {

		    var table = $('#deals').DataTable({
		    	"pageLength": 10,
		    	"processing": true,
				"serverSide": true,
				"ajax": {
				    url: "{{urlFor('admin_deals_manage')}}",
				    type: "POST",
				    data: function(data) {
				    	data.csrf_token = $('input[name=csrf_token]').val();
				    	data.source = $('#source option:selected').val();
				    	data.dtype = $('#type option:selected').val();
				    	data.category = $('#category option:selected').val();
				    },
				    error: function(){ 
						$(".deals-error").html("");
						$("#deals").append('<tbody class="deals-error"><tr><th colspan="9">No data found in the server</th></tr></tbody>');
						$("#deals_processing").css("display","none");
					}
				},
				fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
			        
			       $('td:eq(0)', nRow).html('<img width="64" height="48" src="'+aData[0]+'">');
			       $('td:eq(1)', nRow).html(aData[1]);
			       $('td:eq(2)', nRow).html(aData[2]);
			       $('td:eq(3)', nRow).html(aData[3]);
			       $('td:eq(4)', nRow).html(aData[7]);
			       $('td:eq(5)', nRow).html('<a onclick="addURL(this)" href="{{urlFor("admin_deals_update", {"id" : ""})}}" class="btn btn-xs btn-warning" data-id="'+aData[8]+'"><i class="fa fa-pencil-square-o"></i> Edit</a>');
			    
			       if (aData[9] == 1) {
			       		$(nRow).addClass('danger');
			       }

			    }
		    });

			$('#source').on( 'change', function () {
			    table.ajax.reload();
			});

			$('#category').on( 'change', function () {
			    table.ajax.reload();
			});

			$('#type').on( 'change', function () {
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