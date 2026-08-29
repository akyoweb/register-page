<?PHP

require("database.php");

if (isset($_POST['namefull'])  && isset($_POST['password'])) {

    $namefull =  $_POST['namefull'];
   
    $password = $_POST['password'];

$query= "select * from user where namefull='$namefull' and password='$password' ";
$result = mysqli_query($db,$query);

if ($row = mysqli_fetch_assoc($result)){

echo '<div class="alert-slide-pro"><span class="alert-icon">✅</span> خوش آمدی '. htmlspecialchars($row['namefull']) .' در حال انتقال به خانه...</div>';

echo '<meta http-equiv="refresh" content="2;url=index.php">';

}else{

echo '<div class="alert-error-center">نام کاربری یا رمز عبور اشتباه است</div>';


};


};





?>










<html>


<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8" />


    <link rel="stylesheet" href="random.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="bootstrap.min.css" rel="stylesheet">
    <link integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">



</head>


<body class="body">
  

    <div class="main">



        <div class="box">
            <h5>اطلاعات صحیح را وارد کنید</h5>
            <form method="post" action="register.php">
                <div class="din">
                    <input maxlength="14" required name="namefull" id="namefull" class="in1" type="text" 
                        placeholder=" شماره یا نام کاربری خود را وارد کنید">
                    <div id="nameError" class="error_div d-none">
                        <span class="error">نام خود را به درستی وارد کنید (حداقل ۳ حرف)</span>
                    </div>
                </div>
                <div class="din">
                    
                        <input  required name="password" id="password" maxlength="10" class="in1"
                            type="password" placeholder="رمز خود را وراد کنید">
                        <div id="passwordError" class="error_div d-none">
                            <span class="error">رمز عبور خود را به درستی وارد کنید (حداقل ۶ کاراکتر)</span>
                        </div>
                </div>



                <div>
                    <button type="submit" id="btn" class="btn btn-success ">ورود</button>

                </div>

                <div class="check">
                    <span class="checktext">مرا بخاطر بسپار</span> <input class="checktik" style="direction: rtl;"
                        type="checkbox"></input>
            </form>
        </div>
        </form>
        <div style="font-size: 14px; padding: 0px; margin-top: -15px;">

            رمز خود را فراموش کرده اید؟
            <a class="a" href="bazyabi.html">بازیابی </a>
            حساب
        </div>

        <div style="font-size: 14px;">

            آیا حسابی ندارید؟
            <a class="a" href="http://localhost/random/login.php">اینجا</a>
            ثبت نام کنید

        </div>




    </div>


    </div>

<script>

let namefull = document.getElementById("namefull");
let password = document.getElementById("password");
let btn = document.getElementById("btn");
let nameError = document.getElementById("nameError");
let passwordError = document.getElementById("passwordError");


btn.disabled = true;

namefull.addEventListener('input', function() {
    if (this.value.length < 3) {
        nameError.classList.remove("d-none");
        this.classList.add("error-input");
        this.classList.remove("success-input");
        btn.disabled = true;
    } else {
        nameError.classList.add("d-none");
        this.classList.add("success-input");
        this.classList.remove("error-input");
        
        checkAllFields();
    }
});

password.addEventListener('input', function() {
    if (this.value.length < 6) {
        passwordError.classList.remove("d-none");
        this.classList.add("error-input");
        this.classList.remove("success-input");
        btn.disabled = true;
    } else {
        passwordError.classList.add("d-none");
        this.classList.add("success-input");
        this.classList.remove("error-input");
        checkAllFields();
    }
});

function checkAllFields() {
    if (namefull.value.length >= 3 && password.value.length >= 6) {
        btn.disabled = false;
    } else {
        btn.disabled = true;
    }
}


</script>




</body>