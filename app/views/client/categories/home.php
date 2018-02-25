{% extends 'includes/client/default.php' %}

{% block title %} {{category.name}} {% endblock %}

{% block header %}{% endblock %}

{% block content %}

{% import "client/includes/deal_design_2.php" as catdeals %}

<section class="container" id="page">
    <div class="row">

    	<div class="col-sm-8">

		    <div class="inner-side shadow search_bar">
	        <div class="row">
	            <div class="col-sm-12 clearfix">

            	 <form action="{{urlFor('filter')}}" method="GET" id="search_form">

            	 		<input type="hidden" name="category" value="{{category.id}}">

            	 		<div class="col-sm-6">
							<input type="text" name="q" placeholder="Search" class="form-control">
            	 		</div>

                        <div class="col-sm-3">

                        	<select name="source" class="form-control" id="source">
                                <option value="">Source</option>
                               	{% for source in sources %}
									 <option value="{{source.id}}"{%if (request.get('source') == source.id ) %} selected{% endif%}>{{source.name}}</option>
                               	{% endfor %}
                            </select>
                            
                        </div><!-- /.col 3 -->

                        <div class="col-sm-3">

                        	<select name="type" class="form-control" id="type">
                                <option value="">Type</option>
                               	<option value="fixed" {%if (request.get('type') == "fixed") %} selected{% endif%}>Fixed deals</option>
                               	<option value="count" {%if (request.get('type') == "count") %} selected{% endif%}>Countdown deals</option>
                            </select>
                            
                        </div><!-- /.col 3 -->

                    </form>
	                 
	            </div>
	       </div>
		</div>


		    <div class="inner-side shadow search_bar">
		        <div class="row">
		            <div class="col-sm-12 clearfix">
		                <div class="box-icon">

							{% if category.image is not null %}
		                    <div class="icon-wrap" style="margin-top:0px;">
		                    	<img src="{{category.getImage(baseUrl, images, true)}}" width="32px" height="32px">
		                    </div>
		                    {% endif %}

		                    <div class="text">

		                        <h4>{{category.name}}</h4>
		                        <p>{{category.dis}}</p>
		                    
		                    </div>
		                </div>
		            </div>
		        </div>
		    </div>

			<!-- END HEADER BOX -->

		    <div class="clearfix"></div>

		    {% for deal in deals %}
		        {{ catdeals.homeDeals(deal,  baseUrl, images) }}
		    {% endfor %}

		    {% include 'client/categories/includes/pagination.php' %}

    	</div>

    	<!-- END COL-8 -->

    	<!-- COL-4 -->

    	<div class="col-sm-4 sidebar">
            <div class="inner-side shadow">
            	{% include 'client/includes/sidebar.php' %}
			</div>
		</div>

	</div>
</section>

{% endblock %}

{% block footer%}

    <script type="text/javascript">

    $('#source').on('change', function() {
        $('#search_form').submit();
    });
    
    $('#type').on('change', function() {
        $('#search_form').submit();
    });

    </script>

{% endblock%}


