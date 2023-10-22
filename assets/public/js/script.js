$(document).ready(function(){
    var url = window.location.href.split("/");
    var page = url[url.length - 1].split("?")[0].split(".")[0];
    $("#page-header").find("a.nav-link[href='"+page+"']").addClass("active");
})