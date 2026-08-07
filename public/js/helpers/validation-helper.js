

let namePattern = /^[A-Za-z\s]{3,50}$/;
let emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9-]+\.[a-zA-Z]{2,}(?:\.[a-zA-Z]{2,})?$/;
let passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).+$/;
// let phonePattern = /^[6-9]\d{9}$/;
let addressPattern = /^[A-Za-z0-9\s,.\-/#]+$/;

function validateName(input){
    $(input).attr({
        maxlength:50,
        minlength:3
    });

    $(input).on("keypress", function (e) {
        let char = String.fromCharCode(e.which);

        if (!/^[a-zA-Z\s]+$/.test(char)) {
            e.preventDefault();
        }
    });
    $(input).on("input blur", function () {

        this.value = this.value.replace(/[^A-Za-z\s]/g, "").slice(0, 50);

        let value = $(this).val().trim();

        let error = $("." + this.id + "_error");

        if (value === "") {
            error.text("Name is required");
        } else if (!namePattern.test(value)) {
            error.text("Enter valid name");
        } else {
            error.text("");
        }

    });
}

function validateEmail(input){
    $(input).attr({
        maxlength: 255,
        autocomplete: "email",
        spellcheck: false
    });

    $(input).on("keypress", function(e){

        if(e.which === 32){
            e.preventDefault();
        }

    });
    // Validate pasted text
    $(input).on("paste", function (e) {

        let text = (e.originalEvent || e).clipboardData.getData("text");

        if (/\s/.test(text)) {
            e.preventDefault();
        }

    });


    $(input).on("input blur",function(){

        let value=$(this).val().trim();

        let error=$("." + this.id + "_error");

        if(value===""){
            error.text("Email is required");
        }
        else if(value.length>255){
            error.text("Email must not exceed 255 characters");
        }
        else if(/\s/.test(value)){
            error.text("Email cannot contain spaces");
        }
        else if(!emailPattern.test(value)){
            error.text("Enter valid email");
        }
        else{
            error.text("");
        }

    });

}

function validatePassword(input){

    $(input).attr({
        maxlength: 64,
        minlength: 8,
    });

    $(input).on("input blur",function(){

        this.value=this.value.slice(0,64);

        let value=$(this).val().trim();

        let error=$("." + this.id + "_error");

        if(value===""){
            error.text("Password is required");
        }
        else if(value.length<8){
            error.text("Minimum 8 characters required");
        }
        else if(!passwordPattern.test(value)){
            error.text("Must contain uppercase, lowercase, number and special character");
        }
        else{
            error.text("");
        }

    });

}
function validatePhone(input){

    $(input).attr({
        maxlength: 10,
        minlength: 10,
    });

    $(input).on("keypress",function(e){

        let char=String.fromCharCode(e.which);

        if(!/^\d$/.test(char)){
            e.preventDefault();
        }

    });

    $(input).on("input blur",function(){

        this.value=this.value.replace(/\D/g,"").slice(0,10);

        let value=$(this).val().trim();

        let error=$("." + this.id + "_error");

        if(value===""){
            error.text("Phone number is required");
        }
        else if(value.length !== 10){
            error.text("Phone Number must be have 10 Digit");
        }
        // else if(!phonePattern.test(value)){
        //     error.text("Enter valid phone number");
        // }
        else{
            error.text("");
        }


    });

}
function validateDOB(input){

    $(input).on("input blur",function(){

        let value=$(this).val();

        let error=$("." + this.id + "_error");

        if(value===""){
            error.text("Date of Birth is required");
            return;
        }

        let birthDate=new Date(value);
        let today=new Date();

        birthDate.setHours(0,0,0,0);
        today.setHours(0,0,0,0);

        if(birthDate>today){
            error.text("Future date is not allowed");
            return;
        }

        if(birthDate.getTime()===today.getTime()){
            error.text("DOB cannot be today");
            return;
        }

        error.text("");

    });

}
// for dropdown menu
function validateGender(hiddenInput,button){

    $(document).on("click",".gender-option",function(e){

        e.preventDefault();

        $(hiddenInput).val($(this).data("value"));

        $(button).text($(this).text());

        $("." + hiddenInput.replace("#","") + "_error").text("");

    });

    $(button).on("blur",function(){

        validateGenderField(hiddenInput);

    });

    function validateGenderField(hiddenInput){

        let value=$(hiddenInput).val();

        let error=$("." + hiddenInput.replace("#","") + "_error");

        if(value===""){
            error.text("Please select gender");
            return false;
        }

        error.text("");
        return true;
    }

}
//for radio btn
function validateGenderField(input){

    $(input).on("change blur", function(){

        let error = $(".gender_error");

        if($("input[name='gender']:checked").length === 0){
            error.text("Please select gender");
            return false;
        }

        error.text("");
        return true;

    });

}

function validateImage(input){

    $(input).on("change",function(){

        let file=this.files[0];
        console.log(file);

        let error=$("." + this.id + "_error");
        console.log("image error :",error);

        if(!file){
            error.text("Profile image is required");
            return;
        }

        let allowed=[
            "image/jpeg",
            "image/jpg",
            "image/png",
            "image/webp"
        ];

        if(!allowed.includes(file.type)){
            error.text("Only JPG, JPEG, PNG and WEBP allowed");
            $(this).val("");
            return;
        }

        if(file.size>2*1024*1024){
            error.text("Maximum size is 2MB");
            $(this).val("");
            return;
        }

        let img=new Image();

        img.onload=function(){
            error.text("");
        };

        img.onerror=function(){
            error.text("Invalid image");
            $(input).val("");
        };

        img.src=URL.createObjectURL(file);

    });

}

function validateAddress(input){
     $(input).attr({
        maxlength: 255,
        minlength: 10,
    });

    $(input).on("input blur", function(){

        this.value = this.value.slice(0,255);

        let value = $(this).val().trim();

        let error = $("." + this.name + "_error");

        if(value === ""){
            error.text("Address is required");
        }
        else if(value.length < 10){
            error.text("Address must be at least 10 characters");
        }
        else if(!addressPattern.test(value)){
            error.text("Enter a valid address");
        }
        else{
            error.text("");
        }

    });

}

function validateBio(input){
    $(input).attr({
        maxlength: 500,
        minlength: 10,
    });

    $(input).on("input blur", function(){

        this.value = this.value.slice(0,500);

        let value = $(this).val().trim();

        let error = $("." + this.name + "_error");

        if(value.length > 500){
            error.text("Bio must not exceed 500 characters");
        }
        else{
            error.text("");
        }

    });

}

function CheckTerms(input) {
    $(input).on("change", function () {

        if ($(this).is(":checked")) {
            $(".terms_error").text("");
        } else {
            $(".terms_error").text("Please accept Terms & Conditions");
        }

    });
}


















function validateLoginForm(){
    let isValid = true;

    $(".error-text").text("");

    // Email
    let email = $("#email").val().trim();
    if (email === "") {
        $(".email_error").text("Email is required");
        // toastr.error('Email is required');
        isValid = false;
    }

    // Password
    let password = $("#password").val();

    if (password == "") {
        $(".password_error").text("Password is required");
        // toastr.error("Password is required");
        isValid = false;
    }


    return isValid;
}

function validateRegisterForm() {
    let isValid = true;

    $(".error-text").text("");

    let username = $("#username").val().trim();
    if (username === "") {
        $(".username_error").text("Username is required");
        // toastr.error("Username is required");
        isValid = false;
    }
    // else if (!namePattern.test(username)) {
    //     $(".username_error").text("Enter valid username");
    //     isValid = false;
    // }

    // Email
    let email = $("#email").val().trim();
    if (email === "") {
        $(".email_error").text("Email is required");
        // toastr.error('Email is required');
        isValid = false;
    }
    // else if (!emailPattern.test(email)) {
    //     $(".email_error").text("Enter valid email");
    //     isValid = false;
    // }

    // Phone
    let phone = $("#phone").val().trim();
    if (phone === "") {
        $(".phone_error").text("Phone number is required");
        // toastr.error('Phone number is required');
        isValid = false;
    }
    // else if (!phonePattern.test(phone)) {
    //     $(".phone_error").text("Enter valid phone number");
    //     isValid = false;
    // }

    // DOB
    let dob = $("#dob").val();

    if (dob == "") {
        $(".dob_error").text("Date of Birth is required");
        // toastr.error('Date of Birth is required');
        isValid = false;
    }

    // Gender
    if ($("#gender").val() == "") {
        $(".gender_error").text("Please select gender");
        // toastr.error("Please select gender");

        isValid = false;
    }

    // Password
    let password = $("#password").val();

    if (password == "") {
        $(".password_error").text("Password is required");
        // toastr.error("Password is required");
        isValid = false;
    } else if (password.length < 8) {
        $(".password_error").text("Minimum 8 characters");
        isValid = false;
    } else if (!passwordPattern.test(password)) {
        $(".password_error").text("Password is weak");
        isValid = false;
    }

    // Image
    let file = $("#profile_image")[0].files[0];

    if (file) {
        let allowed = ["image/jpeg", "image/jpg", "image/png", "image/webp"];

        if (!allowed.includes(file.type)) {
            $(".profile_image_error").text("Invalid image");
            // toastr.error("Invalid Image");

            isValid = false;
        }

        if (file.size > 2 * 1024 * 1024) {
            $(".profile_image_error").text("Max 2MB allowed");
            // toastr.error("Max 2MB allowed");

            isValid = false;
        }
    }

    // Terms validation
    if (!$("#terms-conditions").is(":checked")) {
        $(".terms_error").text("Please accept Terms & Conditions");
        isValid = false;
    } else {
        $(".terms_error").text("");
    }

    return isValid;
}


function updateProfileValidation(){
    let isValid = true;

    $(".error-text").text("");

    let name = $("#name").val().trim();
    if (name === "") {
        $(".name_error").text("name is required");
        isValid = false;
    }

    // Phone
    let phone = $("#phone").val().trim();
    if (phone === "") {
        $(".phone_error").text("Phone number is required");
        // toastr.error('Phone number is required');
        isValid = false;
    }

    // DOB
    let dob = $("#dob").val();

    if (dob == "") {
        $(".dob_error").text("Date of Birth is required");
        // toastr.error('Date of Birth is required');
        isValid = false;
    }

    // // Gender
    // if ($("#gender").val() == "") {
    //     $(".gender_error").text("Please select gender");
    //     // toastr.error("Please select gender");
    //     isValid = false;
    // }

    if ($("input[name='gender']:checked").length === 0) {
            $(".gender_error").text("Please select gender");
            return;
    }

    //address
    let address = $('#address').val();
    if (address == "") {
        $(".address_error").text("Address is required");
        // toastr.error('Date of Birth is required');
        isValid = false;
    }


    // Image
    let file = $("#profile_image")[0].files[0];

    if (file) {
        let allowed = ["image/jpeg", "image/jpg", "image/png", "image/webp"];

        if (!allowed.includes(file.type)) {
            $(".profile_image_error").text("Invalid image");
            // toastr.error("Invalid Image");

            isValid = false;
        }

        if (file.size > 2 * 1024 * 1024) {
            $(".profile_image_error").text("Max 2MB allowed");
            // toastr.error("Max 2MB allowed");

            isValid = false;
        }
    }

    return isValid;
}

/**
 * Category Name Field Real-Time Validation
 */
function validateCategoryName(input = "#categoryname") {
    let $input = $(input);
    let categoryPattern = /^[a-zA-Z0-9\s]+$/;

    $input.attr("maxlength", 50);

    $input.on("keypress", function (e) {
        let char = String.fromCharCode(e.which);
        if (!categoryPattern.test(char)) {
            e.preventDefault();
        }
    });

    $input.on("input blur", function () {
        let value = $(this).val().trim();
        let $err = $(".error-categoryname, .categoryname_error");

        if (value === "") {
            $err.text("Category name is required");
        } else if (value.length < 5) {
            $err.text("Category name must be at least 5 characters");
        } else if (value.length > 50) {
            $err.text("Category name cannot exceed 50 characters");
        } else if (!categoryPattern.test(value)) {
            $err.text("Only letters, numbers and spaces allowed");
        } else {
            $err.text("");
        }
    });
}

/**
 * Category Description Field Real-Time Validation
 */
function validateCategoryDescription(input = "#description") {
    let $input = $(input);

    $input.attr("maxlength", 1000);

    $input.on("input blur", function () {
        let value = $(this).val().trim();
        let $err = $(".error-description, .description_error");

        if (value === "") {
            $err.text("Description is required");
        } else if (value.length > 1000) {
            $err.text("Description cannot exceed 1000 characters");
        } else {
            $err.text("");
        }
    });
}

/**
 * Category Status Field Real-Time Validation
 */
function validateCategoryStatus(input = "#status") {
    $(input).on("change blur", function () {
        let value = $(this).val();
        let $err = $(".error-status, .status_error");

        if (!value || value === "") {
            $err.text("Status is required");
        } else {
            $err.text("");
        }
    });
}

/**
 * Dynamic Category Form Validation (called before submit)
 * @param {string|jQuery} formSelector - Selector or element of the form to validate
 * @returns {boolean} isValid - Returns true if form is valid, false otherwise
 */
function validateCategoryForm(formSelector = "#addCategoryForm") {
    let $form = $(formSelector);
    let isValid = true;
    let categoryPattern = /^[a-zA-Z0-9\s]+$/;

    // Reset error containers inside form
    $form.find(".text-danger").text("");

    // Category Name Validation
    let $categoryNameInput = $form.find("#categoryname, [name='categoryname']");
    if ($categoryNameInput.length) {
        let categoryName = $categoryNameInput.val().trim();
        let $err = $form.find(".error-categoryname, .categoryname_error");

        if (categoryName === "") {
            $err.text("Category name is required");
            isValid = false;
        } else if (categoryName.length < 5) {
            $err.text("Category name must be at least 5 characters");
            isValid = false;
        } else if (categoryName.length > 50) {
            $err.text("Category name cannot exceed 50 characters");
            isValid = false;
        } else if (!categoryPattern.test(categoryName)) {
            $err.text("Only letters, numbers and spaces allowed");
            isValid = false;
        }
    }

    // Description Validation
    let $descriptionInput = $form.find("#description, [name='description']");
    if ($descriptionInput.length) {
        let description = $descriptionInput.val().trim();
        let $err = $form.find(".error-description, .description_error");

        if (description === "") {
            $err.text("Description is required");
            isValid = false;
        } else if (description.length > 1000) {
            $err.text("Description cannot exceed 1000 characters");
            isValid = false;
        }
    }

    // Status Validation
    let $statusInput = $form.find("#status, [name='status']");
    if ($statusInput.length) {
        let status = $statusInput.val();
        let $err = $form.find(".error-status, .status_error");

        if (!status || status === "") {
            $err.text("Status is required");
            isValid = false;
        } else if (status !== "active" && status !== "inactive") {
            $err.text("Status must be active or inactive");
            isValid = false;
        }
    }

    return isValid;
}

