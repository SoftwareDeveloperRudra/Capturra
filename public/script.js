document.addEventListener("DOMContentLoaded", function () {

  const loginForm = document.getElementById("loginForm");

  if (loginForm) {
    loginForm.addEventListener("submit", function (e) {
      e.preventDefault();

      const formData = new FormData(loginForm);

      fetch("api/login.php", {
        method: "POST",
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        console.log(data);

        if (data.success) {
          // Redirect based on role
          window.location.href = data.data.redirect_url;
        } else {
          alert(data.message);
        }
      })
      .catch(error => {
        console.error("Error:", error);
        alert("Something went wrong. Check console.");
      });

    });
  }

});
