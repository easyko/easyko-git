/*
$(document).ready(function(){
	$("head").append('<script src="/site/plugin/editor/ckeditor/ckeditor.js" type="text/javascript"></script>');
});
*/
(function($){

	$.extend($.fn,{
		ckeditor: function() {
		  var dom = this[0];
		  var options = arguments[0];
		  
		  var setting = {
				enterMode: CKEDITOR.ENTER_P,
				shiftEnterMode: CKEDITOR.ENTER_BR
		  };

		  if(options!=undefined){
			$.extend(setting, options);
		  }
		  
		  CKEDITOR.replace( dom, setting);
		  return 0;
		}
	});

})(jQuery);

