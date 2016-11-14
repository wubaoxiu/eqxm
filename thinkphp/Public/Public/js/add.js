//可以通过这个对象给状态
function gspan(cobj){
    while(true){
        //如果不为span标签 则返回下一个标签
        if(cobj.nextSibling.nodeName!="SPAN"){
            cobj=cobj.nextSibling;
        }else{
            return cobj.nextSibling;
        }
    }
}

// 通用的检查方法
// 第一个参数 obj 用来检查的对象
// 第二个参数，info 用来检查提示信息
// 第三个参数 fun 是一个回调函数 用来检查值是否按条件输入
// 第四个参数 click 只是一个状态 分清楚是单击提交还是失去焦点
function check(obj,info,fun,click){
    var sp = gspan(obj);
    //焦点
    obj.onfocus = function(){
    
      sp.innerHTML=info;
      sp.className = "stats2";
      }

// 离开焦点
   obj.onblur = function (){
     if (fun(this.value)) {
            sp.innerHTML = "输入正确";
            sp.className = "stats4";
         }else{
            sp.innerHTML = info;
            sp.className = "stats3";
         }
   }
  
  //判断form传过来的click 是否等于click
   if (click == "click") {
    obj.onblur();
   };

}

// 页面加载完自动调用
onload =regs; 

// 一个函数可以使用onsubmit 调用 也可以使用onload调用
function regs(click){
    var stat = true;
    var username = document.getElementsByName('username')[0];
    var password = document.getElementsByName('password')[0];
    var repass = document.getElementsByName('repass')[0];
    var email = document.getElementsByName('email')[0];
    var other = document.getElementsByName('other')[0];


check(username,"用户名的长度要在3-20 之间",function(val){
    if (val.match(/^\S+$/) && val.length>=3 && val.length<=20) {
        return true;
    }else{
        stat = false;
        return false;
    }
},click);

check(password,'密码必须在6-20位之间',function(val){
    if (val.match(/^\S+$/) && val.length>=6 && val.length<=20) {
        return true;
    }else{
        stat = false;
        return false;
    }
},click);
check(repass,'确认密码必须与密码一致',function(val){
    if (val.match(/^\S+$/) && val.length>=6 && val.length<=20 && val ==password.value) { 
        return true;
    }else{
        stat = false;
        return false;
    }
},click);
check(email,'请输入正确的Email邮箱格式',function(val){
    if (val.match(/^\w+@\w+\.\w/)) {
        return true;
    }else{
        stat = false;
        return false;
    }
},click);
  return stat;
}

