var App = {};

App.getState = function() {
    var dataObject             = new Object();
    dataObject.id              = $('#save-item').attr('data-id');
    dataObject.questid         = $('#save-item').attr('data-parent-id');
    dataObject.title           = $('#title').val();
    dataObject.description     = $('#description').val();
    dataObject.crypticdescription     = $('#cryptic-description').val();
    dataObject.hints           = App.getHints();
    dataObject.media           = App.getMedia('#media .media-file.active');


    return dataObject;
}

App.loadItem = function(item_id) {
    $.ajax({
        url : Routing.generate( 'item_ajax_load' ),
        data : {id : item_id},
        type: "POST",
        success: function(data){
            App.doMapping(data);
        },
        error: function(){
            alert("Something went wrong...")
        }
    })

}

App.switchTabs = function(tab_id){

    // reset
    $('.nav-tabs li').each(function(){
        $(this).removeClass('active');
    });
    $('.tab-content .tab-pane').each(function(){
        $(this).removeClass('active');
    });

    // set tab

    $('a[href="#'+ tab_id + '"]').parent().addClass('active');
    $('#' + tab_id).addClass('active');

};


App.deleteItem = function() {
    var confirmDelete = window.confirm("Are you sure?");
    if(confirmDelete){
        $.ajax({
            type: 'post',
            url: Routing.generate( 'item_delete', { id: $('#save-item').attr('data-id') }),
            success: function(data){
                if(data=="ok"){
                    window.location = Routing.generate('item');
                }
            }

        });
    }
}

App.saveItem = function(){
    $.notify("Saving ... ", { className: "info", globalPosition: 'top center' } );
    $.ajax({
        url : Routing.generate('item_ajax_save'),
        data : App.getState(),
        type: 'POST',
        success: function(data){
            App.doMapping(data);
            $.notify("Item saved", { className: "success", globalPosition: 'top center' } );
            App.reloadItems($('#save-item').attr('data-parent-id'));
        },
        error: function(){
            $.notify("Something went wrong", { className: "error", globalPosition: 'top center' } );
        }
    })
}

App.reloadItems = function(quest_id){
    $.ajax({
        url: Routing.generate('quest_load_items'),
        data: {id : quest_id},
        type: 'POST',
        success: function(data){
            $('#items-quest').html(data);
        },
        error: function(){
            $.notify("Something went wrong", { className: "error", globalPosition: 'top center' } );
        }
    })
}

App.setValue = function(dom_id, object_property, entity_object, input_type ){

    //error handling
    if($(dom_id).length==0){
        console.log("Element "+ dom_id + " does not exist...");
        return false;
    }
    if(!entity_object.hasOwnProperty(object_property)){
        console.log("Entity does not have property " + object_property);
        return false;
    }
    // set values
    if(input_type=="text"){
        $(dom_id).val(entity_object[object_property]);
    }
    if(input_type=="textarea"){
        $(dom_id).val(entity_object[object_property]);
    }
    if(input_type=="hint-block"){
        $(dom_id).val(entity_object[object_property]);
    }

}

App.getHints = function() {
    var hints;
    $('.hint-row').each(function(){
        var hint_id = App.getHintId($(this).attr('id'));
        var value = $('#val_'+ hint_id).val();
        var image = App.getMedia("#med_" + hint_id + " img");
        var hint = new Object();
        hint.value = value;
        hint.image = image;
        if(hints instanceof Array){

            hints.push(hint);
        }
        else{
            hints = new Array();
            hints.push(hint);
        }



    });
    return hints;
}

App.setHints = function(hints){
    $('#hints-body').html("");
    for (hint in hints){
        App.addHintRow(hints[hint].value,hints[hint].image);
    }
}

App.setMedia = function(media){
    $('.media-file.active').each(function(){
        $(this).removeClass('active');
    })
    for(file in media){
        $('[data-id="'+ media[file].id +'"]').addClass('active');
    }

}

App.setId = function(id){
    $('#save-item').attr('data-id', id);
}


App.getMedia = function(selector){
    var media = new Array();
    $(selector).each(function(){
        var media_object = new Object();
        media_object.id = $(this).attr('data-id');
        media_object.src = $(this).attr('src');
        media.push(media_object);
    })
    return media;
}


App.doMapping = function(entity_object){
    App.setValue('#title', 'title', entity_object, 'text');
    App.setValue('#description', 'description', entity_object, 'textarea');
    App.setValue('#cryptic-description', 'cryptic_description', entity_object, 'textarea');
    App.setHints(entity_object.hints);
    App.setMedia(entity_object.media);
    App.setId(entity_object.id);
}

App.removeHintRow = function(hint_id)
{
    $('#hint_' + hint_id).remove();
}

App.getHintId = function(id_string)
{
    return  id_string.split("_").pop();
}

App.addHintRow = function(hint_value, hint_image)
{
    if (hint_value == undefined) { hint_value = ""};
    if (hint_image == undefined) { hint_image = new Object();};

    var random_number = Math.floor(Math.random()*10001)
    var hint_id = $.now() + random_number;
    var newRow = "";

    newRow += "            <tr class=\"hint-row\" id=\"hint_"+ hint_id +"\">";
    newRow += "                <td>";
    newRow += "                    <input type=\"text\" id=\"val_"+ hint_id +"\" class=\"form-control hint-key\"  value=\""+ hint_value +"\">";
    newRow += "                <\/td>";
    newRow += "                <td>";
    newRow += "                    <div id=\"med_"+ hint_id +"\">";
    newRow += "                    <div class=\"selected-media\"><\/div>";
    newRow += "                    <a id=\"image_"+ hint_id +"\" title=\""+ hint_id +"\"  class=\"hint-image btn btn-theme btn-sm \"  data-image=\""+ hint_image +"\">Select media</a>";
    newRow += "                    <\/div>";
    newRow += "                <\/td>";
    newRow += "                <td>";
    newRow += "                    <button class=\"btn btn-primary btn-embossed btn-danger btn-sm btn-delete-hint\" id=\"delete_hint_"+ hint_id +"\">";
    newRow += "                        X";
    newRow += "                    <\/button>";
    newRow += "                <\/td>";
    newRow += "            <\/tr>";

    $('#hints-body').append(newRow);


    App.createSelectedMediaBlock(hint_id, hint_image);
}

App.createSelectedMediaBlock = function(parameter, files) {
    $('#med_'+ parameter +' .selected-media').html("");
    for (file in files) {
        $('#med_'+ parameter +' .selected-media').append('<img data-id="'+ files[file].id +'" class="img-thumbnail" id="'+ files[file].id +'" src="'+ files[file].src +'"/>');
    }
}



$(document).ready(function(){
    // events
    $('#new-row').on('click', function(){
        App.addHintRow();
    });

    $('#edit-item').on('click', '.btn-delete-hint', function(){
        App.removeHintRow(App.getHintId($(this).attr('id')));
    });

    $('#save-item').on('click', function(){
        App.saveItem();
    });

    $('.btn-delete-item').on('click', function(){
        App.deleteItem();
    });

    $('.media-file').on('click', function(){
        if($(this).hasClass('active')){
            $(this).removeClass('active');
        }
        else {
            $(this).addClass('active');
        }
    });

    $('.btn-edit-item').on('click', function(){
        App.loadItem($(this).attr('title'));
        $('#edit-item').modal();
        App.switchTabs('edit_item');
    });

    $('.btn-add-item').on('click', function(){
        App.setId(0);
    });

    $('#hints-body').on('click', '.hint-image', function(){
        $(this).attr('title');
        App.switchTabs('media-hint-picker');
        App.current_hint_media = $(this).attr('title');
    })

    $('.btn-add-return').on('click', function(){
        App.switchTabs('edit_item');
        App.createSelectedMediaBlock(App.current_hint_media, App.getMedia('#media-hint-picker .media-file.active'));

    });

});



