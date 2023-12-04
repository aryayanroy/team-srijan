$(document).ready(function(){
    var url = window.location.href;
    var loading = "<i class='fa-solid fa-circle-notch fa-spin'></i>";

    window.find_btn = function(form){
        return form.find("button[type=submit]");
    }

    function def_action(action){
        if(action){
            action = "insert";
        }else{
            action = "update";
        }
        return action;
    }

    window.submit_urlencoded = function(btn, data, action, callback){
        var init_txt = btn.html();
        btn.prop("disabled", true).html(loading);
        data.push({name: "action", value: def_action(action)});
        data = $.param(data);
        $.post(
            url,
            data
        ).done(function(response){
            callback(response);
        }).always(function(){
            btn.prop("disabled", false).html(init_txt);
        }).fail(function(jqXHR, txtStatus, errorThrown) {
            alert(txtStatus+" ("+jqXHR.status+"): "+errorThrown);
        })
    }

    window.submit_multipart = function(btn, data, action, callback){
        var init_txt = btn.html();
        btn.prop("disabled", true).html(loading);
        data.append("action", def_action(action));
        $.ajax({
            url: url,
            type: "POST",
            data: data,
            processData: false,
            contentType: false
        }).done(function(response){
            callback(response);
        }).always(function(){
            btn.prop("disabled", false).html(init_txt);
        }).fail(function(jqXHR, txtStatus, errorThrown) {
            alert(txtStatus+" ("+jqXHR.status+"): "+errorThrown);
        })
    }
    
    window.submit_delete = function(btn, callback){

    }

    window.load_data = function(data, callback) {
        var loading = $("#loading");
        data.push({name: "action", value: "select"});
        data = $.param(data);
        loading.on("shown.bs.modal", function(){
            $.post(
                window.location.href,
                data
            ).done(function (response) {
                callback(response);
            }).always(function () {
                loading.modal("hide");
            });
        });
        loading.modal("show");
    }

    window.response_messages = function(alert_msgs, console_msgs){
        if(alert_msgs.length == 0){
            alert("No response");
        }else{
            $.each(alert_msgs, function(_, msg1){
                alert(msg1);
            })
        }

        $.each(console_msgs, function(_, msg2){
            console.log(msg2);
        })
    }

})