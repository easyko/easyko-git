$(function () {
//点击删除按钮
    $('.b-con .clear').live('click', function () {
        $(this).parent().parent().prev().find('.control').show();
        $(this).parent().parent().remove();
    });
//点击增加按钮
    $('.b-con .add').live('click', function () {
        var _a = $('.demo').clone();
        _a.removeClass('demo');
        var _n = $(".b-con:visible").length;
        _a.find("input").each(function(){
            $(this).attr("name",$(this).attr("name")+_n);
        })
        _a.find("select").each(function(){
            $(this).attr("name",$(this).attr("name")+_n);
        })
        $(this).parents('.write').append(_a);
    });
//file点击增加按钮
    $('.update .file-add').live('click', function () {
        var _a = $('.file-demo').clone();
        _a.removeClass('file-demo');
        var _n = $(".update .b-main:visible").length;
        _a.find("input").each(function(){
            $(this).attr("name",$(this).attr("name")+_n);
            $(this).attr("id",$(this).attr("id")+_n);
        })

        $(this).parents('.update').append(_a);
    });
//点击删除按钮
    $('.update .file-clear').live('click', function () {
        $(this).parents('.b-main').remove();
    });

//合同点击增加按钮
    $('.update-ht .file-add').live('click', function () {
        var _a = $('.ht-demo').clone();
        _a.removeClass('ht-demo');
        var _n = $(".update-ht .b-main:visible").length;
        _a.find("input").each(function(){
            $(this).attr("name",$(this).attr("name")+_n);
            $(this).attr("id",$(this).attr("id")+_n);
        })

        $(this).parents('.update-ht').append(_a);
    });


//合同点击删除按钮
    $('.update-ht .file-clear').live('click', function () {
        $(this).parents('.b-main').remove();
    });

    $(".b-main input[type='file']").live("change",function(){
        $(this).siblings("input").val($(this).val());
    })

//工单号点击增加按钮
    $('.ajax-updata-number .file-add').live('click', function () {
        var _a = $('.gd-demo').clone();
        _a.removeClass('gd-demo');
        var _num = $(this).parents(".make-con-main").index();
        var _n = $(this).parents(".make-con-main").find(".ajax-updata-number .b-main").length;

        _a.find("input").each(function(){
            $(this).attr("name",$(this).attr("name")+"-"+_num+"-"+_n);
            $(this).attr("id",$(this).attr("id")+"-"+_num+"-"+_n);
        })

        $(this).parents(".make-con-main").find(".ajax-updata-number").append(_a);
    });

//工单号点击删除按钮
    $('.ajax-updata-number .file-clear').live('click', function () {
        $(this).parents('.b-main').remove();
    });

})