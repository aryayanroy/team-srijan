$(document).ready(function(){
    var url = window.location.href.split("/");
    var page = url[url.length - 1].split("?")[0].split(".")[0];
    $("#page-header").find("a.nav-link[href='" + page + "'], a.dropdown-item[href='" + page + "']").addClass("active").filter(".dropdown-item").closest(".dropdown").children(".nav-link").addClass("active");
})