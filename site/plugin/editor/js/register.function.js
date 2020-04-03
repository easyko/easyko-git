var emailreg = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/;
var usernamereg = /^([a-zA-Z0-9_]{3,16})+$/;
var passwdreg = /^([a-zA-Z0-9]{6,16})+$/;
var realnamereg = /^[\u0391-\uFFE5]+$/;
var mobilereg = /^(((13[0-9]{1})|(18[0-9]{1})|(15[0-9]{1}))+\d{8})$/;
var telreg = /^(([0-9]{3,4})+\-+\d{7,8})$/;

function checkIdcard(num){ 
	var len = num.length;
	var re;
	if(len == 15){
		re = new RegExp(/^(\d{6})()?(\d{2})(\d{2})(\d{2})(\d{3})$/);
	}else if (len == 18){
		re = new RegExp(/^(\d{6})()?(\d{4})(\d{2})(\d{2})(\d{3})(\w)$/);
	}else {
		return false;
	}
	var a = num.match(re);
	if (a != null){
		if (len==15){
			var D = new Date("19"+a[3]+"/"+a[4]+"/"+a[5]);
			var B = D.getYear()==a[3]&&(D.getMonth()+1)==a[4]&&D.getDate()==a[5];
		}else{
			var D = new Date(a[3]+"/"+a[4]+"/"+a[5]);
			var B = D.getFullYear()==a[3]&&(D.getMonth()+1)==a[4]&&D.getDate()==a[5];
		}
		if(!B){
			return false;
		}
	}
	return true; 
} 