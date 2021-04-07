<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet" />
    <link href="/css/app.css" rel="stylesheet" />
    <title>Home</title>
</head>

<body>
    <div id="app">
        <div>
            <b-navbar toggleable="lg" type="dark" variant="info">
                <b-navbar-brand href="#">Administrator</b-navbar-brand>
                <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>
                <b-collapse id="nav-collapse" is-nav>
                    <b-navbar-nav>
                        <router-link class="nav-link" to="/">Home</router-link>
                        <router-link class="nav-link" to="/task">Task</router-link>
                        <router-link class="nav-link" to="/about">About</router-link>
                    </b-navbar-nav>
                    <b-navbar-nav class="ml-auto">
                        <b-nav-item-dropdown right>
                            <template #button-content>
                                <em>User</em>
                            </template>
                            <b-dropdown-item href="#">Profile</b-dropdown-item>
                            <b-dropdown-item href="#">Sign Out</b-dropdown-item>
                        </b-nav-item-dropdown>
                    </b-navbar-nav>
                </b-collapse>
            </b-navbar>
        </div>
        <b-container class="pt-3">
            <router-view></router-view>
        </b-container>
    </div>
    <script type="text/javascript" src="/js/app.js"></script>
</body>

</html>