function recaptcha(specifier)
{
    $(".old_recaptcha").show();
    $(".old_recaptcha").detach().appendTo(specifier);
    jQuery("#recaptcha_reload").click();
}

function search()
{
    query = encodeURI($("#srch-term").val());
    page_no = encodeURI($("#current_page").val());
    url = "index.php?p=" + page_no + "&q=" + query;
    window.location.replace(url);
    return false;
}

function reply()
{
    $('body').css('cursor', 'progress');
    url = "api.php?call=reply";
    data = {
        "id": $("#view_id").val(),
        "message": $("#reply_message").val(),
        "recaptcha_challenge_field": $("#recaptcha_challenge_field").val(),
        "recaptcha_response_field": $("#recaptcha_response_field").val()
    };

    $.ajax({
        type: "POST",
        dataType: "json",
        url: url,
        data: data,
        success: function( data ) {
            if(typeof data["error"] == "undefined") {
                $("#close_reply").click();
                $.notify("Message sent", "success");
            } else {
                $.notify(data["error"], "error");
            }
        },
        error: function(data) {
            $.notify("Unable to post reply.", "error");
        },
        complete: function(xhdr, status) {
            $('body').css('cursor', 'default');
            reset_buttons();
        }
    });
}

function reset_buttons()
{
    $("#do_post").prop('disabled', false);
    $("#do_post").text('Post listing');
    $("#do_reply").prop('disabled', false);
    $("#do_reply").text('Send message');
}

function create()
{
    recaptcha(".new_post_recaptcha");
}

function view(id)
{
    $('body').css('cursor', 'progress');
    recaptcha(".view_post_recaptcha");
    url = "api.php?call=view";
    data = {
        "id": encodeURIComponent(id)
    };

    $.ajax({
        type: "POST",
        dataType: "json",
        url: url,
        data: data,
        success: function( data ) {
            if(typeof data["error"] == "undefined") {
                $("#view_title").html(data["title"]);
                $("#view_description").html(data["description"]);
                $("#view_id").val(data["id"]);
                $("#view_post").modal("toggle");
            } else {
                $.notify(data["error"], "error");
            }
        },
        error: function(data) {
            $.notify("Unable to view listings.", "error");
        },
        complete: function(xhdr, status) {
            $('body').css('cursor', 'default');
        }
    });
}

function post()
{
    $('body').css('cursor', 'progress');
    url = "api.php?call=post";
    data = {
        "email": $("#new_email").val(),
        "sell_skill": $("#new_sell_skill").val(),
        "buy_skill": $("#new_buy_skill").val(),
        "title": $("#new_title").val(),
        "description": $("#new_description").val(),
        "recaptcha_challenge_field": $("#recaptcha_challenge_field").val(),
        "recaptcha_response_field": $("#recaptcha_response_field").val()
    };

    $.ajax({
        type: "POST",
        dataType: "json",
        url: url,
        data: data,
        success: function( data ) {
            if(typeof data["error"] == "undefined") {
                $("#close_post").click();
                list("");
                $.notify("New listing created", "success");
            } else {
                $.notify(data["error"], "error");
            }
        },
        error: function(data) {
            $.notify("Unable to post listings.", "error");
            reset_buttons();
        },
        complete: function(xhdr, status) {
            $('body').css('cursor', 'default');
            reset_buttons();
        }
    });
}

function list(q)
{
    $('body').css('cursor', 'progress');
    html  = '<div class="loading_listings">';
    html += 'Loading listings ... please wait';
    html += '</div>';
    $("#list_main").html(html);

    page_no = $("#current_page").val();
    url  = "api.php?call=list&q=" + encodeURIComponent(q);
    data = {
        "p": page_no
    };

    $.ajax({
        type: "POST",
        dataType: "json",
        url: url,
        data: data,
        success: function( data ) {
            if(typeof data["error"] == "undefined") {
                html = '<tbody>';
                html += '<tr>';
                html += '<th id="skill-heading">Can offer</th>';
                html += '<th id="wants-heading">Wants in return</th>';
                html += '<th>Title</th>';
                html += '</tr>';
                $.each( data, function( key, val ) {
                    html += '<tr class="clickable" onclick="view(' + data[key]["id"] + ');">';
                    html += '<td><div id="skill-content">' + data[key]["sell_skill"] + '</div></td>';
                    html += '<td><div id="wants-content">' + data[key]["buy_skill"] + '</div></td>';
                    html += '<td><div id="title-content"><a href="#">' + data[key]["title"] + '</a></div></td>';
                    html += '</tr>';
                });

                if(data.length == 0){
                    html += '<tr><td colspan="3"><center>There are no listings available at this time.</center></td></tr>';
                }

                html += '</tbody>';
                $("#list_main").html(html);
                if($('.loading_listings').length) {
                    $(".loading_listings").remove();
                } 
                $('.clickable').hover(function() {
                    $(this).addClass('row-hover');
                }, function() {
                    $(this).removeClass('row-hover');
                });
            } else {
                $.notify(data["error"], "error");
            }
        },
        error: function(data) {
            $.notify("Unable to show listings.", "error");
        },
        complete: function(xhdr, status) {
            $('body').css('cursor', 'default');
        }
    });
}


