$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    toastr.options.preventDuplicates = true;

    $("#registerLink").on("click", function (e) {
        e.preventDefault();

        window.location.href = $(this).attr("href");
    });
    // $('form, input').attr('autocomplete', 'off');

    validateEmail("#email");
    validatePassword("#password");

    // Submit Login Form
    $("#loginform").submit(function (e) {
        e.preventDefault();

        if (!validateLoginForm()) {
            toastr.error("Please fix all validation errors.");
            return;
        }

        let formData = new FormData(this);

        console.log(Object.fromEntries(formData.entries()));

        $.ajax({
            url: "/login",

            type: "POST",

            data: formData,

            processData: false,

            contentType: false,

            dataType: "json",

            success: function (response) {
                if ((response.status = "success")) {
                    toastr.success(response.message);

                    $("#loginform")[0].reset();

                    $(".text-success").text("");

                    // Optional Redirect
                    setTimeout(function () {
                        window.location.href = "/dashboard";
                    }, 1000);
                }
                if ((response.status = "error")) {
                    toastr.error(response.message);

                    // $("#formAuthentication")[0].reset();

                    $(".text-danger").text("");
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
        });
    });
});
