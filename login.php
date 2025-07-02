<?php include "inc/koneksi.php"; ?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Login | Bank Siswa</title>
  <link rel="icon" href="dist/img/logo.png">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

  <style>
    .login-logo h3 {
      font-weight: bold;
      color: #3c8dbc;
    }

    .login-box-body {
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    }

    .btn-flat {
      border-radius: 5px;
    }

    .form-control {
      border-radius: 5px;
    }

    .login-box-msg {
      font-size: 16px;
      font-weight: 500;
      color: #555;
    }

    .switch-role {
      margin-bottom: 20px;
    }

    .hidden {
      display: none;
    }
  </style>
</head>

<body class="hold-transition login-page">
  <div class="login-box">
    <div class="login-logo">
      <a href="#"><h3><i class="fa fa-bank"></i> E-Bank Siswa</h3></a>
    </div>

    <div class="login-box-body">
      <p class="login-box-msg">Silakan login sesuai peran</p>

      <!-- Switch Role -->
      <div class="text-center switch-role">
        <button class="btn btn-info btn-xs" onclick="switchForm('petugas')"><i class="fa fa-user"></i> Petugas</button>
        <button class="btn btn-success btn-xs" onclick="switchForm('siswa')"><i class="fa fa-child"></i> Siswa</button>
      </div>

      <!-- Form Petugas -->
      <form method="post" action="" id="formPetugas">
        <div class="form-group has-feedback">
          <input type="text" class="form-control" name="username" placeholder="Username" required>
          <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
          <input type="password" class="form-control" name="password" placeholder="Password" required>
          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="row">
          <div class="col-xs-8"></div>
          <div class="col-xs-4">
            <button type="submit" class="btn btn-primary btn-block btn-flat" name="btnLoginPetugas">
              <i class="fa fa-sign-in"></i> Masuk
            </button>
          </div>
        </div>
      </form>

      <!-- Form Siswa -->
      <form method="post" action="" id="formSiswa" class="hidden">
        <div class="form-group has-feedback">
          <input type="text" class="form-control" name="nis" placeholder="Masukkan NIS" required>
          <span class="glyphicon glyphicon-barcode form-control-feedback"></span>
        </div>
        <div class="row">
          <div class="col-xs-8"></div>
          <div class="col-xs-4">
            <button type="submit" class="btn btn-success btn-block btn-flat" name="btnLoginSiswa">
              <i class="fa fa-sign-in"></i> Masuk
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Scripts -->
  <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
  <script src="bootstrap/js/bootstrap.min.js"></script>
  <script>
    function switchForm(role) {
      document.getElementById('formPetugas').classList.add('hidden');
      document.getElementById('formSiswa').classList.add('hidden');

      if (role === 'petugas') {
        document.getElementById('formPetugas').classList.remove('hidden');
      } else {
        document.getElementById('formSiswa').classList.remove('hidden');
      }
    }
  </script>
</body>

</html>

<?php
if (isset($_POST['btnLoginPetugas'])) {
  $username = mysqli_real_escape_string($koneksi, $_POST['username']);
  $password = mysqli_real_escape_string($koneksi, $_POST['password']);

  $sql = "SELECT * FROM tb_pengguna WHERE BINARY username='$username' AND password='$password'";
  $query = mysqli_query($koneksi, $sql);
  $data = mysqli_fetch_array($query, MYSQLI_BOTH);

if ($data) {
    session_start();
    $_SESSION["ses_id"] = $data["id_pengguna"];
    $_SESSION["ses_nama"] = $data["nama_pengguna"];
    $_SESSION["ses_level"] = $data["level"];
    $_SESSION["ses_username"] = $data["username"]; // TAMBAHKAN INI

    echo "<script>
      Swal.fire({ title: 'Login Petugas Berhasil!', icon: 'success' }).then(() => {
        window.location = 'index.php';
      });
    </script>";
  } else {
    echo "<script>
      Swal.fire({ title: 'Login Gagal!', text: 'Username/password salah.', icon: 'error' });
    </script>";
  }
}

if (isset($_POST['btnLoginSiswa'])) {
  $nis = mysqli_real_escape_string($koneksi, $_POST['nis']);

  $sql = "SELECT * FROM tb_siswa WHERE nis='$nis'";
  $query = mysqli_query($koneksi, $sql);
  $data = mysqli_fetch_array($query, MYSQLI_BOTH);

if ($data) {
    session_start();
    $_SESSION["ses_nis"] = $data["nis"];
    $_SESSION["ses_nama"] = $data["nama_siswa"];
    $_SESSION["ses_level"] = "siswa";
    $_SESSION["ses_username"] = $data["nis"]; // TAMBAHKAN INI

    echo "<script>
      Swal.fire({ title: 'Login Siswa Berhasil!', icon: 'success' }).then(() => {
        window.location = 'index.php';
      });
    </script>";
  } else {
    echo "<script>
      Swal.fire({ title: 'Login Gagal!', text: 'NIS tidak ditemukan.', icon: 'error' });
    </script>";
  }
}
?>
