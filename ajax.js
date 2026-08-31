// ajax.js — ارسال AJAX برای همه فرم‌های دارای data-ajax
// هر فرم: <form action="..." method="POST" data-ajax data-redirect="index.php">
(function () {
  document.addEventListener("submit", function (e) {
    var form = e.target;
    if (!form.matches("form[data-ajax]")) {
      return;
    }
    e.preventDefault();

    var btn = form.querySelector('button[type="submit"]');
    var oldLabel = btn ? btn.innerText : "";
    if (btn) {
      btn.disabled = true;
      btn.innerText = "لطفاً صبر کنید...";
    }

    fetch(form.action || window.location.href, {
      method: "POST",
      body: new FormData(form),
    })
      .then(function (r) {
        if (!r.ok) {
          throw new Error("HTTP " + r.status);
        }
        return r.json();
      })
      .then(function (data) {
        if (data && data.success) {
          showTopAlert(data.message, "success");
          var redirect = form.getAttribute("data-redirect");
          if (redirect) {
            setTimeout(function () {
              window.location.href = redirect;
            }, 1800);
          }
        } else {
          if (btn) {
            btn.disabled = false;
            btn.innerText = oldLabel;
          }
          showTopAlert((data && data.message) || "خطایی رخ داد", "error");
        }
      })
      .catch(function () {
        if (btn) {
          btn.disabled = false;
          btn.innerText = oldLabel;
        }
        showTopAlert("خطا در ارتباط با سرور", "error");
      });
  });

  // پنجره اطلاع‌رسانی ثابت بالای صفحه
  function showTopAlert(message, type) {
    var box = document.getElementById("topAlert");
    if (!box) {
      box = document.createElement("div");
      box.id = "topAlert";
      document.body.prepend(box);
    }
    box.className =
      type === "success" ? "alert-slide-pro" : "alert-error-center";
    box.textContent = message;
    box.style.display = "block";
    clearTimeout(window.__alertTimer);
    window.__alertTimer = setTimeout(function () {
      box.style.display = "none";
    }, 5000);
  }
  window.showTopAlert = showTopAlert;
})();
