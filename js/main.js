/*
 * Matrix v2.0.1 (c) 2014 ChinaPnR.com
 * MIT License | http://ued.chinapnr.com/matrix/license.txt
 */
$(function(){
    $('.left-nav-item dl').each(function () {
        if($(this).find("dd").length == 0){
            console.log($(this));
            $(this).find("u").css("visibility","hidden")
        }
    })

//    $(document).mousemove(function(e){
//        sceenMouseCoordinate(e);
//    })
//    $(".sidebar-menu").mouseleave(function(e){
//        hideleftmenu();
//    })
//    setTimeout(function(){
//        hideleftmenu();
//    },1200)

    //$(".sidebar-menu").css("height",$(document).height()-83+"px");



})

function sceenMouseCoordinate(t){
    e = t || window.event;
//这里可得到鼠标X坐标
    var pointX = e.pageX;
//这里可以得到鼠标Y坐标
    var pointY = e.pageY;
    if(pointX <= 10){
        showleftmenu();
    }
}

function hideleftmenu(){
    $(".sidebar-menu").stop().animate({"margin-left":"-180px"},400)
    $(".main .content").stop().animate({"margin-left":"15px"},400)
}

function showleftmenu(){
    $(".sidebar-menu").stop().animate({"margin-left":"0px"},400)
    $(".main .content").stop().animate({"margin-left":"180px"},400)
}