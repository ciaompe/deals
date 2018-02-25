{% extends 'email/default.php' %}

{% block container %}

<p style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; font-weight: normal; margin: 0 0 10px; padding: 0;">Congratulations,</p>

<p style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; font-weight: normal; margin: 0 0 10px; padding: 0;">
Your {{main_config.sitename}} account has been setup successfully, you may login to your newly created account by <a href="{{main_config.url}}">clicking here </a></p>
<p> Feel free to comment, rate and maintain your own watch list with the new awesomeness you unlocked.</p>

{% endblock %}