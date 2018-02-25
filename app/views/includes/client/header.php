<header class="main-header">

    <div class="header">
        <div class="container">
            <div class="row">

                <!-- LOGO -->
                <div class="col-sm-3">
                    <a class="navbar-brand logo" href="{{urlFor('home')}}"><img alt="" class="img-responsive" src="{{images}}/logo.png"></a>
                </div>
                <!-- END LOGO -->
                
                <!-- SEARCH -->
                <div class="col-sm-9">
                    <div class="search-form">

                        <form action="{{urlFor('filter')}}" method="GET">

                            <div class="col-sm-7">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input class="form-control"
                                        placeholder="Search"
                                        type="text" name="q">
                                    </div>
                                </div>
                            </div><!-- /.col 4 -->
                            <div class="col-sm-3">

                                {% macro recursiveCategory(category, postCat, level = 0) %}

                                    {% set isSet = '' %}

                                    {% for catId in postCat %}
                                        {% if (catId == category.id) %}
                                            {% set isSet = 'selected' %}
                                        {% endif %}
                                    {% endfor %}

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

                                <select name="category" class="form-control">
                                    <option value="">Select Category</option>
                                   {% if categoryTree %}
                                        {% for category in categoryTree %}
                                            {{ _self.recursiveCategory(category, request.post('category')) }}
                                        {% endfor %}
                                    {% endif %}
                                </select>

                            </div><!-- /.col 3 -->
                            <div class="col-sm-2">
                                <button class="btn btn-default btn-block ripple-effect"><i class="ti-search" style="margin-right:10px"></i> search</button>
                            </div><!-- /.col 1 -->

                        </form>

                    </div>
                </div>
                <!-- END SEARCH -->

            </div>
        </div>
    </div>
    
    <!-- END HEADER -->

    <ul class="header-col-border"><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li></ul>

    <!-- END BORDER -->

    <!-- MAIN NAVIGATION -->
    <div id="nav-wrap">
        <div class="nav-wrap-holder">
            <div class="container" id="nav_wrapper">

                <nav class="navbar navbar-static-top nav-white" id="main_navbar" role="navigation">

                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                         <a class="my-custom-nav-bar-icon" href="{{urlFor('home')}}"><img alt="#" src="{{images}}/logo-dark.png"></a>
                        <button class="navbar-toggle" data-target="#Navbar" data-toggle="collapse" type="button">
                            <span class="sr-only">Togglenavigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="Navbar">
                        <!-- regular link -->
                        <ul class="nav navbar-nav navbar-left">

                            <li class="dropdown">
                                <a href="{{urlFor('home')}}" role="button"><i class="ti-home"></i>Home</a>
                            </li>

                            <li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="{{urlFor('deal_home')}}"><i class=" ti-clipboard"></i>Deals <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{urlFor('filter')}}?type=fixed">Fixed Deals</a>
                                    </li>
                                    <li>
                                        <a href="{{urlFor('filter')}}?type=count">Countdown Deals</a>
                                    </li>
                                </ul>
                            </li>

                            <li class="dropdown">

                                <a aria-expanded="false" class="dropdown-toggle" data-toggle="dropdown" href="#" role="button"><i class="ti-list-ol"></i>Categories <span class="caret"></span></a>
                                
                                {% macro menuCategory(category) %}

                                    <li {% if category.children|length %}class="dropdown-submenu"{% endif %}>
                                        <a href="{{urlFor('category_single', {'slug' : category.slug, 'id' : category.id})}}">{{category.name}}</a>
                                        
                                        {% if category.children|length %}
                                            <ul class="dropdown-menu">
                                                {% for child in category.children %}
                                                    {{ _self.menuCategory(child) }}
                                                {% endfor %}
                                            </ul>
                                        {% endif %}
                                    </li>

                                {% endmacro %}

                                {% if categoryTree %}
                                    <ul class="dropdown-menu" role="menu">
                                        {% for category in categoryTree %}
                                             {{ _self.menuCategory(category) }}
                                        {% endfor %}
                                    </ul>
                                {% endif %}

                            </li>

                            <li>
                                <a href="{{urlFor('contact')}}"><i class=
                                "ti-email"></i> Contact</a>
                            </li>

                        </ul>
                        
                        <ul class="nav navbar-nav navbar-right">
                        {% if auth %}
                            <li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="{{urlFor('myaccount')}}"><i class=" ti-user"></i>{{auth.getName()}} <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                     <li>
                                        <a href="{{urlFor('myaccount')}}"><i class="ti-settings" style="margin-right:10px;"></i> MY ACCOUNT</a>
                                    </li>
                                    <li>
                                        <a href="{{urlFor('auth_logout')}}"><i class="ti-new-window" style="margin-right:10px;"></i> LOGOUT</a>
                                    </li>
                                </ul>
                            </li>
                         {% else %}
                            <li>
                                <a href="{{urlFor('auth_login')}}"><i class="ti-lock"></i> Sign in</a>
                            </li>
                            <li>
                                <a href="{{urlFor('auth_register')}}"><i class="ti-user"></i> Sign up</a>
                            </li>
                        {% endif %}
                        </ul>

                    </div>
                </nav>
            </div>
        </div>
        <!-- END .div nav wrap holder -->
    </div>
    <!-- END #nav wrap -->


</header>
        