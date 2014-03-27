<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Group extends SpotOn {

    function __construct() {
        parent::__construct();
    }
    
    function countMedia($value, $row) {
        $E = @$this->db->query('select count(media_id) as result from mst_media where cat_id = ?', array($row->cat_ID))->row()->result;
        return ($E) ? (string) $E : '0'; // or: return ($E) ? (string) $E : '~empty~';
    }

    function index() {

        $this->crud->set_table('mst_media_cat')
        ->set_subject('Group')
        
        ->columns('cat_name','count_media')
            ->order_by("cat_name", "DESC")
            ->callback_column('cat_name',array($this,'callbackCatName'))
            ->callback_column('count_media', array($this, 'countMedia'))
            ->display_as('cat_id', 'ID')
            ->display_as('cat_name', 'GroupName')
            ->display_as('sum_media', 'Total')
            ->fields('cat_name', 'sum_media')

//        ->add_action('Add', base_url().'themeimages/delete.png', 'demo/action_more','ui-icon-image',array($this,'callGroupDetail'))
        
        ->add_fields('cat_name')->edit_fields('cat_name');

//        $output = $this->crud->render();
        $this->output();
        
    }

 
    function callbackCatName($value = '', $primary_key = null)
    {
        return '<a href="'.site_url('media?cat_id='.$primary_key->cat_ID).'">'.$value.'</a>';
    }


    public function callGroupDetail($primary_key = '', $row)
    {
        return site_url('group').'/'.$primary_key;
    }
    
    
    public function media() {
        
        $cat_id = $this->uri->segment(2);
        
        
        

        $this->crud->set_table('mst_media')
            ->where("cat_id", $cat_id)
//        $crud->set_relation('officeCode','offices','city');
//        $crud->display_as('officeCode','Office City');
        ->set_subject('Media')
        
        ->columns('media_type', 'media_desc', 'media_name', 'media_epire', 'create_date', 'update_date')
        
        ->display_as('media_desc', 'Media')
            ->display_as('media_type', 'ประเภทไฟล์')
            ->display_as('media_name', 'ชื่อ')
            ->display_as('media_epire', 'Expire Date')
            ->display_as('create_date', 'Create Date')
            ->display_as('update_date', 'Update Date')
            
        
        ->field_type('media_epire', "date")
            ->field_type('update_date', "date")
            ->field_type('create_date', "date") 
        
        ->add_action('Edit', base_url().'assets/grocery_crud/themes/flexigrid/css/images/edit.png', 'group/media/edit/'.$cat_id)
        ->add_action('Delete', base_url().'assets/grocery_crud/themes/flexigrid/css/images/close.png', 'group/media/edit/'.$cat_id)
//        $crud->add_action('Edit', base_url().'', 'demo/action_more','ui-icon-image',array($this,'callGroupDetail'))

        ->set_field_upload('media_filename', 'assets/uploads/media')

        ->callback_before_upload(array($this, 'callbackBeforeUpload'))
        ->callback_after_upload(array($this, 'callbackAfterUpload'))
        ->unset_edit()->unset_delete();

//        $output = $this->crud->render();

        $this->output();
    }
    
    
    
    function group() {
        
        $cat_id = $this->uri->segment(4);
        
        
        $crud = new grocery_CRUD();

        $crud->set_table('mst_media')
            ->where("cat_id", $cat_id)
//        $crud->set_relation('officeCode','offices','city');
//        $crud->display_as('officeCode','Office City');
        ->set_subject('Media')
        
        ->columns('media_name', 'media_type', 'media_path', 'media_filename', 'media_size', 'media_lenght')
        
        ->display_as('media_type', 'ประเภทไฟล์')
            ->display_as('media_name', 'ชื่อ')
            ->display_as('media_path', 'ที่อยู่ไฟล์')
            ->display_as('media_filename', 'ไฟล์')
            ->display_as('media_size', 'ขนาด')

        ->field_type("media_path", "readonly")

        ->required_fields("media_filename")

        ->set_field_upload('media_filename', 'assets/uploads/media')

        ->callback_before_upload(array($this, 'callbackBeforeUpload'))
        ->callback_after_upload(array($this, 'callbackAfterUpload'))
                ->setListUrl(site_url()."/group/".$cat_id) ;
//        $crud->callback_after_update(function(){
//            $crud->set_lang_string('update_success_message',
//		 'Your data has been successfully stored into the database.<br/>Please wait while you are redirecting to the list page.
//		 <script type="text/javascript">
//		  window.location = "'.site_url(strtolower(__CLASS__).'/'.strtolower(__FUNCTION__)).'";
//		 </script>
//		 <div style="display:none">
//		 '
//            );
//        });
             

        

        $output = $crud->render();

        $this->_exampleOutput($output);
    }
    
    
    
    function setDefaultAction(){
        $this->crud->set_theme('datatables')->unset_print()->unset_export()->unset_read();
    }
}