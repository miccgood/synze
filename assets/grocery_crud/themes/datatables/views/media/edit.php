<?php
$this->set_css($this->default_theme_path . '/datatables/css/datatables.css');
$this->set_js_lib($this->default_theme_path . '/flexigrid/js/jquery.form.js');
$this->set_js_config($this->default_theme_path . '/datatables/js/datatables-edit.js');
$this->set_css($this->default_css_path . '/ui/simple/' . grocery_CRUD::JQUERY_UI_CSS);
        $this->set_css($this->default_css_path . '/ui/simple/colpick.css');

$this->set_js_lib($this->default_javascript_path . '/jquery_plugins/ui/' . grocery_CRUD::JQUERY_UI_JS);

$this->set_js_lib($this->default_javascript_path . '/jquery_plugins/jquery.noty.js');
$this->set_js_lib($this->default_javascript_path . '/jquery_plugins/config/jquery.noty.config.js');
$this->set_js_lib($this->default_javascript_path.'/jquery_plugins/colpick.js');
?>
<script type="text/javascript">
<?php echo $this->customScript; ?>
</script>
<div class='ui-widget-content ui-corner-all datatables'>
    <h3 class="ui-accordion-header ui-helper-reset ui-state-default form-title">
        <div class='floatL form-title-left'>
            <a href="#"><?php echo $this->l('form_edit'); ?> <?php echo $subject ?></a>
        </div>
        <div class='clear'></div>
    </h3>
    <div class='form-content form-div' id="form_input">
        <?php echo form_open($update_url, 'method="post" id="crudForm" autocomplete="off" enctype="multipart/form-data"'); ?>
        <div>
           <?php include 'detail.php';?>
            <!-- Start of hidden inputs -->
            <?php
            foreach ($hidden_fields as $hidden_field) {
                echo $hidden_field->input;
            }
            ?>
            <!-- End of hidden inputs -->
            <?php if ($is_ajax) { ?><input type="hidden" name="is_ajax" value="true" /><?php } ?>
            <div class='line-1px'></div>
            <div id='report-error' class='report-div error'></div>
            <div id='report-success' class='report-div success'></div>
        </div>
        <?php if($this->default_value["permissionEdit"]){ ?>
        
        <div class='buttons-box'>
            <div class='form-button-box'>
                <input  id="form-button-save" type='submit' value='<?php echo $this->l('form_update_changes'); ?>' class='ui-input-button' />
            </div>
<?php if (!$this->unset_back_to_list) { ?>
                <div class='form-button-box'>
                    <input type='button' value='<?php echo $this->l('form_update_and_go_back'); ?>' class='ui-input-button' id="save-and-go-back-button"/>
                </div>
                <div class='form-button-box'>
                    <input type='button' value='<?php echo $this->l('form_cancel'); ?>' class='ui-input-button' id="cancel-button" />
                </div>
<?php } ?>
            <div class='form-button-box loading-box'>
                <div class='small-loading' id='FormLoading'><?php echo $this->l('form_update_loading'); ?></div>
            </div>
            <div class='clear'></div>
        </div>
        
         <?php 
                        } else {  ?>
        
        <div class='buttons-box'>
<?php if (!$this->unset_back_to_list) { ?>
                <div class='form-button-box'>
                    <input type='button' value='<?php echo $this->l('form_back'); ?>' class='ui-input-button' id="cancel-button" />
                </div>
<?php } ?>
            <div class='form-button-box loading-box'>
                <div class='small-loading' id='FormLoading'><?php echo $this->l('form_update_loading'); ?></div>
            </div>
            
            <div class='clear'></div>
        </div>
        
        <script type="text/javascript" >
            $(function(){
                $("#cat_id_field_box .datatables-add-button, .datepicker-input-clear").hide();
                $(":text").prop({disabled:true}).css({"background-color": "#dedede"});
                $('.chosen-select').prop('disabled', true).trigger('liszt:updated');
                
            });
        </script>
        <?php }?>
        </form>
    </div>
</div>
<script>
    var validation_url = '<?php echo $validation_url ?>';
    var list_url = '<?php echo $list_url ?>';

    var message_alert_edit_form = "<?php echo $this->l('alert_edit_form') ?>";
    var message_update_error = "<?php echo $this->l('update_error') ?>";
    
    $(function(){
        var $mediaLenght = parseInt($("#field-media_lenght").val());
        $("#field-media_lenght").val($mediaLenght / 1000);
    });
</script>