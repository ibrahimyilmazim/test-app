<?php 

	require_once('config.php');
	
	
	if($_POST) {
		
		$name    	= $_POST['name'];
		$surname    = $_POST['surname'];
		$email    	= $_POST['email'];
		$password 	= $_POST['password'];
		$password2 	= $_POST['password2'];
		$passHash 	= sifrele($password);
		
		
		if(!$name || strlen($name)<3) {
			die("1,İsim alanı boş veya 3 karakterden az olamaz.");
		} else if(!$surname || strlen($surname)<3) {
			die("1,Soysim alanı boş veya 3 karakterden az olamaz.");
		} else if(!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
			die("1,Geçerli bir mail adresi giriniz.");
		} else if(!$password || strlen($password)<6 || !$password2 || strlen($password2)<6) {
			die("1,En az 6 haneli parola girmeniz gerekmektedir.");
		} else if($password!=$password2) {
			die("1,Parolalar eşleşmemektedir.");
		} else {
			$varmi = $db->prepare("SELECT email FROM users WHERE email=:posta");
			$varmi->execute(array(':posta'=>$email));
			
			if($varmi->rowCount()) {
				die("1,Bu email adresi ile kayıtlı bir üye bulunmaktadır.");
			} else {
				
				try{
					
				
				$kayit = $db->prepare("INSERT INTO users SET
					name 	  = :ad,
					surname   = :soyad,
					email	  = :posta,
					password  = :sifre,
					addedtime = :eklenme
				");
				
				$kayit->execute([
					':ad'		=> $name,
					':soyad'	=> $surname,
					':posta'	=> $email,
					':sifre'	=> $passHash,
					':eklenme'	=> $ondatetime
				]);
				}  catch (PDOException $e) {
					die("1,".$e->getMessage());
				}
				
				if($db->lastInsertId()) {
					die("2,Kayıt işleminiz başarı ile oluşturulmuştur.");
				} else {
					die("1,Teknik bir hata oluştu, lütfen daha sonra deneyiniz.");
				}
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

  <title>Admin Panel - Kayıt Ol</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="library/css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

  <div class="container">

    <div class="card o-hidden border-0 shadow-lg my-5">
      <div class="card-body p-0">
        <!-- Nested Row within Card Body -->
        <div class="row">
          <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
          <div class="col-lg-7">
            <div class="p-5">
              <div class="text-center">
                <h1 class="h4 text-gray-900 mb-4">Hesap Oluştur!</h1>
              </div>
              <form class="user" id="formkontrol" action="" method="POST" onsubmit="return false">
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="text" class="form-control form-control-user" name="name" placeholder="Adınız" value="<?php echo $_POST['name']; ?>">
                  </div>
                  <div class="col-sm-6">
                    <input type="text" class="form-control form-control-user" name="surname" placeholder="Soyadınız" value="<?php echo $_POST['surname'];?>">
                  </div>
                </div>
                <div class="form-group">
                  <input type="email" class="form-control form-control-user" name="email" placeholder="E-mail Adresiniz" value="<?php echo $_POST['email'];?>">
                </div>
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="password" class="form-control form-control-user" name="password" id="exampleInputPassword" placeholder="Parola" value="<?php echo $_POST['password'];?>">
                  </div>
                  <div class="col-sm-6">
                    <input type="password" class="form-control form-control-user" name="password2" id="exampleRepeatPassword" placeholder="Parolanızı Yeniden Giriniz" value="<?php echo $_POST['password2'];?>">
                  </div>
                </div>
				<div id="alert_message" class="alert" style="display:none"></div>
                <button type="submit" class="btn btn-primary btn-user btn-block">Kayıt Ol</button>
              </form>
              <hr>
              <div class="text-center">
                <a class="small" href="forgot-password.html">Şifremi Unuttum?</a>
              </div>
              <div class="text-center">
                <a class="small" href="login">Hesabım var, giriş yap!</a>
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
					} else if(res==1) {
						data = data.substr(2);
						ikaz.removeClass("alert-success");
						ikaz.addClass("alert-danger").html(data).fadeIn();
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