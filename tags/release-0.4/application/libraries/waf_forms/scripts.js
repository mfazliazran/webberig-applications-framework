//Javascript required validation
///////////////////////////////////////////////////////////////
function CheckRequired()
{
    if (this.value.length==0)
    {
        $(this).removeClass('invalid');
        $(this).addClass('invalid');
        isValid = false;
    } else {
        isValid = true;
        $(this).removeClass('invalid');
    }
}
$(function(){
    $('div.required input').blur(CheckRequired);
    $('div.required input').keyup(CheckRequired);
    $('div.required input').each(CheckRequired);

// Datepicker
///////////////////////////////////////////////////////////////
    $( "input.date" ).datepicker({
        firstDay: 1,
        dateFormat: 'dd-mm-yy'
    });

// QTIP Tooltips
///////////////////////////////////////////////////////////////
    $('form .inputTooltip').qtip({
        // Simply use an HTML img tag within the HTML string
        content: {
                text: function(api)
                {
                    return tooltip = $(this).parent().find('div.tooltip').html();
                }
            },
        show: 'focus',
        hide: 'blur',
        style: {
            tip: {
            corner: 'left top'
            }
        },
        position: {
            my: 'top left',  // Position my top left...
            at: 'center right',
            adjust: {
                x: 5
            }
        }
    });
    $('div.tooltip').hide();
});
