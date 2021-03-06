<?php 
$theme = Theme::uses(Session::get("theme"));
$title = Session::get("portal-title");
?>

@section ('page-header')
@include("layout/fixed-header", array(
                            "title" => $title
                        ))
@show
<style>
/*z index of sidebar is 100.*/
.theme-header{
    position: relative;
    z-index:101;
}
</style>
<div class="theme-header">
<!-- Header from theme -->
@if( isset($theme) )
{{ $theme->partial("header") }}
@endif
</div>

<body>

<!-- Getting user info -->
@if(Session::has("user-profile"))
<script>
var email =  "{{ Session::get("user-profile")["email"] }}";
var fullName = "{{Session::get("user-profile")["firstname"] . " " . Session::get("user-profile")["lastname"]}}"
</script>
@endif

<div class="pga-header">
{{ CommonUtilities::create_nav_bar() }}
</div>

<!-- Handling error on pages --> 
<!--  Alerts if guests users try to go to the link without signing in. -->
@if (Session::has("login-alert")) 
    {{ CommonUtilities::print_error_message("You need to login to use this service.") }}
    {{ Session::forget("login-alert") }}
@endif
<!-- if signed in user is not an admin. -->
@if (Session::has("admin-alert"))
    {{ CommonUtilities::print_error_message("You need to be an admin to use this service.") }}
    {{ Session::forget("admin-alert") }}
@endif

<!--  PGA UI lies here. Do not touch. -->
<style>
.content-area{
    margin:0;
}
</style>
<div class="row content-area">
    @yield('content')
</div>


@include('layout/fixed-footer')

<style>
.theme-footer{
	margin-top: 20px;
}
</style>
@if( isset( $theme))
<footer class="theme-footer">
{{ $theme->partial("footer") }}
</footer>
@endif

</body>

@show


@section('scripts')
@include('layout/fixed-scripts')
<script type="text/javascript">
	/* keeping a check that footer stays atleast at the bottom of the window.*/
	var bh = $("html").height();
	if( bh < $(window).height()){
		$(".theme-footer").css("position", "relative").css("top", $(window).height()/4).css("z-index", "-1");
    }
    var bw = $("body").width();
    if( bw > 767){
        $(".hero-unit").height( bw*0.36);
    }

    //put sidebar below all headers in admin dashboards
    if( $(".side-nav").length > 0){
        var headerHeight = $(".pga-header").height() + $(".theme-header").height();
        $(".side-nav").css("padding-top", headerHeight);

        var selectedDashboardHeight = $(window).height() - headerHeight;
        if( selectedDashboardHeight < $(".side-nav").height())
        {
            $(".side-nav").height( selectedDashboardHeight).css("overflow-y", "scroll").css("overflow-x", "none");
        }
    }

    $(".floating").click( function(){
        $('html,body').animate({
            scrollTop: $(".seagrid-info").offset().top},
        'slow');
        $(".seagrid-info").scrollTop( $(window).scrollTop() + 150);
    })
</script>

<!-- Google Analytics for portal-->

@if( isset( Config::get('pga_config.portal')['google-analytics-id']))

    @if( Config::get("pga_config.portal")["google-analytics-id"] != '')
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', '{{ Config::get("pga_config.portal")["google-analytics-id"] }}', 'auto');
      ga('send', 'pageview');

    </script>
    @endif

@endif
<!-- end google analytics --> 
@show

</html>