<?php

$dir = __DIR__;
$tests = json_decode(file_get_contents($dir . '/all-tests.json'), true);

foreach ($tests as $t) {
    $color = match ($t['code']) {
        '200' => '#15803d',
        '201' => '#1d4ed8',
        '422' => '#b45309',
        '404' => '#b91c1c',
        default => '#334155',
    };

    $req = '';
    if (!empty($t['reqBody'])) {
        $decoded = json_decode($t['reqBody'], true);
        $rb = json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $req = '<div class="label">Request Body</div><pre class="box">' . htmlspecialchars((string) $rb) . '</pre>';
    }

    $title = htmlspecialchars((string) $t['title']);
    $method = htmlspecialchars((string) $t['method']);
    $code = htmlspecialchars((string) $t['code']);
    $url = htmlspecialchars((string) $t['url']);
    $body = htmlspecialchars((string) $t['body']);
    $no = (int) $t['no'];

    $html = <<<"HTML"
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Uji {$no}</title>
<style>
body{margin:0;font-family:Segoe UI,Arial,sans-serif;background:#0f172a;color:#e2e8f0}
.wrap{padding:24px;max-width:980px}
.badge{display:inline-block;padding:6px 12px;border-radius:6px;font-weight:700;background:{$color};color:#fff;margin-left:8px}
.method{display:inline-block;padding:6px 10px;border-radius:6px;background:#334155;font-weight:700}
h1{font-size:22px;margin:14px 0}
.url{font-family:Consolas,monospace;background:#1e293b;padding:10px 12px;border-radius:8px;margin:12px 0;word-break:break-all}
.label{color:#94a3b8;font-size:12px;text-transform:uppercase;margin:16px 0 6px}
pre.box{background:#020617;border:1px solid #334155;border-radius:8px;padding:14px;white-space:pre-wrap;word-break:break-word;font-family:Consolas,monospace;font-size:13px;line-height:1.45}
.meta{color:#94a3b8;font-size:13px}
</style>
</head>
<body>
<div class="wrap">
  <div class="meta">Capture Pengujian Manual REST API Mahasiswa</div>
  <h1>Skenario {$no}: {$title}</h1>
  <div><span class="method">{$method}</span><span class="badge">HTTP {$code}</span></div>
  <div class="url">http://127.0.0.1:8000{$url}</div>
  {$req}
  <div class="label">Response JSON</div>
  <pre class="box">{$body}</pre>
</div>
</body>
</html>
HTML;

    $file = sprintf('%s/uji-%02d.html', $dir, $no);
    file_put_contents($file, $html);
    echo "OK $file\n";
}
