$('.close').click(function(){
    var deleteButton = $(this);
    console.log('clicked');
    $.ajax({
        url: "deletetexture.php",
        type: "POST",
        data: {id: deleteButton.next().attr('id')},
        success: function(data){
            console.log(data);
            deleteButton.parent().remove();
        }
    });
});