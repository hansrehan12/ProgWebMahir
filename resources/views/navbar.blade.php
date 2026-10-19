<style>
    .navbar {
        background-color: #333;
        overflow: hidden;
        border-radius: 6px;
        margin-bottom: 20px;
        display: flex;
        justify-content: center;
    }

    .navbar a {
        float: left;
        color: #f2f2f2;
        text-align: center;
        padding: 12px 20px;
        text-decoration: none;
        font-size: 15px;
        font-weight: bold;
    }

    .navbar a:hover {
        background-color: #ddd;
        color: black;
    }

    .navbar a.active {
        background-color: #007bff;
        color: white;
    }
</style>

<div class="navbar">
    <a href="/profil" class="{{ Request::is('profil') ? 'active' : '' }}">Profil</a>
    <a href="/pendidikan" class="{{ Request::is('pendidikan') ? 'active' : '' }}">Pendidikan</a>
</div>