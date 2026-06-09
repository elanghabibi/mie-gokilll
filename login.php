<?php
session_start();
include './services/domain.php';
include './services/helpers.php';
include './config/koneksi.php';
include './services/auth-login.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare('SELECT * FROM users WHERE username=?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['idUser'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['isLogin'] = true;

            toast('success', 'Login berhasil!');

            if ($_SESSION['role'] == 'admin') {
                header('location: '.$domain.'admin/dashboard');
                exit;
            } else if ($_SESSION['role'] == 'petugas') {
                header('location: '.$domain.'petugas/dashboard');
                exit;
            } else {
                header('location: '.$domain);
                exit;
            }
        } else {
            toast('error', 'Username atau password salah!');

            header('Location:' . $domain . 'login.php');
            exit;
        }
    } else {
        toast('error', 'Username atau password salah!');

        header('Location:' . $domain . 'login.php');
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mie Gokilll</title>
    <link rel="stylesheet" href="src/css/style.css">
    <link
      href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@700;800&amp;family=Inter:wght@400;500&amp;family=Space+Mono:wght@400;700&amp;family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
      rel="stylesheet"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
      rel="stylesheet"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@100..900&amp;family=Inter:wght@100..900&amp;family=Space+Mono:wght@100..900&amp;display=swap"
      rel="stylesheet"
    />
    <!-- Basic Icons -->
    <link href="https://cdn.boxicons.com/3.0.8/fonts/basic/boxicons.min.css" rel="stylesheet">
    <!-- Filled Icons -->
    <link href="https://cdn.boxicons.com/3.0.8/fonts/filled/boxicons-filled.min.css" rel="stylesheet">
    <!-- Brand Icons -->
    <link href="https://cdn.boxicons.com/3.0.8/fonts/brands/boxicons-brands.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex w-full h-screen items-center justify-center">
    <div class="flex flex-col gap-12 items-center">
        <div class="relative group">
            <div class="bg-white border-4 border-gray-950 px-4 py-2 relative z-10">
                <h1
                class="font-bricolage text-5xl font-black uppercase italic tracking-tighter text-gray-950"
                >
                MIE GOKILLL
                </h1>
            </div>
            <div
                class="absolute -bottom-4 left-8 w-6 h-6 bg-gray-950 speech-bubble-tail"
            ></div>
        </div>
        <div class="shadow-[8px_8px_0px_#000] bg-white border-6 space-y-8 border-black sm:min-w-sm max-sm:w-9/10 p-6">
            <form action="<?= $domain . 'login.php'  ?>" method="POST" class="form">
                <div class="space-y-6">
                    <div class="flex flex-col gap-2">
                        <label for="username" class="font-space-mono font-semibold text-lg flex w-fit">ADMIN USERNAME</label>
                        <input type="text" name="username" id="username" class="font-space-mono bg-white border-4 py-1 pl-2 border-black" placeholder="admin123">
                    </div>
                    <div class="flex flex-col gap-2">
                        <label for="password" class="font-space-mono font-semibold text-lg flex w-fit">SECRET PASSWORD</label>
                        <div class="show-password relative w-full">
                            <input type="password" name="password" id="password" class="password-input font-space-mono w-full bg-white border-4 py-1 pl-2 border-black" placeholder="password">
                            <button class="flex items-center justify-center absolute top-1/2 right-2 -translate-y-1/2 text-xl cursor-pointer" type="button"><i class="password-btn-icon bx bx-eye"></i></button>
                        </div>
                    </div>

                    <button type="submit" class="form-btn cursor-pointer hover:translate-1 hover:shadow-none bg-white border-4 py-3 mt-2 border-black shadow-[8px_8px_0px_#000] w-full transition-all"><span class="form-btntext font-space-mono font-semibold text-xl">MASUK</span></button>
                </div>
            </form>

            <div class="w-full justify-between items-center flex font-space-mono text-sm text-gray-600">
                <p>Admin Portal Access</p>
                <p>Level: Admin</p>
            </div>
        </div>
    </div>
    <?php include './includes/toast.php'; ?>
</body>
<script src="./src/js/script.js"></script>
</html>