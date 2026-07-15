$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

toastr.options.preventDuplicates = true;
$("#cancleUpdate").on("click", function (e) {
        e.preventDefault();

        window.location.href = $(this).attr("href");
    });

    // $("#email").prop("readonly", true);
    $("#email").prop("disabled", true);


    validateName("#name");
    validatePhone("#phone");
    validateDOB("#dob");
    // validateGenderField("input[name='gender']");
    validateImage("#profile_image");
    validateAddress("textarea[name='address']");
    validateBio("textarea[name='bio']");

    console.log('hiii');

    $('#updateProfileForm').submit(function(e){
        e.preventDefault();

        updateProfileValidation();

        let form = this;
        let formData = new FormData(this);
        console.log('faaaaaaaa');
        // debugger;
        $.ajax({
            url: $(form).attr('action'),
            type: $(form).attr('method'),
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (response) {
                if ((response.status = "success")) {
                    toastr.success(response.message);

                    $(".text-success").text("");

                    // Optional Redirect
                    setTimeout(function () {
                          window.history.back();
                    }, 1000);
                }
                if ((response.status = "error")) {
                    toastr.success(response.message);

                    // $("#formAuthentication")[0].reset();

                    $(".text-danger").text("");

                    // Optional Redirect
                    // setTimeout(function(){
                    //     window.location.href="/";
                    // },1000);
                }
            },

            error: function (xhr) {
                $(".text-danger").text("");

                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;

                    $.each(errors, function (key, value) {
                        $("." + key + "_error").text(value[0]);

                        toastr.error(value[0]);
                    });
                } else {
                    toastr.error("Something went wrong.");

                    console.log(xhr.responseText);
                }
            },
        })
    })
});
