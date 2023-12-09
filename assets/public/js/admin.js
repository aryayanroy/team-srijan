$(document).ready(function(){
    var url = window.location.href;
    var loading = "<i class='fa-solid fa-circle-notch fa-spin'></i>";

    window.find_btn = function(form){
        return form.find("button[type=submit]");
    }

    function def_action(action){
        if(action){
            action = 1;
        }else{
            action = 2;
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
        if(confirm("You are about to remove this entry from the lineup.")){
            var init_txt = btn.html();
            btn.prop("disabled", true).html(loading);
            var data = {
                id: btn.val(),
                action: 3
            };
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
    }

    window.load_data = function(data, callback) {
        var loading = $("#loading");
        data.push({name: "action", value: 0});
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
        if(!alert_msgs){
            alert("No response.");
        }else{
            $.each(alert_msgs, function(_, msg1){
                alert(msg1);
            })
        }

        $.each(console_msgs, function(_, msg2){
            console.log(msg2);
        })
    }

    window.null_rows = function(table){
        var span = table.find("tr:first-child>th").length;
        var row = $("<tr>");
        row.append("<td colspan='"+span+"' class='text-center'>No records found.</td>");
        table.append(row);
    }

})