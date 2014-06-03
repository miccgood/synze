$(function(){
    
    var option = {};
    if($("#field-page").val() === "playlist"){
        option = {
            unique: false ,
            removeAll: false,
            addAll: false,
            droppable: false,
            groupList: groupList,
            mediaList: mediaList,
            refreshAvailableList: false,
            nodeInserted: function(item) {
                item.attr('title', item.text()); // set a "tooltip" to newly added items
            },
            isUpdateProcessBar: true
        };
    }else{
        option = {
            unique: true 
        };
    }
    
    $(".multiselect").multiselect(option);	
    
});