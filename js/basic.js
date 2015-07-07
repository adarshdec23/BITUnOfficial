var ar=[];
var imgTitles=[];
var imgAlt=[];
function lclick(adr,id,titles, alt){		
    var src=$("#link"+id+" img").attr("src");//Obtain the current source
    ar=adr.split(",");//Split the string containing all address
    imgTitles = titles.split(",");
    imgAlt = alt.split(",");
    var ind=ar.indexOf(src);//Find the position of current source in the split array
    if(ind===0)
        setImgAddr(ar.length-1,id);
    else
        setImgAddr(ind-1,id);		
}
function rclick(adr,id, titles, alt){
    var src=$("#link"+id+" img").attr("src");
    ar=adr.split(",");
    imgTitles = titles.split(",");
    imgAlt = alt.split(",");
    var ind=ar.indexOf(src);
    if(ind===ar.length-1)
        setImgAddr(0,id);
    else
        setImgAddr(ind+1,id);
}	
/*Function to generate the address in the slideshow */
function setImgAddr(imgInd,id){  
    $("#link"+id+" img").attr("src",ar[imgInd]);
    $("#link"+id+" img").attr("title",imgTitles[imgInd]);
    $("#link"+id+" img").attr("alt",imgAlt[imgInd]);
    //$("#link"+id).attr("href","img.php?iid="+id+"&offset="+imgInd);
}
$(document).ready(function(){
    var totWidth=0,count=0;
    $.each($('.rEmptyWrap'),function(){
        totWidth=totWidth+$('#robj').width();
        count++;
    });
    $('.bullet:nth-last-child('+count+')').css("background","#49E0D1");
    var initWidth=$('.rEmptyWrap:first').width();
    $('#slideShow').width(totWidth+20);
    $('.rEmptyWrap').width(initWidth);
    var bTracker=count;
    var tick=function(){
        anim=window.setTimeout(function(){
            $('.bullet:nth-last-child('+(bTracker)+')').css("background","#303030");
            if(--bTracker===0){
                bTracker=count;
                $('.rEmptyWrap:first').animate({marginLeft:0},300);
            }
            else
                $('.rEmptyWrap:first').animate({marginLeft:"-="+(initWidth)},700);
            $('.bullet:nth-last-child('+bTracker+')').css("background","#49E0D1");
            tick();
        },5000);
   };
   tick();
   $('.bullet').click(function(){
        clearTimeout(anim);
        var clickedBullet=$(this).index();
        $('.bullet').css("background","#303030");
        $(this).css("background","#49E0D1");
		bTracker = count - clickedBullet;
        $('.rEmptyWrap:first').animate({marginLeft:(-1*clickedBullet*initWidth)},400);
        tick();
   });
});
