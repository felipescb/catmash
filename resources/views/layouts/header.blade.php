<div class="navbar navbar-default">
    <div class="container">
        <div class="navbar-header">
            <a href="/" class="navbar-brand">CatMash</a>
            <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div class="navbar-collapse collapse" id="navbar-main">
            <ul class="nav navbar-nav">
                <li class="{{ request()->is('/') ? 'active' :'' }}">
                    <a href="{{ url('/') }}" >Vote</a>
                </li>
                <li class="{{ request()->is('cats') ? 'active' :'' }}">
                    <a href="{{ url('/cats') }}">Results</a>
                </li>
            </ul>
            <ul class="nav navbar-right hidden-xs">
                <img src="pics/header.png" alt="header" style="max-height: 41px; margin-top: 5px;">
            </ul>
        </div>
    </div>
</div>
