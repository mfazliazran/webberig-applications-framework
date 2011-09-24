$(document).ready(function(){
    
// Create buttons and button sets
///////////////////////////////////////////////////////////////
    $("a.button, input:submit, input:button").button();
    $("#submenu ul.options").buttonset();

// Alternating table rows
///////////////////////////////////////////////////////////////
    $("table tbody tr:even").addClass("alt");

// Menu animation
///////////////////////////////////////////////////////////////
    $.extend($.fx.step,{
        backgroundPosition: function(fx) {
            if (fx.state === 0 && typeof fx.end == 'string') {
                var start = $.curCSS(fx.elem,'backgroundPosition');
                start = toArray(start);
                fx.start = [start[0],start[2]];
                var end = toArray(fx.end);
                fx.end = [end[0],end[2]];
                fx.unit = [end[1],end[3]];
            }
            var nowPosX = [];
            nowPosX[0] = ((fx.end[0] - fx.start[0]) * fx.pos) + fx.start[0] + fx.unit[0];
            nowPosX[1] = ((fx.end[1] - fx.start[1]) * fx.pos) + fx.start[1] + fx.unit[1];
            fx.elem.style.backgroundPosition = nowPosX[0]+' '+nowPosX[1];
    
           function toArray(strg){
               strg = strg.replace(/left|top/g,'0px');
               strg = strg.replace(/right|bottom/g,'100%');
               strg = strg.replace(/([0-9\.]+)(\s|\)|$)/g,"$1px$2");
               var res = strg.match(/(-?[0-9\.]+)(px|\%|em|pt)\s(-?[0-9\.]+)(px|\%|em|pt)/);
               return [parseFloat(res[1],10),res[2],parseFloat(res[3],10),res[4]];
           }
        }
    });
    $('nav#top li a')
        .css( {backgroundPosition: "0 -30px"} )
        .mouseover(function(){
            $(this).stop().animate({backgroundPosition:"(0 0px)"}, {duration:600})
        })
        .mouseout(function(){
            $(this).stop().animate({backgroundPosition:"(0 -30px)"}, {duration:500, complete:function(){
                $(this).css({backgroundPosition: "0 -30px"})
            }})
    })

// Site selector
///////////////////////////////////////////////////////////////    
    
    $('#site-selector a:first').button(
    {
        icons: {
            secondary: 'ui-icon-triangle-1-s'
        }
    });

    $('#site-selector a').click(function () {
    	$('#site-selector-menu').slideToggle('fast');
    });
    
    $('#site-selector-menu a').click(function(e){
        e.preventDefault();
        $.post('shared/setActiveLanguage', { site: $(this).attr('rel')}).success(function(data) {
            Refresh();            
        });        
    });

// Language selector
///////////////////////////////////////////////////////////////    
    
    $('#lang-selector a:first').button(
    {
        icons: {
            secondary: 'ui-icon-triangle-1-s'
        }
    });

    $('#lang-selector a').click(function () {
    	$('#lang-selector-menu').slideToggle('fast');
    });
    
    $('#lang-selector-menu a').click(function(e){
        e.preventDefault();
        $.post('shared/setActiveLanguage', { language: $(this).attr('rel')}).success(function(data) {
            Refresh();
        });        
    });

// "New" button style
///////////////////////////////////////////////////////////////    
    
    $('#btnnieuw').button({
        icons: {
            primary: "ui-icon-plus"
        }
    });
// "Delete" button style
///////////////////////////////////////////////////////////////    
    
    $('.button.delete').button({
        icons: {
            primary: "ui-icon-trash"
        }
    });
});
