<div class="bg-white shadow">

    <div class="widget-menu" style="margin-top:35px">


        {% macro homeCategory(category, baseUrl, images) %}

            <li {% if category.children|length %}class="dropdown"{% endif %}>

                <a data-target="#" {% if category.children|length %}class="ripple-effect dropdown-toggle disabled" data-toggle="dropdown"{% endif %} href="{{urlFor('category_single', {'slug' : category.slug, 'id' : category.id})}}">
            
                    {% if category.image is not null %}
                        {% if file_exists(constant('APP_PATH') ~category.image) %}    
                         <img alt="" src="{{baseUrl}}{{category.image}}" style="height:32px; margin-right:16px;">
                        {% endif %}
                    {% endif %}

                    <span style="padding-left: 16px;border-left: 1px solid #eaeaea;">{{category.name}}</span> 

                    {% if category.children|length %}
                       <span class="caret" data-toggle="dropdown" data-hover="dropdown"></span>
                    {%endif%} 
                </a>
                
                {% if category.children|length %}
                    <ul class="dropdown-menu">
                        {% for child in category.children %}
                            {{ _self.homeCategory(child, baseUrl, images) }}
                        {% endfor %}
                    </ul>
                {% endif %}

            </li>

        {% endmacro %}
    
        {% if categoryTree %}
            <ul class="nav sidebar-nav">
                {% for category in categoryTree %}
                    {{ _self.homeCategory(category, baseUrl, images) }}
                {% endfor %}
            </ul><!-- Sidebar divider -->
        {% endif %}

    </div><!-- /.widget -->

</div><!-- /col 4 - sidebar -->
