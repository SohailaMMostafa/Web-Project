<header class="head">
    <!-- <h1>Registration Website</h1> -->
    <nav class="main-navigation">
        <ul>
            <!-- Using Laravel's route helper to link to the home page -->
            <li><a href="{{ url('/') }}" class="fa fa-home"></a></li>
            <li><a href="{{ url('/') }}">Home</a></li>
        </ul>
    </nav>
    <div class="language-slider">
        <div class="toggle-content">
            <a href="{{ url('locale/en') }}">EN</a> |
            <a href="{{ url('locale/ar') }}">AR</a>
        </div>
    </div>
</header>
