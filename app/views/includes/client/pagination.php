<ul class="pagination" style="padding-left:15px;">	
	{% if pages > 1 %}

		{% set range = 5 %}

		{% if page != 1 %}
			<li><a href="?page=1">First</a></li>
		{% endif %}

		{% set prev = page - 1 %}

		{% if  page > 1 %}
			<li><a href="?page={{prev}}"><</a></li>
		{% endif %}

		{% for i in (page - range)..((page + range) + 1) %}

			{% if (i > 0) and (i <= pages) %}
				<li><a href="?page={{i}}" {% if page == i %} class="pagination-selected" {% endif %}>{{i}}</a></li>
			{% endif %}

		{% endfor %}

		{% if page != pages %}

			{% set  next = page + 1 %}

			<li><a href="?page={{next}}">></a></li>
			<li><a href="?page={{pages}}">Last</a></li>

		{% endif %}
		
	{% endif %}
</ul>