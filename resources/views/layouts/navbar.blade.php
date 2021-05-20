<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="/"><h3>חשבונית הקסמים</h3></a>
    <button
    class="navbar-toggler"
    type="button" data-toggle="collapse"
    data-target="#navbarSupportedContent"
    aria-controls="navbarSupportedContent"
    aria-expanded="false"
    aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            @auth
                @if (isset($business))
                    <li class="nav-item">
                        <a class="nav-link" href="/businesses/{{$business->id}}/home">דף הבית</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/businesses/{{$business->id}}/docs">מסמכים</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/businesses/{{$business->id}}/customers">לקוחות</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/businesses/{{$business->id}}/products">מוצרים</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/businesses/{{$business->id}}/reports">דוחות</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/home">הגדרות משתמש</a>
                    </li>
                    
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="/businesses">בחר עסק/צור עסק חדש</a>
                    </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link" href="/logout">היתנתק</a>
                </li>
            @endauth

            @guest
                <li class="nav-item">
                    <a class="nav-link" href="/login">התחבר</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/register">הירשם</a>
                </li>
            @endguest
        </ul>
        
    </div>
</nav>
