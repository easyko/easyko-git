/*
* Matrix v2.0.1 (c) 2014 ChinaPnR.com
* MIT License | http://ued.chinapnr.com/matrix/license.txt
*/

var isIE6 = $.browser.msie && parseInt($.browser.version) <= 6;

// 焦点至input时, 打开tip标签
$(document).on('focus','input.form-unit',function(e){
  var group = $(this).closest('.form-group');
  //当input得到焦点时，先去除原有打开的标签，然后显示 tip 
  $(group).find(".active").removeClass("active");
  $(group).find(".tip").addClass("active");
})
// input失焦时, 检查验证项
$(document).on('blur','input.form-unit',function(e){
  //先关闭所有打开的标签，取消出错红框
  $(this).removeClass("form-unit-error");
  $(this).closest('.form-group').find(".active").removeClass("active");
  //检查验证项
  setSingleInputAfterCheck(this);
})

function setSingleInputAfterCheck(input,skipRequire,skipAjax){
  var formGroup = $(input).closest('.form-group');
  formGroup.find(".okay.active").removeClass('active');
  if(formGroup.find(".error.active").length>0) return;
  var checkReturn = checkSingleInput(input,skipRequire,skipAjax);
  if (checkReturn==="skip") return;
  if (checkReturn!==undefined){
    markTip(checkReturn,input);
  }else{
    formGroup.find(".okay").addClass("active"); 
  }
}

function checkSingleInput(input,skipRequire,skipAjax){
  var re = $(input).val();
  var form = $(input).closest(".form");
  
  //不包括任何条件则跳过
  if ( $(input).attr("required")===undefined &&
       $(input).attr("match")===undefined &&
       $(input).attr("equal")===undefined &&
       $(input).attr("diff")===undefined
  ) return "skip";
  //确认必填项
  if($(input).attr("required")==="required" && 
    (re ==="" || ($(input).attr('type')==="checkbox" && $(input).attr('checked')!=="checked"))
  ){
    if(skipRequire){
      return "skip";
    }
    //console.log(input)
    return "required";
  }

  //确认match条件
  // usrMp:    11位数字;
  // email:     x@x.x
  // usrId:     帐号长度6-25位
  // userPw:    密码长度6-16位
  // smsCode:   只能是6位
  // bankCard:  银行卡号长度10-32位数字
  var match = $(input).attr("match");
  var name  = $(input).attr("name");

  //console.log($(input).attr('name')+" match: "+match);
  if(match!==undefined){
    if (re==="") return "skip";
    var matchs=[];
    matchs["usrMp"]  = new RegExp(/^1[0-9]{10}$/);
    matchs["email"]   = new RegExp(/^([a-zA-Z0-9_\.\-]+)(@{1})([a-zA-Z0-9_\.\-]+)(\.[a-zA-Z0-9]{1,3})$/);
    matchs["usrId"]  = new RegExp(/^[0-9a-zA-Z_.@-]{6,25}$/);
    matchs["userPw"]  = new RegExp(/^.{6,16}$/);
    matchs["smsCode"]  = new RegExp(/^0?\d{6}$/);
    matchs["bankCard"]  = new RegExp(/^[0-9]{10,32}$/);

    //判断密码强度
    if (match==="userPw"){
      setPwdStr(input);
    }

    if (!re.match(matchs[match]) || 
        (match==="certId" && name==="certId" && !getIdCardInfo(re).isTrue)) { 
      return "match";
    }
  }
  //必须相同
  if($(input).attr("equal")!==undefined){
    //需要与其相同的input
    var equal     = $(form).find("input[name="+$(input).attr("equal")+"]"); 
    var reEqual   = $(equal).val();
    var thisTip   = $(input).closest('.form-group').find(".equal");
    var equalTip  = $(form).find(equal).closest('.form-group').find(".equal");
    var thisOkay  = $(input).closest('.form-group').find(".okay");
    var equalOkay = $(equal).closest('.form-group').find(".okay");

    //如果两个任意一个为空，则忽略;
    if(reEqual!=="" && re!=="" ){
      if(re!==reEqual){
        if(thisTip.length === 0){
          equalOkay.removeClass("active");
          markTip("equal",equal);
        }else{
          thisOkay.removeClass("active");
          return "equal";
        }
      }else{
        if(thisTip.length === 0){
          equalTip.removeClass("active");
          equal.removeClass("form-unit-error");
          equalOkay.addClass("active");
        }
      }
    }else{
      if(thisTip.length === 1){
        //当第一项为空,第二项输入任何都不打勾
        return "skip";
      }
    }
  }

  //必须不同
  if($(input).attr("diff")!==undefined){
    var diff = $(form).find("input[name="+$(input).attr("diff")+"]");
    var reDiff = $(diff).val();
    var thisTip = $(input).closest('.form-group').find(".diff");
    var diffTip = $(form).find(diff).closest('.form-group').find(".diff");
    var thisOkay = $(input).closest('.form-group').find(".okay");
    var diffOkay = $(diff).closest('.form-group').find(".okay");

    //如果两个任意一个为空，则忽略;
    if(reDiff!=="" && re!==""){
      if(re===reDiff){
        if(thisTip.length === 0){
          diffOkay.removeClass("active");
          markTip("diff",diff);
        }else{
          thisOkay.removeClass("active");
          return "diff";
        }
      }else{
        if(thisTip.length === 0){
          $(diffTip).removeClass("active");
          $(diff).removeClass("form-unit-error");
          diffOkay.addClass("active");
        }
      }
    }
  }
}

//标记错误
function markTip(type,input){
  var tip;
  if(typeof type==="object"){
    tip = type[0];
    $(input).closest('.form-group').find("."+tip+" p").text(type[1]);
  }else{
    tip = type;
  }
  if(tip!=='checking' && tip!=='okay'){
    $(input).addClass("form-unit-error");
    if ($(input).attr('type')==="hidden"){
      $(input).closest('.form-group').find('.form-unit').addClass("form-unit-error");
    }
  }
  $(input).closest('.form-group').find("."+tip).addClass("active");          
}

//银行卡号放大，并每4个字体加空格显示
$(document).on('focus','input.input-text-zoom',function(e){
  checkCardId(this);
})
$(document).on('keyup','input.input-text-zoom',function(e){
  checkCardId(this);
})
function checkCardId(input){
  var t = $(input).parent().find('.tips-content-card');
  var v = input.value;
  v!=="" ? t.show() : t.hide();
  //每4个字符加空格
  t.text(v.replace(/(\d{4})(?=\d)/g,"$1 "));
}


/*
 *   弹出框
 *   显示
 */
function modalShow(modal){
  if($('#'+modal+':visible')[0]) return;
  if(!$('.modal-backdrop')[0] && $('#'+modal)[0]){
    $('body').append("<div class='modal-backdrop'></div>");
    modalLayout();
  }
  $('#'+modal).show();
  modalCenter(modal);  
}

$(document).on('click', '.modal .close', function(){
  modalHide()
});

function modalHide(){
  $('.modal').hide();
  $('.modal-backdrop').remove();
}

//遮罩满屏
function modalLayout(){
  $('.modal-backdrop').css({
    'width':$('body').width(),
    'height':$('body').height()
  });
}

//窗体居中

function modalCenter(){
  $('.modal:visible').each(function(){
    if ($(this).data("height")===undefined){
      $(this).data("height",$(this).height());
      $(this).data("dbHeight",$(this).find('.modal-bd').height());
    }
    var h = $(this).data("height");
    var dbh = $(this).data("dbHeight");
    var maxh = $(window).height() - 50;
    var db = $(this).find('.modal-bd');

    if(h > maxh){
      $(db).css('height',maxh-(h-dbh));
    }else{
      $(db).css('height',"auto");
    }

    var t = isIE6 ? ($(window).height()/ 2) + $(window).scrollTop() + "px" : ($(window).height()/ 2) + "px";
    $(this).css({'top':t,'margin-top':-$(this).height()/2});
  });

}

//改变窗体重新调整位置
window.onresize = function() {
  modalLayout();
  modalCenter();
}

window.onscroll = function() {
    modalLayout();
    modalCenter();
}

document.onkeyup = function(event) {
  var evt = window.event || event;
  var code = evt.keyCode ? evt.keyCode : evt.which;
  if(code == 27) {
    modalHide();
  }
}