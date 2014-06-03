function show_upload_button(unique_id, uploader_element)
{
	$('#upload-state-message-'+unique_id).html('');
	$("#loading-"+unique_id).hide();

	$('#upload-button-'+unique_id).slideDown('fast');
	$("input[rel="+uploader_element.attr('name')+"]").val('');
	$('#success_'+unique_id).slideUp('fast');	
}

function clearInput()
{
	$("input:text").val("");
        $("#field-media_type").val('').change().prop('disabled', false).trigger('liszt:updated');
        $('#field-media_size').val("").prop("readonly", true).css("background-color", "silver").change();
}

function load_fancybox(elem)
{
	elem.fancybox({
		'transitionIn'	:	'elastic',
		'transitionOut'	:	'elastic',
		'speedIn'		:	600, 
		'speedOut'		:	200, 
		'overlayShow'	:	false
	});		
}

$(function(){
    
	$('.gc-file-upload').each(function(){
		var unique_id 	= $(this).attr('id');
		var uploader_url = $(this).attr('rel');
		var uploader_element = $(this);
		var delete_url 	= $('#delete_url_'+unique_id).attr('href');
		eval("var file_upload_info = upload_info_"+unique_id+"");
		
	    $(this).fileupload({
	        dataType: 'json',
	        url: uploader_url,
	        cache: false,
	        acceptFileTypes:  file_upload_info.accepted_file_types,
			beforeSend: function(){
	    		$('#upload-state-message-'+unique_id).html(string_upload_file);
				$("#loading-"+unique_id).show();
				$("#upload-button-"+unique_id).slideUp("fast");
			},
	        limitMultiFileUploads: 1,
	        maxFileSize: file_upload_info.max_file_size,			
			send: function (e, data) {						
				
				var errors = '';
				
			    if (data.files.length > 1) {
			    	errors += error_max_number_of_files + "\n" ;
			    }
				
	            $.each(data.files,function(index, file){
		            if (!(data.acceptFileTypes.test(file.type) || data.acceptFileTypes.test(file.name))) {
		            	errors += error_accept_file_types + "\n";
		            }
		            if (data.maxFileSize && file.size > data.maxFileSize) {
		            	errors +=  error_max_file_size + "\n";
		            }
		            if (typeof file.size === 'number' && file.size < data.minFileSize) {
		            	errors += error_min_file_size + "\n";
		            }			            	
	            });	
	            
	            if(errors != '')
	            {
	            	alert(errors);
	            	return false;
	            }
				
			    return true;
			},
	        done: function (e, data) {
                    if(typeof data.result.success != 'undefined' && data.result.success)
                    {
                        $("#loading-"+unique_id).hide();
                        $("#progress-"+unique_id).html('');
                        $.each(data.result.files, function (index, file) {
                            $('#upload-state-message-'+unique_id).html('');
                            $("input[rel="+uploader_element.attr('name')+"]").val(file.name);
                            
                            var mediaSizeId = '#field-media_size';
                            var mediaNameId = '#field-media_name';
                            
                            var mediaTypeId = '#field-media_type';
                            var mediaTypeLabel = '#field_media_type_chzn span';
                            var mediaLenghtId = '#field-media_lenght';
                            var mediaPathId = '#field-media_path';
                            var mediaCheckSumId = '#field-media_checksum';
                            var file_name = file.name;

//                            var is_image = (file_name.substr(-4) == '.jpg'  
//                                                            || file_name.substr(-4) == '.png' 
//                                                            || file_name.substr(-5) == '.jpeg' 
//                                                            || file_name.substr(-4) == '.gif' 
//                                                            || file_name.substr(-5) == '.tiff')
//                                            ? true : false;
//                                            
//                            var is_video = (file_name.substr(-4) == '.mp4') ? true : false;
                            if(file.type === "image")
                            {
                                    $('#file_'+unique_id).addClass('image-thumbnail');
                                    load_fancybox($('#file_'+unique_id));
                                    $('#file_'+unique_id).html('<img src="'+file.url+'" height="240" />');
                                    $(mediaTypeId).val('image');
//                                    $(mediaSizeId).val(file.size).prop("readonly", true);
                            }
                            else if(file.type === "video")
                            {
                                    $('#file_'+unique_id).removeClass('image-thumbnail'); 
                                    
                                   
                                    $('#file_'+unique_id).unbind("click");
//                                    $('#file_'+unique_id).html(file_name);

                                    var $input = '<object id="MediaPlayer1" CLASSID="CLSID:22d6f312-b0f6-11d0-94ab-0080c74c7e95" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=5,1,52,701"';
                                    $input += 'standby="Loading Microsoft Windows® Media Player components..." type="application/x-oleobject" width="300" height="256">';
                                    $input += '<param name="fileName" value="' + file.path + '">';
                                    $input += '<param name="animationatStart" value="true">';
                                    $input += '<param name="transparentatStart" value="true">';
                                    $input += '<param name="autoStart" value="true">';
                                    $input += '<param name="showControls" value="true">';
                                    $input += '<param name="Volume" value="-450">';
                                    $input += '<embed type="application/x-mplayer2" pluginspage="http://www.microsoft.com/Windows/MediaPlayer/" src="' + file.path + '" name="MediaPlayer1" width=280 height=256 autostart=1 showcontrols=1 volume=-450>';
                                    $input += '</object>';
                    
                                    $('#file_'+unique_id).html($input);
//                                    $('#file_'+unique_id).html("<video height='240' controls id='video' style='z-index: 1;' name='asdf'>"
//                                            +"<source src='"+file.path+"' type='video/"+file.ext+"'>"
//                                        +"</video>");
                                    
                                    
                                    // custom
                                    $(mediaLenghtId).val(file.lenght).prop("readonly", true);
//                                    $(mediaSizeId).val(file.size).prop("readonly", true);
                                    $(mediaTypeId).val('video');
//                                    $(mediaTypeLabel).html('video');
//                                    $(mediaPathId).val(file.path);
                                    
                                    
                                    
                            }else if(file.type === "scrolling text"){
                                    $('#file_'+unique_id).removeClass('image-thumbnail');
                                    $('#file_'+unique_id).unbind("click");
                                    $('#file_'+unique_id).html(file_name);
                                    $(mediaTypeId).val('scrolling text');
//                                    $(mediaTypeId).val('text').prop("disabled", true);
//                                    $(mediaSizeId).val(file.size).prop("readonly", true);
                            }
                            $(mediaPathId).val(file.path);
                            $(mediaSizeId).val(file.size).prop("readonly", true).css("background-color", "silver").change();
                            $(mediaTypeId).change().prop('disabled', true).trigger('liszt:updated');
                            $("#field-media_filename_temp").val(file.name);
                            $(mediaCheckSumId).val(file.checkSum);
                            
                            var name = file.name.split(".");
                            if( name.length === 1 || ( name[0] === "" && name.length === 2 ) ) {
                                name = "";
                            }
                            
                            name = name.pop(); 
                            var nameReplaceEx = file.name.replace("."+name, "")
                            $(mediaNameId).val(nameReplaceEx.replace(nameReplaceEx.substring(0, nameReplaceEx.indexOf("-") + 1 ), "" ));

                            $('#file_'+unique_id).attr('href',file.url);
                            $('#hidden_'+unique_id).val(file_name);

                            $('#success_'+unique_id).fadeIn('slow');
                            $('#delete_url_'+unique_id).attr('rel',file_name);
                            $('#upload-button-'+unique_id).slideUp('fast');
                        });
                    }
                    else if(typeof data.result.message != 'undefined')
                    {
                            alert(data.result.message);
                            show_upload_button(unique_id, uploader_element);
                    }
                    else
                    {
                            alert(error_on_uploading);
                            show_upload_button(unique_id, uploader_element);
                    }
	        },
	        autoUpload: true,
	        error: function()
	        {
	        	alert(error_on_uploading);
	        	show_upload_button(unique_id, uploader_element);
	        },
	        fail: function(e, data)
	        {
	            // data.errorThrown
	            // data.textStatus;
	            // data.jqXHR;	        	
	        	alert(error_on_uploading);
	        	show_upload_button(unique_id, uploader_element);
	        },	        
	        progress: function (e, data) {
                $("#progress-"+unique_id).html(string_progress + parseInt(data.loaded / data.total * 100, 10) + '%');
            }	        
	    });
		$('#delete_'+unique_id).click(function(){
			if( confirm(message_prompt_delete_file) )
			{
				var file_name = $('#delete_url_'+unique_id).attr('rel');
				$.ajax({
					url: delete_url+"/"+file_name,
					cache: false,
					success:function(){
						show_upload_button(unique_id, uploader_element);
                                                clearInput();
                                                
					},
					beforeSend: function(){
						$('#upload-state-message-'+unique_id).html(string_delete_file);
						$('#success_'+unique_id).hide();
						$("#loading-"+unique_id).show();
						$("#upload-button-"+unique_id).slideUp("fast");
					}
				});
			}
			
			return false;
		});		    
	    
	});
});