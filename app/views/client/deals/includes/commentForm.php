<h4 class="margin-bottom-20">Post a review</h4>

{% if auth %}
<form action="{{urlFor('deal_add_comment', {'id' : deal.id})}}" class="comments" id="comment_form" method="post" name="comment-form">
    <fieldset>
        <div class="space-xs">
            <div class="form-group{% if errors.has('comment') %} has-error{% endif %}{% if spam %} has-error{% endif %}">
                    
                    <textarea name="comment" class="form-control" cols="0" rows="5" placeholder="Write a review here">{% if request.post('comment') %}{{request.post('comment')}}{% endif %}</textarea>
                    {% if errors.has('comment') %} 
                            <span class="help-block">{{errors.first('comment') }}</span>
                    {% endif %}
                    {% if spam %}
                            <span class="help-block">Spam detected, Publish again and verify your humanity</span>
                    {% endif %}

            </div>
            {% if spam %}
                {% include 'client/deals/includes/captcha.php' %}
            {% endif %}
                
            <input type="hidden" name="csrf_token" value="{{csrf_token}}">
            <input type="text" name="user" class="off">

        </div>
        
        <button class="btn btn-green btn-raised ripple-effect" type="submit" id="comment-publish">Publish</button>
    </fieldset>
</form>
{% else %}

<p>Please <a href="{{urlFor('auth_login')}}?r={{ urlFor('deal_single_page', {'slug' : deal.dealUrl(), 'id' : deal.id} ) }}">Sign in</a> or <a href="{{urlFor('auth_register')}}">Sign up</a> to make a review</p>

{% endif %}

{% if comment_count %}
<br><br>
<h4><b>{{comment_count}}</b> Reviews Founded</h4>
<hr>

{% for comment in comments %}

    <!-- comment start -->
    <div class="comment clearfix">
        <div class="comment-avatar">
            <img alt="avatar" src="{{comment.user.getAvatar(baseUrl, images, true)}}">
        </div>
        <header>
            <h3>{{comment.user.getName()}} 
                {% if (comment.user.id == auth.id) or (auth.isBackend()) %}
                <span class="pull-right">
                    <a href="#" class="review-btn edit-btn" data-body="{{comment.body}}" data-id="{{comment.id}}"><i class="ti-pencil-alt"></i></a>
                    {% if auth.isBackend() %}
                        <a href="{{urlFor('deal_delete_comment', {'id': comment.id})}}" class="review-btn delete-comment"><i class="ti-trash"></i></a>
                    {% endif %}
                </span>
                {% endif %}
            </h3>
            <div class="comment-meta">{{comment.createdAt()}}</div>
        </header>
        <div class="comment-content">
            <div class="comment-body clearfix">
                <p class="comment-wrapper-{{comment.id}}">{{comment.body}}</p>
            </div>
        </div>

    </div><!-- comment end -->

{% endfor %}


{% include 'client/deals/includes/pagination.php' %}

{% endif %}

{% include 'client/deals/includes/editComment.php' %}