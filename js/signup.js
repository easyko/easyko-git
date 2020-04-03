 $(function(){
  //click to touch
  try{       
  FastClick.attach(document.body);
  }catch(e){}
  //调整帐号前缀所占用的长度
  $(".form-text-prefix").next().data("width", $(".form-text-prefix").next().width());
  $( window ).resize(function() {
    resetPrefixInput();
  });
  function resetPrefixInput(){
    if($( window ).width()>720){
      $(".form-text-prefix").each(function(){
        var nextInput = $(this).next();
        $(nextInput).width($(nextInput).data("width")-$(this).width()-5)
      });
    }else{

      $(".form-text-prefix").each(function(){
        var nextInput = $(this).next();
        $(nextInput).width($('input[name=usrName]').width() - $(this).width()-5);
        $('input[name=usrId]').css("left",($(this).width()+5)+"px");
      });
    }
  }
  resetPrefixInput();

  $(document).on('focus','input.form-unit',function(e){
    var form  = $(this).closest(".form");
    var group = $(this).closest('.form-group');
    //当input得到焦点时，先去除原有打开的标签，然后显示 tip 
    $(group).find(".active").removeClass("active");
    $(group).find(".tip").addClass("active");

    //焦点至一组相当项的第一个input时，第二个的提示将隐藏（ie6层的BUG）
    if($(this).attr("equal")!==undefined){
      var equal       = $(form).find("input[name="+$(this).attr("equal")+"]"); 
      var thisTip     = $(group).find(".equal");
      var equalGroup  = $(equal).closest('.form-group');
      var equalTip    = $(equalGroup).find(".active");
      var equalOkay   = $(equalGroup).find(".okay");
      
      if(thisTip.length === 0){
        equalTip.removeClass("active");
      }
    }
  })

  //检查所有form里的字段是否合法，页面刚打开时，require字段需要忽略
  checkAllInput($("#regInfoForm"),true);

  //检查所有不是hidden的input
  function checkAllInput(form,skipRequire,skipAjax){
    $(form).find("input").each(function(){
      if($(this).closest('.form-group').is(':hidden')) return;
      setSingleInputAfterCheck(this,skipRequire,skipAjax);
    })
    //$(".form-unit-error").first().focus();
    goToError();
  }
  function goToError(){
    //移动至出错提示标签位置
    var focusInput = $(".errFocus .form-tips.error.active").first();
    if(focusInput.length>0 ){
      $('html,body').animate({scrollTop: $(focusInput).offset().top-50},'medium', function(){
        //$(focusInput).focus();
      });
    }
  }

  function setSingleInputAfterCheck(input,skipRequire,skipAjax){
    $(input).closest('.form-group').find(".okay.active").removeClass('active');
    if($(input).closest('.form-group').find(".error.active").length>0) return;
    //if()
    //$(input).closest('.form-group').find(".active").removeClass("active");
    var checkReturn = checkSingleInput(input,skipRequire,skipAjax);
    if (checkReturn==="skip") return;
    if (checkReturn!==undefined){
      markTip(checkReturn,input);
    }else{
      $(input).closest('.form-group').find(".okay").addClass("active"); 
    }
  }


  var checkingNum = 0;
  function getSingleCheckByAjax(url,data,input,inputExt){
    //url = url.substr(1);
    //alert(url)
    checkingNum++;
    $.ajax({
      url: url,
      type: 'post',
      dataType: 'json',
      data: data
    })
    .done(function(resp) {
      if(resp.error){
        var err = ["errorInfo",resp.error];
        markTip(err,input);
      }else{
        if(!$(input).is(":focus")){
          markTip("okay",input);
        }
        if(!$(inputExt).is(":focus")){
          markTip("okay",inputExt);
        }
      }
    }).always(function(resp) {
      //alert(resp);
      //$(input).closest('.form-group').find(".checking").removeClass("active");
      checkingNum--;
    })
  }

  function checkSingleInput(input,skipRequire,skipAjax){
    //console.log(input)
    //console.log($(input).attr("required"))
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
    // mobile:    11位数字;
    // email:     x@x.x
    // usrId:     帐号长度6-25位
    // userPw:    密码长度6-16位
    // smsCode:   只能是6位
    // bankCard:  银行卡号长度10-32位
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

      if (!re.match(matchs[match]) || (match==="certId" && name==="certId" && !getIdCardInfo(re).isTrue)) { 
        return "match";
      }

      var merCustId = $("#merCustId").val();
      if(!skipAjax){
        if (match==="usrId"){
          getSingleCheckByAjax('/muser/reg/checkLoginId',{
            loginId: $("#usrPrefix").val()+re,
            merCustId: merCustId
          },input)
          return "checking";
        }else if (match==="certId"){
          var usrName = $("input[name=usrName]").val(),
              certId  = $("input[name=certId]").val()
          $("input[name=certId]").closest(".form-group").removeClass("active");
          $("input[name=usrName]").closest(".form-group").removeClass("active");
          if(usrName!=="" && certId!==""){
            getSingleCheckByAjax('/muser/reg/checkCertId',{
              usrName: usrName,
              certId: certId,
              merCustId: merCustId,
              certType: $("#certType").val()
            },$("input[name=certId]"),$("input[name=usrName]"))
            markTip("checking",$("input[name=certId]"))
            return "skip";
          }else{
            if(usrName===""){
              markTip("required",$("input[name=usrName]"))
            }
            return "skip";
          }
        }else if (match==="usrMp"){
          getSingleCheckByAjax('/muser/ajax/checkUsrMp',{
            usrMp: re,
            merCustId: merCustId
          },input)
          return "checking";
        }
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

  $(document).on('blur','input.form-unit',function(e){
    
    //先关闭所有打开的标签，取消出错红框
    $(this).removeClass("form-unit-error");
    $(this).closest('.form-group').find(".active").removeClass("active");

    setSingleInputAfterCheck(this)
  })
  // 密码安全等级
  $(document).on('keyup','input[name=loginPwd],input[name=transPwd]',function(e){
    setPwdStr(this);
  })
  function setPwdStr(input){
    var re = $(input).val(); 
    var w = pwdStrength(re);
    var g = $(input).closest(".form-group");
    var pwi = $(g).find(".pw-secure i");
    var pwb = $(g).find(".pw-secure b");
    pwb.css("background-color","#e4e7ea");
    if (w===0){
      $(pwi).removeClass().addClass("pw-secure-none");
      $(g).find(".pw-secure-text").text('无').css("color","#666");

    }else if (w===1){
      $(pwi).removeClass().addClass("pw-secure-low");
      $(g).find(".pw-secure-text").text('低').css("color","#dc7018");
      $(pwb).last().css("background-color","#dc7018");
    }else if (w===2){
      $(pwi).removeClass().addClass("pw-secure-med");
      $(g).find(".pw-secure-text").text('中').css("color","#ff9c00");
      $(pwb).parent().find("b:gt(1)").css("background-color","#ff9c00");
    }else if (w===3){
      $(pwi).removeClass().addClass("pw-secure-high");
      $(g).find(".pw-secure-text").text('高').css("color","#009900");
      $(pwb).css("background-color","#009900");
    }
  }
  //如果checkbox是必填
  $(document).on('click','input[type=checkbox]',function(e){
    if ($(this).attr('required')==="required" && $(this).attr('checked')!=="checked"){
      $(this).addClass("form-unit-error");
      $(this).closest('.form-group').find(".required").addClass("active");
    }else{
      $(this).removeClass("form-unit-error");
      $(this).closest('.form-group').find(".active").removeClass("active");
    }
  })

  // 提交手机校验码
  // $(document).on('click','#smsCode-submit-btn',function(e){
  //   var form = $(this).closest("form");          
  //   checkAllInput(form);
  //   var checkNum = $(form).find(".error.active").length;
    
  //   if(checkNum===0){
  //     $.ajax({
  //       url: '/muser/reg/regInfoSubmit/checkSmSCode',
  //       type: 'post',
  //       dataType: 'json',
  //       data: {
  //         usrMp : $("input[name=usrMp]").val(),
  //         smsCode : $("input[name=smsCode]").val()
  //       }
  //     })
  //     .done(function(re) {
  //       console.log(re);
  //       if(re.error===""){
  //         $("input[name=reSmsCode]").val($("input[name=smsCode]").val());
  //         $("#regInfoForm").submit();
  //       }else{
  //         markTip(["errorInfo",re.error],$("input[name=smsCode]"))
  //       }
  //     })
  //   }
  // })
  
  function regSubmit(form){
    //console.log("check="+checkingNum);

    if(checkingNum>0){
      alert("正在校验请稍后重新点击确定按钮");
      return;
    }          

     //最后点提交按钮时, 跳过ajax查询
    checkAllInput(form,undefined,true);
    var checkNum = $(form).find(".error.active").length;

    if(checkNum>0){
      //goToError();
      return;
    }
    if(checkNum===0){
      $(form).find(".btn-primary").removeClass('btn-primary').addClass('btn-disabled');
      var usrId = $("input[name=usrId]");
      if(usrId.length>0){
        $(form).find("input[name=loginId]").val('wwwj_'+$(form).find("input[name=usrId]").val());
      }

      form.submit();

      // var url  = '/muser/reg/regInfoSubmit',
      //     data = $(form).serialize();
      // console.log(data);
      // $.ajax({
      //   url: url,
      //   type: 'post',
      //   dataType: 'json',
      //   data: data
      // })
      // .done(function(resp) {
      //   if (resp.error){
      //     alert(resp.error);
      //   }else{
      //     modalShow('modal-1');
      //     $("#usrMptxt").text($("input[name=usrMp]").val())
      //     $("input[name=smsCode]").focus().val("");
      //     setSMSCodeReset();
      //   }
      // }).always(function(resp){
      //   reging=false;
      //   console.log(resp)
      // })
      
    }else{
      //alert("失败："+checkNum);
    }
  }
  $(document).on('submit','#regInfoForm',function(e){
    e.preventDefault();
    regSubmit(this);
  })
  $(document).on('click','#reg-submit-btn.btn-primary',function(e){
    regSubmit($(this).closest("form"));
  })
  //modalShow('modal-1');
  //setSMSCodeReset();
  var smsInter;
  function setSMSCodeReset(){
    clearInterval(smsInter);
    var SMSCodeRestTime = 60;
    var smsResetBtn = $("#smsResetBtn");
    smsResetBtn.removeClass('btn-secondary').addClass('btn-disabled');

    smsResetBtn.find('span').text(SMSCodeRestTime+"秒后重发");
    smsInter = setInterval(function(){
      
      SMSCodeRestTime--;
      if(SMSCodeRestTime===0){

        smsResetBtn.removeClass('btn-disabled').addClass('btn-secondary').find("span").text("重发校验码");

        clearInterval(smsInter);
        return;
      }
      smsResetBtn.find('span').text(SMSCodeRestTime+"秒后重发")
    },1000)
  }
  // var resetSMSing = false;
  // $(document).on('click','#smsResetBtn',function(e){
  //   console.log(resetSMSing)
  //   if($(this).hasClass('btn-secondary') && !resetSMSing){
  //     resetSMSing = true;
  //     console.log("reset")
  //     $.ajax({
  //       url: '/muser/ajax/reSendSCode',
  //       type: 'post',
  //       dataType: 'json',
  //       data: {
  //         usrMp : $("input[name=usrMp]").val()
  //       }
  //     })
  //     .done(function(resp) {
  //       setSMSCodeReset();
  //     }).always(function(resp){
  //       console.log(resp)
  //       resetSMSing=false;
  //     })
  //   }
  // })
  

  $(document).on('click','.secure-tips h2',function(e){
    var con = $(this).siblings(".content");
    con.toggle();
    $(this).find("i").css('-webkit-transform','scaleY('+(con.is(":visible")?'-1':'1')+')')
    $('html,body').animate({scrollTop: $(this).offset().top+150},'medium', function(){
        //$(focusInput).focus();
    });
  })
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
  function pwdStrength(str){
    var characters = 0;
    var capitalletters = 0;
    var loweletters = 0;
    var number = 0;
    //var special = 0;

    var upperCase= new RegExp('[A-Z]');
    var lowerCase= new RegExp('[a-z]');
    var numbers = new RegExp('[0-9]');    
    //var specialchars = new RegExp('([!,%,&,@,#,$,^,*,?,_,~])');

    if (str.match(upperCase)) { capitalletters = 1} else { capitalletters = 0; };
    if (str.match(lowerCase)) { loweletters = 1}  else { loweletters = 0; };
    if (str.match(numbers)) { number = 1}  else { number = 0; };
    //if (str.match(specialchars)) { special = 1}  else { special = 0; };

    var weight = 0;
    if (str.length>=6){
      weight = capitalletters + loweletters + number;// + special;
    }
    return weight;
  }
  //http://leeyee.github.io/blog/2013/07/31/javascript-idcard-validate/
  function getIdCardInfo(cardNo) {

    var info = {
        isTrue : false,
        year : null,
        month : null,
        day : null,
        isMale : false,
        isFemale : false
    };

    //跳过身份证验证
    //info.isTrue = true;
    //return info;
    //跳过身份证验证

    if (!cardNo && 15 != cardNo.length && 18 != cardNo.length) {
        info.isTrue = false;
        return info;
    }
    if (15 == cardNo.length) {
        var year = cardNo.substring(6, 8);
        var month = cardNo.substring(8, 10);
        var day = cardNo.substring(10, 12);
        var p = cardNo.substring(14, 15); //性别位
        var birthday = new Date(year, parseFloat(month) - 1,
                parseFloat(day));
        // 对于老身份证中的年龄则不需考虑千年虫问题而使用getYear()方法  
        if (birthday.getYear() != parseFloat(year)
                || birthday.getMonth() != parseFloat(month) - 1
                || birthday.getDate() != parseFloat(day)) {
            info.isTrue = false;
        } else {
            info.isTrue = true;
            info.year = birthday.getFullYear();
            info.month = birthday.getMonth() + 1;
            info.day = birthday.getDate();
            if (p % 2 == 0) {
                info.isFemale = true;
                info.isMale = false;
            } else {
                info.isFemale = false;
                info.isMale = true
            }
        }
        return info;
    }
    if (18 == cardNo.length) {
        var year = cardNo.substring(6, 10);
        var month = cardNo.substring(10, 12);
        var day = cardNo.substring(12, 14);
        var p = cardNo.substring(14, 17)
        var birthday = new Date(year, parseFloat(month) - 1,
                parseFloat(day));
        // 这里用getFullYear()获取年份，避免千年虫问题
        if (birthday.getFullYear() != parseFloat(year)
                || birthday.getMonth() != parseFloat(month) - 1
                || birthday.getDate() != parseFloat(day)) {
            info.isTrue = false;
            return info;
        }
        var Wi = [ 7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2, 1 ];// 加权因子  
        var Y = [ 1, 0, 10, 9, 8, 7, 6, 5, 4, 3, 2 ];// 身份证验证位值.10代表X 
        // 验证校验位
        var sum = 0; // 声明加权求和变量
        var _cardNo = cardNo.split("");
        if (_cardNo[17].toLowerCase() == 'x') {
            _cardNo[17] = 10;// 将最后位为x的验证码替换为10方便后续操作  
        }
        for ( var i = 0; i < 17; i++) {
            sum += Wi[i] * _cardNo[i];// 加权求和  
        }
        var i = sum % 11;// 得到验证码所位置
        if (_cardNo[17] != Y[i]) {
            return info.isTrue = false;
        }
        info.isTrue = true;
        info.year = birthday.getFullYear();
        info.month = birthday.getMonth() + 1;
        info.day = birthday.getDate();
        if (p % 2 == 0) {
            info.isFemale = true;
            info.isMale = false;
        } else {
            info.isFemale = false;
            info.isMale = true
        }
        return info;
    }
    return info;
  }

})