<?php
session_start();
// Allow viewing for logged-in voters or admins. If neither, redirect to root selection.
if (!isset($_SESSION['voter']) && !isset($_SESSION['admin'])) {
    header('Location: /votesystem/index.php');
    exit;
}
// Determine role for a small indicator
$viewer_role = isset($_SESSION['admin']) ? 'admin' : 'voter';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Developer Profile</title>
  <style>
    body{font-family:Arial,Helvetica,sans-serif;background:#f7f7f9;margin:0;padding:24px;color:#222}
    .card{max-width:900px;margin:28px auto;background:#fff;padding:22px;border-radius:8px;box-shadow:0 6px 18px rgba(0,0,0,0.06)}
    .row{display:flex;gap:20px;align-items:flex-start}
    .left{flex:1}
    .photo{width:160px;height:160px;border-radius:8px;overflow:hidden;background:#fafafa;border:1px solid #eee;display:flex;align-items:center;justify-content:center}
  .photo img{width:100%;height:100%;object-fit:cover;display:block}
    h1{margin:0 0 6px 0;font-size:22px}
    .muted{color:#666;margin-bottom:8px}
    .field{margin-bottom:10px}
    .label{font-weight:600;color:#333}
    pre.bio{white-space:pre-wrap;background:transparent;padding:0;margin:0;color:#333}
    .back-btn{position:fixed;left:14px;top:14px;background:#222;color:#fff;padding:10px 12px;border-radius:6px;border:0;z-index:999;cursor:pointer}
    @media(max-width:640px){.row{flex-direction:column}.photo{width:120px;height:120px}}
  </style>
</head>
<body>
<button class="back-btn" id="backBtn">◀ Back</button>
<?php
// Load server data (developer_data.json)
$dataFile = __DIR__ . '/developer_data.json';
$dev = [];
if (file_exists($dataFile)) {
    $raw = file_get_contents($dataFile);
    $d = json_decode($raw, true);
    if (is_array($d)) $dev = $d;
}

// Helper to safely echo
function e($v){ return htmlspecialchars($v ?? '', ENT_QUOTES); }

// Photo handling: if data URL present, use it; if plain filename exists in images/, create path
$photo = '';
if (!empty($dev['photo'])) {
    $photo = $dev['photo'];
    if (strpos($photo, 'data:') !== 0) {
        // treat as filename under images/
        $candidate = __DIR__ . '/images/' . $photo;
        if (file_exists($candidate)) {
            $photo = '/votesystem/images/' . rawurlencode($photo);
        }
    }
}
?>

<div class="card">
  <div class="row">
    <div class="left">
      <h1><?php echo e($dev['fullname'] ?? 'Developer Name'); ?> <small style="font-size:12px;color:#888">(<?php echo e($viewer_role); ?> view)</small></h1>
      <div class="muted"><?php echo e($dev['title'] ?? ''); ?><?php if(!empty($dev['location'])) echo ' • ' . e($dev['location']); ?></div>

      <div class="field"><div class="label">Email: jallohalusine698@gmail.com</div><?php echo e($dev['email'] ?? ''); ?></div>
      <div class="field"><div class="label">Phone</div><?php echo e($dev['phone'] ?? ''); ?></div>
      <div class="field"><div class="label">Website</div><?php if(!empty($dev['website'])) echo '<a href="'.e($dev['website']).'" target="_blank">'.e($dev['website']).'</a>'; ?></div>
      <div class="field"><div class="label">LinkedIn</div><?php if(!empty($dev['linkedin'])) echo '<a href="'.e($dev['linkedin']).'" target="_blank">'.e($dev['linkedin']).'</a>'; ?></div>
      <div class="field"><div class="label">GitHub</div><?php if(!empty($dev['github'])) echo '<a href="'.e($dev['github']).'" target="_blank">'.e($dev['github']).'</a>'; ?></div>

      <div style="margin-top:12px"><div class="label">About</div>
        <pre class="bio"><?php echo e($dev['bio'] ?? ''); ?></pre>
      </div>
    </div>

    <div style="width:180px;flex:0 0 180px">
      <div class="photo">
        <?php if(!empty($photo)): ?>
            <img src="<?php echo e($photo); ?>" alt="Developer photo">
        <?php else: ?>
            <div style="color:#999">No photo</div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

  <script>
  // Back button uses history.back() with fallback to index
  (function(){
    var b = document.getElementById('backBtn');
    if(!b) return;
    b.addEventListener('click', function(){
      if(window.history.length > 1){ window.history.back(); }
      else { window.location.href = '/votesystem/index.php'; }
    });
  })();
  </script>
</body>
</html>
