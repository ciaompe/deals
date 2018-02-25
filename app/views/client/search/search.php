{% extends 'includes/client/default.php' %}

{% block title %} Search {% endblock %}

{% block header %}{% endblock %}

{% block content %}

{% import "client/includes/deal_design_2.php" as searchDeals %}

<section class="container" id="page">

<div class="inner-side shadow search_bar">
	        <div class="row">
	            <div class="col-sm-12 clearfix">

            	 <form action="{{urlFor('filter')}}" method="GET" id="search_form">

            	 		<div class="col-sm-6">
							<input type="text" name="q" placeholder="Search" class="form-control" {%if (request.get('q') != "") %}value="{{request.get('q')}}"{%endif%}>
            	 		</div>

                        <div class="col-sm-2">

                        	<select name="source" class="form-control" id="source">
                                <option value="">Source</option>
                               	{% for source in sources %}
									 <option value="{{source.id}}"{%if (request.get('source') == source.id ) %} selected{% endif%}>{{source.name}}</option>
                               	{% endfor %}
                            </select>
                            
                        </div><!-- /.col 3 -->

                        <div class="col-sm-2">

                        	<select name="type" class="form-control" id="type">
                                <option value="">Type</option>
                               	<option value="fixed" {%if (request.get('type') == "fixed") %} selected{% endif%}>Fixed deals</option>
                               	<option value="count" {%if (request.get('type') == "count") %} selected{% endif%}>Countdown deals</option>
                            </select>
                            
                        </div><!-- /.col 3 -->

                        <div class="col-sm-2">

                            {% macro recursiveCategory(category, postCat, level = 0) %}

                                {% set isSet = '' %}
                                
                                {% if (postCat == category.id) %}
                                    {% set isSet = 'selected' %}
                                {% endif %}

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

                            <select name="category" class="form-control" id="category">
                                <option value="">Category</option>
                               {% if categoryTree %}
                                    {% for category in categoryTree %}
                                        {{ _self.recursiveCategory(category, request.get('category')) }}
                                    {% endfor %}
                                {% endif %}
                            </select>

                        </div><!-- /.col 3 -->

                    </form>
	                 
	            </div>
	       </div>
		</div>

	<div class="row">

		<div class="col-sm-8">

		<!-- END HEADER BOX -->

		<div class="clearfix"></div>

	    {% for deal in deals %}
	        {{searchDeals.homeDeals(deal,  baseUrl, images) }}
	    {% else %}
	       <h4>Oops! We did not find any deals matching your search, try again with another query.</h4>
	    {% endfor %}

	    {% include 'includes/client/pagination.php' %}

	    </div>

	    <!-- END COL-8 -->

    	<!-- COL-4 -->

    	<div class="col-sm-4 sidebar">
            <div class="inner-side shadow" style="margin-top:0;">
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

    $('#category').on('change', function() {
        $('#search_form').submit();
    });

    </script>

{% endblock%}

