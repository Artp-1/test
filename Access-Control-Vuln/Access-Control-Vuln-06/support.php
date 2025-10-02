<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

$dir = __DIR__ . '/transcripts';

if(isset($_GET['file'])){
    $file = $_GET['file'];
    $path = $dir . '/' . basename($file);

    if (!file_exists($path)) die("File not found!");

    header('Content-Type: text/plain');
    header('Content-Disposition: attachment; filename="' . basename($path) . '"');
    readfile($path);
    exit;
}


function ensure_sample_file($path, $label){
    if(!file_exists($path)){
        $content = "From: system\nTime: ".date('c')."\n\nSample $label content created automatically.\n";
        if(file_put_contents($path, $content) !== false){
            chmod($path, 0644);
            return "Created sample file: ".basename($path);
        } else {
            return "FAILED to create $path (check permissions)";
        }
    }
    return null;
}

if (!is_dir($dir)) die("ERROR: transcripts folder missing. Create manually 1.txt and 2.txt");

$created_msgs = [];
$msg = ensure_sample_file($dir.'/1.txt','1.txt'); if($msg) $created_msgs[]=$msg;
$msg = ensure_sample_file($dir.'/2.txt','2.txt'); if($msg) $created_msgs[]=$msg;

$errorMsg = "";
$infoMsg = "";
$messages = [];

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $userMsg = trim($_POST['message'] ?? '');
    if($userMsg !== ''){
        $supportMsg = "Support: Wait until someone contacts you -> ".htmlspecialchars($userMsg);

        // AUTO SAVE NEW FILE
        $n = 3;
        while(file_exists("$dir/$n.txt")) $n++;
        $fileToSave = "$dir/$n.txt";
        $content = $supportMsg . "\n";

        if(file_put_contents($fileToSave, $content) === false){
            $errorMsg .= "Could not write file $fileToSave. Check permissions! PHP user: ".exec('whoami');
        } else {
            $infoMsg .= "Saved new transcript as $n.txt";
            $_SESSION['lastFile'] = "$n.txt";
            $messages[] = $supportMsg;
        }
    } else {
        $errorMsg .= "Message empty.";
    }
}

$lastFile = $_SESSION['lastFile'] ?? '';
?>
<!doctype html>
<html lang="sq">
<head>
<meta charset="utf-8">
<title>Support</title>
<style>
body{font-family:Arial,sans-serif;background:#0f1226;color:#e8ecff;display:flex;align-items:center;justify-content:center;height:100vh;padding:1rem;}
.card{background:#141833;padding:1.25rem;border-radius:12px;width:520px;box-shadow:0 12px 28px rgba(0,0,0,.45);position:relative;}
textarea{width:100%;height:100px;padding:.6rem;border-radius:10px;border:1px solid rgba(124,92,255,.4);background:rgba(20,24,51,.5);color:#fff;}
.actions{display:flex;gap:6%;margin-top:.6rem;}
.actions input[type="submit"]{flex:1;padding:.7rem;border-radius:10px;border:none;background:linear-gradient(135deg,#7c5cff,#22d3ee);color:#fff;font-weight:700;cursor:pointer;}
.msg{background:#0c0f23;padding:.6rem;border-radius:8px;margin-top:.8rem;max-height:200px;overflow:auto;}
.err{background:#3b0a0a;color:#ffb3b3;padding:.6rem;border-radius:8px;margin-top:.6rem;}
.ok{background:#072b0b;color:#b3ffcf;padding:.6rem;border-radius:8px;margin-top:.6rem;}
.small{font-size:12px;color:#aab0d5;margin-top:.6rem;}
.created{font-size:13px;color:#d7f7d7;margin-bottom:.5rem;}

/* Back to Login button */
.login-btn{
    position:absolute;
    top:15px;
    right:15px;
    padding:.5rem 1rem;
    border-radius:8px;
    border:none;
    background: linear-gradient(135deg,#ff7f50,#ff6347); 
    color:#fff;
    font-weight:700;
    cursor:pointer;
    transition: background 0.3s;
}
.login-btn:hover{
    background: linear-gradient(135deg,#ff6347,#ff4500); 
}
</style>
</head>
<body>
<div class="card">
<h3>Support</h3>

<!-- Back to Login button top-right -->
<button class="login-btn" type="button" onclick="window.location.href='index.php';">Back to Login</button>

<?php if(!empty($created_msgs)): ?>
<div class="created">
<?php foreach($created_msgs as $cm) echo htmlspecialchars($cm)."<br>"; ?>
</div>
<?php endif; ?>

<form method="post" id="chatForm">
<textarea name="message"><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
<div class="actions">
    <input type="submit" name="send" value="Send">
</div>
</form>

<?php if(!empty($errorMsg)): ?>
<div class="err"><?php echo htmlspecialchars($errorMsg); ?></div>
<?php endif; ?>

<?php if(!empty($infoMsg)): ?>
<div class="ok"><?php echo htmlspecialchars($infoMsg); ?></div>
<?php endif; ?>

<div class="msg">
<?php
if(!empty($messages)){
    foreach($messages as $m) echo "<div>".htmlspecialchars($m)."</div>";
} else {
    echo "<div class='small'>No messages yet.</div>";
}
?>
</div>

<script>
<?php if($lastFile): ?>
window.location.href = "?file=<?php echo urlencode($lastFile); ?>";
<?php unset($_SESSION['lastFile']); endif; ?>
</script>

</div>
</body>
</html>
