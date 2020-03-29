$(document).ready(function () {
  // ====================================================================
  /* ****** костыль для инпута бутстрап****** */
  $("#add_name").on("change", function () {
    var fileName = $(this).val().split("\\").pop();
    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    readURL(this);
});
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#image_thrumb').attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
}
  // переключить во включенное или выключенное состояние кнопку submit
  // чекбокс - изменение состояния кнопки субмит
  $('#formCheck').change(function () {
    // changeAgreement(this);
    var _check = $(this);
    _check.closest('form').find('[type="submit"]').prop('disabled', !this.checked);
  });

  function changeStateSubmit(element, state) {
    $(element).closest('form').find('[type="submit"]').prop('disabled', state);
  };
  // изменение состояния кнопки submit в зависимости от состояния checkbox agree
  function changeAgreement(element) {
    changeStateSubmit(element, !element.checked);
  };
  /* Button UP Down scroll */
  $(window).scroll(function () {
    if ($(this).scrollTop() > 100) {
      $('.scroll_btn').fadeIn();
    } else {
      $('.scroll_btn').fadeOut();
    }
  });
  $('.scroll_btn').click(function () {
    $("html, body").animate({
      scrollTop: 0
    }, 600);
    return false;
  });
  /* ********** SEND MAIL CONTACT FORM ******** */
  /*   $('#contact_form').on('submit', function (e) {
      if($('#formCheck').prop("checked") == false){
        event.preventDefault();
        event.stopPropagation();
      return false;
      }
      else{
         e.preventDefault();
      }
        $.ajax({
            type: $(this).attr('method'),
            url: $(this).attr('action'),
            data: $(this).serialize()
        })
        .done(function (data) {
          var obj = jQuery.parseJSON(data);
          var err = "";
          $.each(obj, function (k, v) {
              $.each(v, function (k1, v1) {
                  err += "------------------------- \n"
                  $.each(v1, function (k2, v2) {
                      err += k2 + ' : ' + v2 + "\n";
                  });
              });
          });
         alert(err);
          if(obj[0]['status']['result'] == 'OK'){
             location.reload(true);
          }
      }, "json")
        .fail(function() {
            alert('Действие не удалось. Попробуйте снова.');
        });
       // return false;
    }); */

    /* **********END SEND MAIL CONTACT FORM ******** */
}); /* readyfunction */
  (function () {
  'use strict';
  window.addEventListener('load', function () {
    // Get the forms we want to add validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function (form) {
      form.addEventListener('submit', function (event) {
        if (form.checkValidity() === false) {
          $('#formCheck').prop("checked", false);
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();