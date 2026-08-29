<!DOCTYPE html>
<html>


<head>


        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="utf-8" />


        <link rel="stylesheet" href="random.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="bootstrap.min.css" rel="stylesheet">
        <link integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
                crossorigin="anonymous">








</head>







<body>






<body class="body">
        
<div id="alertbox" class="d-none" class="alert alert-success d-flex align-item-center " role="alert">


</div>
   <!-- <p class="header" class="page">بازیابی حساب</p> -->
        <div class="main">
     



                <div class="box">
                        <h5>اطلاعات صحیح را وارد کنید</h5>
                        <form method="get" action="">
                        <div class="din">
                                <input required id="phone" class="in1" type="tel" maxlength="11" placeholder="شماره تلفن خودرا وارد کنید">
                        </div> 
                         <div id="divcode" class="din"> 
                                <input required style="border-radius: 10px;
    border: solid 1px lightslategray;
    width: 80%;
    height: 40;
    outline: none;
    padding: 9px;
    box-sizing: border-box;
    font-size: 16px;" class="d-none" id="code" class="in1" type="tel" placeholder="کد شش رقمی را وارد کنید"> 
                        

                        

                        <div>
                            
                                    <button  style="margin-top: 9px;" type="button" id="btn2" class="btn btn-success d-none " >ثبت</button>
                                <button type="button" id="btn" class="btn btn-success ">ارسال کد</button>
</form>

<!-- 
                        </div>
                        <br>
                        <div class="ersalmojadad"><button style="font-size: 8px;" class="btn btn-primary">ارسال مجدد کد</button></div>
                      -->
<div style="font-size: 14px; padding: 0px;">








  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js">
    </script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js">
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js">
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    let phone = document.getElementById("phone");
    let code = document.getElementById("code");
    let btn = document.getElementById("btn");
    let alertbox = document.getElementById("alertbox");
    let verifyBtn = document.getElementById("btn2"); 

    let realcod = "1234";
    let timer = null;
    let time = 60;

    btn.onclick = function() {
        if (!phone.value || phone.value.length < 11) {
            alert("لطفاً شماره تلفن معتبر وارد کنید");
            return;
        }

        code.classList.remove("d-none");
        verifyBtn.classList.remove("d-none"); 
        code.disabled = false;

        if (timer !== null) {
            return;
        }

        btn.disabled = true;
        time = 60;
        btn.innerText = `${time} ثانیه`;

        timer = setInterval(function() {
            time--;
            btn.innerText = `${time} ثانیه`;

            if (time <= 0) {
                clearInterval(timer);
                timer = null;
                btn.disabled = false;
                btn.innerText = "ارسال مجدد کد";
            }
        }, 1000);
    };

    
    btn2.onclick = function() {
        if (code.value === realcod) {
          alertbox.classList.remove("d-none");
          alertbox.innerHTML="کد تایید شد";
          alertbox.style.color = "green";
          alertbox.style.direction="rtl";
           alertbox.style.width="50%";
           alertbox.style.textAlign="center";
          alertbox.style.backgroundColor ="#d4edda";
          

setTimeout(function(){

alertbox.classList.add("d-none");


},5000);
        } else {
                 alertbox.classList.remove("d-none");
          alertbox.innerHTML="کد اشتباه است";
          alertbox.style.color = "red";
          alertbox.style.backgroundColor ="#f8d7da";
           alertbox.style.direction="rtl";
           alertbox.style.width="50%";
           alertbox.style.textAlign="center";
            
         



setTimeout(function(){

alertbox.classList.add("d-none");


},5000);





        }
    };
});

</script>

</body>
































</html>