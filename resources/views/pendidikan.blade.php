<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendidikan Mahasiswa</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .card {
            background-color: #ffffff;
            width: 100%;
            max-width: 500px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            padding: 30px;
            margin: 20px;
        }

        h1 {
            font-size: 1.6rem;
            color: #1a252f;
            text-align: center;
            margin-bottom: 20px;
        }

        .nav-links {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 25px;
        }

        .nav-links a {
            text-decoration: none;
            color: #4a5568;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 6px;
            background-color: #edf2f7;
            transition: all 0.2s ease;
        }

        .nav-links a:hover,
        .nav-links a.active {
            background-color: #3182ce;
            color: #ffffff;
        }

        hr {
            border: 0;
            height: 1px;
            background: #e2e8f0;
            margin-bottom: 25px;
        }

        .education-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .edu-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 18px;
            background-color: #f8fafc;
            border-left: 4px solid #3182ce;
            border-radius: 6px;
            transition: transform 0.2s ease;
        }

        .edu-item:hover {
            transform: translateX(4px);
        }

        .edu-level {
            font-weight: 700;
            color: #2d3748;
            font-size: 0.95rem;
        }

        .edu-school {
            font-weight: 500;
            color: #4a5568;
            font-size: 0.95rem;
        }
    </style>
</head>
<body>

    <div class="card">
        <h1>Riwayat Pendidikan</h1>

        <div class="nav-links">
            <a href="/profil">Profil</a>
            <a href="/pendidikan" class="active">Pendidikan</a>
        </div>

        <hr>

        <div class="education-list">
            <div class="edu-item">
                <span class="edu-level">SD</span>
                <span class="edu-school">{{ $sd }}</span>
            </div>
            <div class="edu-item">
                <span class="edu-level">SMP</span>
                <span class="edu-school">{{ $smp }}</span>
            </div>
            <div class="edu-item">
                <span class="edu-level">SMA / SMK</span>
                <span class="edu-school">{{ $sma }}</span>
            </div>
        </div>
    </div>

</body>
</html>