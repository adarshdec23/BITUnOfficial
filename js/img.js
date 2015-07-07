$(document).ready(function(){
    $("#videosTab").click(function(){
        $(this).addClass('greyBackground');
        $('#imagesTab').removeClass('greyBackground');
    });
    $("#imagesTab").click(function(){
        $(this).addClass('greyBackground');
        $('#videosTab').removeClass('greyBackground');
    });
});

function lclick(adr,tit,alt){	
    var src=$("#iss img").attr("src");//Obtain the current source
    var Aar=adr.split(",");//Split the string containing all address
    var ind=Aar.indexOf(src);//Find the position of current source in the split array
    var stit=tit.split(",");
    var salt=alt.split(",");
    if(ind===0){
        setImgAddr(Aar[Aar.length-1],stit[Aar.length-1],salt[Aar.length-1]);
        setText(stit[Aar.length-1]);
    }
    else{
        setImgAddr(Aar[ind-1],stit[ind-1],salt[ind-1]);
        setText(stit[ind-1]);
    }
}
function rclick(adr,tit,alt){
    var src=$("#iss img").attr("src");//Obtain the current source
    var Aar=adr.split(",");//Split the string containing all address
    var ind=Aar.indexOf(src);//Find the position of current source in the split array
    var stit=tit.split(",");
    var salt=alt.split(",");
    if(ind===Aar.length-1){
        setImgAddr(Aar[0],stit[0],salt[0]);
        setText(stit[0]);
    }
    else{
        setImgAddr(Aar[ind+1],stit[ind+1],salt[ind+1]);
        setText(stit[ind+1]);
    }
}	
/*Function to generate the address in the slideshow */
function setImgAddr(adr,tit,alt){  
    $("#iss img").attr({"src":adr,"title":tit,"alt":alt});
}
function setText(tit){
    if(tit==0)
        tit="";
    document.getElementById('textBelow').innerHTML=tit;
}