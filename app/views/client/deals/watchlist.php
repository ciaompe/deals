{% extends 'includes/client/default.php' %}

{% block title %} Watch List {% endblock %}

{% block header %}

    <link rel='stylesheet' href='{{css}}/framework/alert/alert.css'>

{% endblock %}

{% block content %}

<div class="row">

    <ul class="list-group">

		{% for deal in deals %}
        
        <li class="col-md-12 list-group-item">
            <div class="media col-md-2">
                <img class="media-object img-rounded img-responsive"  src="{{deal.getDealImage(baseUrl, images, 'small', true)}}">
            </div>

            <div class="col-md-10">
                <h4 class="list-group-item-heading">{{deal.limiText(deal.title, 50)}}</h4>
                <section>
                	<span><i class="fa fa-thumbs-up {% if deal.liked(auth.id) %}liked{% endif %}"></i> <b>{{deal.likesCount()}}</b></span>
                	<span><i class="fa fa-thumbs-down {% if deal.disLiked(auth.id) %}disliked{% endif %}"></i> <b>{{deal.dislikesCount()}}</b></span>
                    
                     <a href="{{urlFor('deal_remove_list', {'id' : deal.id})}}?outside=false" class="btn btn-danger pull-right btn-xs delete"><i class="fa fa-times"></i></a>
                      <a class="btn btn-primary pull-right btn-xs" href="{{ urlFor('deal_single_page', {'slug' : deal.dealUrl(), 'id' : deal.id} ) }}">Open</a>
                </section>
            </div>

        </li>

        {% else %}

        <li>Watch list is empty</li>

		{% endfor %}

    </ul>

	{% include 'includes/client/pagination.php' %}

</div>

{% endblock %}

{% block footer %}

    <script src='{{js}}/framework/alert/alert.js'></script>

    <script type="text/javascript">

    $('.delete').click(function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            swal({   
                title: "Are you sure?",
                text: "want to delete this deal from your Watch list ?",   
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

