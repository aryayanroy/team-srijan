$(document).ready(function(){

    $("form").submit(function(e){
        $(this).find("button[type=submit]").prop("disabled", true).html("<i class='fa-solid fa-circle-notch fa-spin'></i>");
    })

    $(".delete-btn").click(function(e){
        e.preventDefault();
        if(confirm("Are you sure want to delete?")){
            window.location.href = window.location.href.split('?')[0] + "?delete=" + $(this).val();
        }
    })
})