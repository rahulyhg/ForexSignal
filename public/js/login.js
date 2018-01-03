$("#login-button").click(function (event) {
  event.preventDefault();
  $('form').fadeOut(500);
  $('.wrapper').addClass('form-success');
  var allData = $("form").serializeArray();
  $.ajax({
    method: 'POST',
    url: "login.php",
    data: allData,
    dataType: 'json',
    success: function (response) {
      if (response == 'login') {
        window.location = "index.php";
      } else {
        $('form').fadeIn(500);
        $('.wrapper').removeClass('form-success');
      }
    },
    error: function (response) {
      if (response.responseText == 'login') {
        window.location = "index.php";
      } else {
        $('form').fadeIn(500);
        $('.wrapper').removeClass('form-success');
      }
    }
  });
});