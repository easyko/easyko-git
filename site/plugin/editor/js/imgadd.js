var doc = document.getElementById("addimg");
var nu = 0;
function add(){
	nu++;
	var newNode = document.createElement("div");
	newNode.setAttribute("id","imgdiv"+nu);
	var input = document.createElement("input");
	input.setAttribute("name","pictures[]");
	input.setAttribute("type","file");
	var imgtitle_txt = document.createTextNode(' ±êÌâ£º');
	var imgtitle = document.createElement("input");
	imgtitle.setAttribute("name","title[]");
	imgtitle.setAttribute("type","text");
	var img_small_txt = document.createTextNode(' Ð¡Í¼£º');
	var img_small = document.createElement("input");
	img_small.setAttribute("type","checkbox");
	img_small.setAttribute("name","isthumb[]");
	//alert(nu-1);
	img_small.setAttribute("value",(nu-1));
	newNode.appendChild(input);
	newNode.appendChild(imgtitle_txt);
	newNode.appendChild(imgtitle);
	newNode.appendChild(img_small_txt);
	newNode.appendChild(img_small);
	doc.appendChild(newNode);
}

function del(){
	//alert(nu);
	if(nu>1){
		doc.removeChild(document.getElementById("imgdiv"+nu));
	}
	nu--;
	if(nu<1){nu=1;}
}
add();