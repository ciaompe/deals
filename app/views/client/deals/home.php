{% extends 'includes/client/default.php' %}

{% block title %} Deals {% endblock %}

{% block header %}{% endblock %}

{% block content %}

{% import "client/includes/deal_design_1.php" as dealsAll %}

<section id="page">
	<div class="mTop-30">
		<div class="row">

		    {% for deal in deals %}
		        {{ dealsAll.homeDeals(deal,  baseUrl, images) }}
		    {% else %}
		        <p>Deals are empty</p>
		    {% endfor %}

		</div>
		<div class="row">
			 {% include 'includes/client/pagination.php' %}
		</div>
	</div>
</section>

{% endblock %}

