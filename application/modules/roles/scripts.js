$(document).ready(function(){

//EDIT PAGE - Confirmation dialog
///////////////////////////////////////////////////////////////    
    $( "#dialog-confirm" ).dialog({
        autoOpen: false,
        resizable: false,
        draggable: false,
        height: 180,
        width: 350,
        modal: true,
        buttons: {
            "Verwijderen": function() {
                Redirect("roles/delete/" + $( "#dialog-confirm" ).data("id"));
            },
            "Annuleren": function() {
                $( this ).dialog( "close" );
            }
        }
    });

// Roles table click action
//////////////////////////////////////////////////////////////////////
    $("#roles tbody tr").click(function()
    {
        Redirect("roles/edit/" + this.id);
    }).addClass("selectable");
});

