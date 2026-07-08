$(document).ready(function () {
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        $('form, input').attr('autocomplete', 'off');
        // Name validation
        let namePattern = /^[A-Za-z\s]{3,50}$/;
        let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        let passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).+$/;


        //name
        $("#username").on("keypress", function (e) {
            let char = String.fromCharCode(e.which);

            if (!/^[a-zA-Z\s]+$/.test(char)) {
                e.preventDefault();
            }
        });
        $("#username").on("input blur", function () {
            this.value = this.value.replace(/[^a-zA-Z\s]/g, "").slice(0, 50);
            let username = $(this).val().trim();
            if (username === "") {
                $(".username_error").text("Name is required");
            } else {
                $(".username_error").text("");
            }
        });

        //email
        $("#email").on("input blur", function () {
            let email = $(this).val().trim();

            if (email === "") {
                $(".email_error").text("Email is required");
            } else if (!emailPattern.test(email)) {
                $(".email_error").text("Enter valid email");
            } else {
                $(".email_error").text("");
            }
        });


        //password
        $("#password").on("input blur", function () {
            this.value = this.value.slice(0, 20);

            let password = $(this).val().trim();

            if (password === "") {
                $(".password_error").text("Password is required");
            } else if (password.length < 8) {
                $(".password_error").text("Password must be at least 8 characters");
            } else if (!passwordPattern.test(password)) {
                $('.password_error').text("Password must contain uppercase, lowercase, number and special character");
            } else {
                $(".password_error").text("");
            }
        });



        $("#formAuthentication").submit(function (e) {
            e.preventDefault();


            let formData = new FormData(this);
            console.log(Object.fromEntries(formData.entries()));
            // debugger;
            $.ajax({
                url: "/register",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function (response) {
                    console.log(response);
                    if (response.status === "success") {
                        $("#successNotification").fadeIn();

                        setTimeout(function () {
                            window.location.href = "/";
                        }, 1000); // 1 second
                    } else if (response.status === "error") {
                        $("#errorNotification")
                            .text(response.message)
                            .fadeIn()
                            .delay(1000)
                            .fadeOut();
                    }
                },


                error: function (xhr) {

                    if (xhr.status === 422) {

                        $(".text-danger").text("");

                        $.each(xhr.responseJSON.errors, function (key, value) {
                            $(".error-" + key).text(value[0]);
                        });

                    } else {

                        $("#errorNotification")
                            .text(xhr.responseJSON.message)
                            .fadeIn()
                            .delay(3000)
                            .fadeOut();
                    }
                }
            });
        });


    });
