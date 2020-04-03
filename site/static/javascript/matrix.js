
$(function(){
  var old = -1;
  $(document).on('click','.system-bar > ul > li > a',function(){
    var $body = $('body');
    var $li = $(this).parent();
    if($(this).parent().index()==old){
        $body.removeClass('sidebar-open');
        old = -1;
        return;
    }
    old = $(this).parent().index();
    $body.addClass('sidebar-open');
    var $root = $(this).closest('.sidebar');
    if($li.index()==-1) return;
    $(".main-bar ul:eq("+$li.index()+")").show().siblings().hide();
  })

    //点击外部删除下拉框
    $(document).click(function(e){
          if($(e.target).closest(".system-bar").length<=0){
              $('body').removeClass('sidebar-open');
          }
    })


  /*** 设置二级菜单高度，css动画需要高度的绝对值 ***/
  var styleTxt = '\n';
  $('ul.nav ul.nav').each(function(){
    var liLength = $(this).find('li').length;
    var $root = $(this).closest('.sidebar');
    var idx = $(this).parent().index()+1;
    if($root.hasClass('main-bar')){
      styleTxt += '.main-bar li:nth-child('+idx+').active ul.nav{height:'+liLength*40+'px}\n';
      $(this).closest('ul').prev().append('<b>›</b>')
    }else{
      styleTxt += '.sidebar-open .system-bar li:nth-child('+idx+').active ul.nav{height:'+liLength*40+'px}\n';
    }
  })
  $("<style></style>").text(styleTxt).appendTo($("head"));
  /*** 初始 最左侧菜单 的 tooltip ***/
  $('.system-bar [data-toggle="tooltip"]').tooltip({'container': 'body'});
})