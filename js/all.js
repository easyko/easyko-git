/**
 * Created by nero.li on 2015/6/12.
 */

var isIE = !! window.ActiveXObject;
var isIE6 = isIE && !window.XMLHttpRequest;
var isIE8 = isIE && !! document.documentMode;
var isIE7 = isIE && !isIE6 && !isIE8;

/** 弹出层开始 **/
//调整位置
function BOX_layout() {
    $('#BOX_overlay').css({
        "height": document.documentElement.clientHeight + 'px',
        "display": ""
    })
}

//显示


function BOX_show(box) {
    if($("#" + box + ":visible")[0]) {
        return;
    }
    if(!$('#BOX_overlay')[0]) {
        $('body').append("<div id='BOX_overlay'></div>");
        BOX_layout();
    }
    BOX_center(box);
    //改变窗体重新调整位置
    window.onresize = function() {
        BOX_layout();
        BOX_center(box);
    }

    window.onscroll = function() {
        if(isIE6) {
            BOX_layout();
            BOX_center(box);
        }
    }

    document.onkeyup = function(event) {
        var evt = window.event || event;
        var code = evt.keyCode ? evt.keyCode : evt.which;
        if(code == 27) {
            BOX_remove(box);
        }
    }
}

function BOX_center(box) {
    var t = isIE6 ? (($(window).height() - (parseInt($("#" + box).height()))) / 2) + $(window).scrollTop() + "px" : (($(window).height() - (parseInt($("#" + box).height()))) / 2) + "px"
    var l = isIE6 ? (($(window).width() - (parseInt($("#" + box).width()))) / 2) + $(window).scrollLeft() + "px" : (($(window).width() - (parseInt($("#" + box).width()))) / 2) + "px"
    $("#" + box).css({
        "z-index": 99999,
        "display": 'block',
        "left": l,
        "top": t
    })
}
//移除


function BOX_remove(box) {
    window.onscroll = null;
    window.onresize = null;
    $('#BOX_overlay').remove();
    $('#' + box).hide();
    var selects = document.getElementsByTagName('select');
    for(i = 0; i < selects.length; i++) {
        selects[i].style.visibility = "visible";
    }
} /** 弹出层结束 **/


/** 密码框默认清空 **/
$("input[type=password]").val("");
/** 输入框变化 **/
$('.input_change').hover(function() {
    $(this).css('border', '1px solid #7ea3c9');
}, function() {
    $(this).css('border', '1px solid #D6D6D6');
});


/** 输入框提示 **/
$('.err_info').live("focus",function() {
    $(this).nextAll(".info").show();
});
$('.err_info').live("focusout",function() {
    $(this).nextAll(".info").hide();
});

/** 输入框提示1 **/
$('.interval-info').live("focus",function() {
    if($(this).parent().data("info-msg")=='') return;
    $(this).nextAll(".info").find(".form-qj-w p").text($(this).parent().data("info-msg"));
    $(this).nextAll(".info").show();
});
$('.interval-info').live("focusout",function() {
    $(this).nextAll(".info").hide();
});

function showInfo(e,f,g){
    $("#popdiv .poptext span").text(f);
    $("#popdiv .poptext span").removeClass();
    switch (e){
        case "error":
            $("#popdiv .poptext span").addClass("error");
            break;
        case "worning":
            $("#popdiv .poptext span").addClass("worning");
            break;
        case "success":
            $("#popdiv .poptext span").addClass("success");
            break
        default :
            break
    }
    BOX_show("popdiv");
    if(typeof g=="function"){
        $("#popdiv .btn-add").attr("href","javascript:void(0)");
        $("#popdiv .btn-add").unbind().click(function () {
            g();
        })
    }
}

/*
 * jquery ajax上传
 * e 上传input id
 * f 上传地址
 * */
function ajaxFileUpload(e,f) {
    var _e = e;
    if($("#"+_e).val()==""){
        showInfo("error","请选择上传文件！");
        return;
    }
    $.ajaxFileUpload
    (
        {
            url: f,
            secureuri: false,
            fileElementId: _e,
            dataType: 'json',
            timeout:90000,
            data: {},
            success: function (data) {
                if(data.status=="SUCCESS"){
                    showInfo(data.msg,"success")
                }else{
                    showInfo(data.msg,"error")
                }
            },
            error: function () {
                showInfo("error","上传失败，请重新上传！")
            }
        }
    )
    return false;
}