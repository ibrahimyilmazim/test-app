<?php 
	
	require_once('config.php');
	
	if($_POST) {
		
		$email    = $_POST['email'];
		$password = $_POST['password'];
		$passHash = sifrele($password);
		
		if(!$email) {
			die("1,Lütfen geçerli bir e-posta adresi giriniz.");
			
		} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			die("1,E-mail adresiniz hatalı gözüküyor.");
			
		} else if (!$password) {
			die("1,Parola alanı boş olamaz.");
			
		} else {
			$sql = $db->prepare("SELECT * FROM users WHERE email = :email AND password = :password");
			$sql->execute([
				
				':email'    => $email,
				':password' => $passHash
			
			]);
			
			if($sql->rowCount()) {
				
				$row = $sql->fetch(PDO::FETCH_OBJ);
				
				$_SESSION['oturum']  = true;
				$_SESSION['adsoyad'] = $row->name.' '.$row->surname;
				$_SESSION['email'] = $row->email;
				
				die("2,Giriş başarılı, yönlendiriliyorsunuz.");
				
			} else {
				
				die("1,Girdiğiniz bilgiler yanlış.");
				
			}
			
		}
		
	}
?>
<!DOCTYPE html>
<html lang="tr">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Admin Panel - Giriş</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="library/css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-10 col-lg-12 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
              <div class="col-lg-6">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Hoş Geldiniz!</h1>
                  </div>
                  <form class="user" id="formkontrol" action="" method="POST" onsubmit="return false">
                    <div class="form-group">
                      <input type="email" name="email" class="form-control form-control-user" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="E-mail adresinizi giriniz..." value="<?php echo $_POST['email'];?>">
                    </div>
                    <div class="form-group">
                      <input type="password" name="password" class="form-control form-control-user" id="exampleInputPassword" placeholder="Parola" value="<?php echo $_POST['password'];?>">
                    </div>
                    <div class="form-group">
                      <div class="custom-control custom-checkbox small">
                        <input type="checkbox" class="custom-control-input" id="customCheck">
                        <label class="custom-control-label" for="customCheck">Beni hatırla</label>
                      </div>
                    </div>
					<div id="alert_message" class="alert" style="display:none"></div>
                    <button type="submit" class="btn btn-primary btn-user btn-block">Giriş Yap</button>
                    <hr>
                  </form>
                  <div class="text-center">
                    <a class="small" href="forgot-password.html">Şifremi Unuttum?</a>
                  </div>
                  <div class="text-center">
                    <a class="small" href="register">Kayıt Ol!</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="library/vendor/jquery/jquery.min.js"></script>
  <script src="library/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="library/vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="library/js/sb-admin-2.min.js"></script>

</body>

<script>
	$(document).ready(function() {
		var formkontrol = $('#formkontrol');
		var ikaz = $('#alert_message');
		formkontrol.on('submit', function(e) {
			e.preventDefault();
			$.ajax({
				type: 'POST',
				dataType: 'html',
				data:  $("#formkontrol").serialize(),
				
				success: function(data) {
					var res = data.substr(0, 1);
					if(res==2) {
						data = data.substr(2);
						ikaz.removeClass("alert-danger");
						ikaz.addClass("alert-success").html(data).fadeIn();
						// formkontrol.trigger('reset'); sıfırlıyor formu
					} else if(res==1) {
						data = data.substr(2);
						ikaz.removeClass("alert-success");
						ikaz.addClass("alert-danger").html(data).fadeIn();
						// formkontrol.trigger('reset'); sıfırlıyor formu
					}
					
				},
				error: function(e) {
					console.log(e)
				}
			});
		});
	});

</script>
</html>


<?php ob_end_flush(); $db = null; ?>