<?PHP

require("database.php");

 if(isset($_POST['namefull']) && isset($_POST['phone']) && isset($_POST['password'])){
    $namefull = mysqli_real_escape_string($db, $_POST['namefull']);
    $phone = $_POST['phone'];
    $password = mysqli_real_escape_string($db, $_POST['password']);
    
     $sql = mysqli_query($db, "INSERT INTO user(namefull, phone, password) VALUES ('$namefull','$phone','$password')");
 }
?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8" />
    <link rel="stylesheet" href="random.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
</head>

<body class="body">
    <p class="header">صفحه ثبت نام</p>

    <div class="main">
        <div class="box">
            <h5>اطلاعات مورد نیاز را وارد کنید</h5>
            
            <form action="login.php" method="POST">
        
                <div class="din">
                    <input required maxlength="20" name="namefull" id="namefull" class="in1" type="text" placeholder="نام کاربری خود را وارد کنید">
                    <div id="nameError" class="error_div d-none">
                        <span class="error">نام خود را به درستی وارد کنید (حداقل ۳ حرف)</span>
                    </div>
                </div>
                
                
                <div class="din">
                    <input required maxlength="11" name="phone" id="phone" class="in1" type="tel" placeholder="شماره خود را وارد کنید">
                    <div id="phoneError" class="error_div d-none">
                        <span class="error">شماره خود را به درستی وارد کنید</span>
                    </div>
                </div>
            
                <div class="din">
                    <input required maxlength="11" name="password" id="password" class="in1" type="password" placeholder="رمز خود را وارد کنید">
                    <div id="passwordError" class="error_div d-none">
                        <span class="error">رمز عبور خود را به درستی وارد کنید (حداقل ۶ کاراکتر)</span>
                    </div>
                </div>

                <div>
                    <button type="submit" id="btn" class="btn btn-success">ثبت</button>
                </div>
            </form>

            <div>
                آیا از قبل حساب دارید؟
                <a class="a" href="register.php">وارد</a> شوید
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        
        let namefull = document.getElementById("namefull");
        let phone = document.getElementById("phone");
        let password = document.getElementById("password");
        let nameError = document.getElementById("nameError");
        let phoneError = document.getElementById("phoneError");
        let passwordError = document.getElementById("passwordError");
        let btn = document.getElementById("btn");


        
        namefull.addEventListener('input', function() {
            if (this.value.length < 3) {
                nameError.classList.remove("d-none");
                this.classList.add("error-input");
                btn.disabled=true;
            } else {
                nameError.classList.add("d-none");
                 this.classList.add("success-input")
                 this.classList.remove("error-input")
                  btn.disabled=false;
            }
        });

        
        phone.addEventListener('input', function() {
            let phoneRegex = /^[0-9]{11}$/;
            if (!phoneRegex.test(this.value)) {
                phoneError.classList.remove("d-none");
                 this.classList.add("error-input")
                   btn.disabled=true;
                   return;
            } else {
                phoneError.classList.add("d-none");
                this.classList.add("success-input")
                this.classList.remove("error-input")
                 btn.disabled=false;
               

            }
        });

        
        password.addEventListener('input', function() {
            if (this.value.length < 6) {
                passwordError.classList.remove("d-none");
                this.classList.add("error-input")
                  btn.disabled=true;
            } else {
                passwordError.classList.add("d-none");
                 this.classList.add("success-input")
                 this.classList.remove("error-input")
                  btn.disabled=false;
            }
        });
    });
    </script>

</body>
</html> 


















































