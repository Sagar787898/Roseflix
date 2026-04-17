<?php
session_start();
// Agar pehle se login hai toh seedha admin par bhejo
if(isset($_SESSION['admin'])) { header('Location: admin.php'); exit; }

$error = "";
if(isset($_POST['login'])){
    // Username aur Password check
    if($_POST['user'] == 'admin' && $_POST['pass'] == '1234'){
        $_SESSION['admin'] = true;
        header('Location: admin.php');
        exit;
    } else {
        $error = "Invalid Credentials!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StreamX | Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap');
        
        body {
            background: #020202;
            font-family: 'Plus Jakarta Sans', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            overflow: hidden;
        }

        /* Background Glow Effect */
        .glow {
            position: absolute;
            width: 300px;
            height: 300px;
            background: #e50914;
            filter: blur(120px);
            opacity: 0.2;
            z-index: 0;
        }

        .glass-card {
            background: rgba(20, 20, 20, 0.6);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 30px;
            width: 100%;
            max-width: 400px;
            padding: 40px;
            z-index: 10;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .input-group input {
            background: rgba(255, 255, 255, 0.03) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: white !important;
            transition: 0.3s;
        }

        .input-group input:focus {
            border-color: #e50914 !important;
            box-shadow: 0 0 15px rgba(229, 9, 20, 0.2);
            outline: none;
        }

        .login-btn {
            background: #e50914;
            transition: 0.3s;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: 800;
        }

        .login-btn:hover {
            background: #b20710;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(229, 9, 20, 0.3);
        }

        /* Mobile Adjustments */
        @media (max-width: 480px) {
            .glass-card {
                margin: 20px;
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>

    <div class="glow"></div>

    <div class="glass-card">
        <div class="text-center mb-10">
            <h1 class="text-3xl font-black italic text-red-600 tracking-tighter mb-2">STREAMX</h1>
            <p class="text-gray-500 text-xs font-bold uppercase tracking-[3px]">Admin Authentication</p>
        </div>

        <?php if($error): ?>
            <div class="bg-red-600/10 border border-red-600/20 text-red-500 text-xs py-3 px-4 rounded-xl mb-6 text-center font-bold">
                <i class="fa fa-exclamation-circle mr-2"></i> <?= $error ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-6">
            <div class="input-group">
                <label class="text-[10px] font-bold text-gray-500 uppercase ml-2 mb-2 block tracking-widest">Username</label>
                <div class="relative">
                    <i class="fa fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-600"></i>
                    <input type="text" name="user" value="admin" class="w-full py-4 pl-12 pr-4 rounded-2xl text-sm" placeholder="Enter username" required>
                </div>
            </div>

            <div class="input-group">
                <label class="text-[10px] font-bold text-gray-500 uppercase ml-2 mb-2 block tracking-widest">Password</label>
                <div class="relative">
                    <i class="fa fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-600"></i>
                    <input type="password" name="pass" value="1234" class="w-full py-4 pl-12 pr-4 rounded-2xl text-sm" placeholder="Enter password" required>
                </div>
            </div>

            <button name="login" class="login-btn w-full py-5 rounded-2xl text-white text-xs mt-4">
                Sign In <i class="fa fa-arrow-right ml-2"></i>
            </button>
        </form>

        <div class="mt-10 text-center">
            <p class="text-gray-600 text-[10px] uppercase font-bold tracking-widest">Secure Access Only</p>
        </div>
    </div>

</body>
</html>
