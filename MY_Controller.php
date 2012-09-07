<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends MX_Controller {

    public function MY_Controller()
    {
        parent::MX_Controller();
    }
	
    public function __construct()
    {
        parent::__construct();
    }
}

// ------------------------------------------------------------------------

class IIC_Controller extends MX_Controller {
	
	protected $module_config = array(
										'module'		=> '',
										'controller'	=> '',
										'model'			=> '',
										'form'			=> ''
									);
	protected $content_model;
	protected $content_form;
	
	// ------------------------------------------------------------------------
	// CONSTRUCTOR
	// ------------------------------------------------------------------------
	
	function __construct()
	{
		parent::__construct();
		
		// Load language
		$this->config->load('../../modules/backoffice/config/config');
		$this->lang->load(
							'backoffice', 
							$this->config->item('backoffice_language'), 
							FALSE, 
							TRUE, 
							'application/modules/backoffice/'
						 );
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Get content
	 *
	 * @access	public
	 * @param 	integer	$id
	 * @return	json
	 */
	
	function get_content($id)
	{
		return json_encode($this->content_model->get_content($id));
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Get form
	 *
	 * @access	public
	 * @param 	integer	$id
	 * @return	html
	 */
	
	function get_content_form($id = NULL)
	{
		$this->content_form = ($this->module_config['form'] != '') ? $this->module_config['form'] : $this->content_form;
		
		$_data = ($id != NULL) ? $this->content_model->get_content($id) : NULL;	
		
		$this->load->view($this->content_form, $_data);	
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Get content list
	 *
	 * @access	public
	 * @return	json
	 */
	  
    function list_content($limit = 25, $offset = 0, $select = NULL, $where = NULL, $order_by = NULL, $order_direction = 'ASC')
	{
		$limit = ($this->input->post('limit')) ? $this->input->post('limit') : $limit;
		$offset = ($this->input->post('offset')) ? $this->input->post('offset') : $offset;
		$select = ($this->input->post('select')) ? $this->input->post('select') : $select;
		$where = ($this->input->post('where')) ? $this->input->post('where') : $where;
		$order_by = ($this->input->post('order_by')) ? $this->input->post('order_by') : $order_by;
		$order_direction = ($this->input->post('order_direction')) ? $this->input->post('order_direction') : $order_direction;
		
		echo json_encode($this->content_model->list_content($limit, $offset, $select, $where, $order_by, $order_direction));	
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Get content list
	 *
	 * @access	public
	 * @return	json
	 */
	  
    function sort_content($order_by = NULL, $order_direction = 'ASC')
	{
		$order_by = ($this->input->post('order_by')) ? $this->input->post('order_by') : $order_by;
		$order_direction = ($this->input->post('order_direction')) ? $this->input->post('order_direction') : $order_direction;
		
		echo json_encode($this->content_model->list_content('', '', '', '', $order_by, $order_direction));	
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Search content list
	 *
	 * @access	public
	 * @return	json
	 */
	  
	function search_content()
	{		
		$_data = $this->input->post();
		
		echo json_encode($this->content_model->search_content($_data['keyword'], $_data['criteria']));	
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Create content 
	 *
	 * @access	public
	 */
	
	function create_content()
	{		
		$_data = $this->input->post();
		
		unset($_data['id']);
				 
		$this->content_model->create_content($_data);
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Update content 
	 *
	 * @access	public
	 */
	
	function update_content()
	{
		$_data = $this->input->post();
		$_id = $_data['id'];
		
		unset($_data['id']);
				 
		$this->content_model->update_content($_id, $_data);
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Delete content 
	 *
	 * @access	public
	 */
	 
	function delete_content()
	{
		$this->content_model->delete_content($this->input->post('id'));
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Get content selecbox option 
	 *
	 * @access	public
	 * @param 	string	$selected	
	 * @return	mixed
	 */
	
	function get_content_selectbox_option($selected = NULL)
	{
		$_option = '';
		$_group = $this->content_model->list_content(25, 0, NULL, NULL, 'name');
		
		foreach($_group as $_data)
		{
			$_selected = ($_data['id'] == $selected) ? ' selected="selected"' : '';
			$_option .= '<option value="'. $_data['id'].'"'.$_selected.'>'.$_data['name'].'</option>';
		}
		
		return $_option;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Module page for display backoffice module
	 *
	 * @access	public
	 */
	
	function get_menu()
	{
		$this->load->view('menu');
	}
	
	// ------------------------------------------------------------------------
}


/* End of file IIC_Controller.php */
/* Location: ./system/core/IIC_Controller.php */