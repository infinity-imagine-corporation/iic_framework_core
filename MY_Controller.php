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
	 * @return	array
	 */
	
	function get_content($id)
	{
		$_result = $this->content_model->get_content($id);
		
		if(count($_result) > 0)
		{
			echo json_encode($_result);
			return $_result;
		}
		else 
		{
			$this->output->set_status_header('204');	
		}
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
	 * Reformat content
	 * 
	 * for reformat content before send to user 
	 * use in list_content, sort_content, search_content
	 *
	 * @access	public
	 * @param	array	$content
	 */
	
	function reformat_content($content)
	{
		return $content;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Get content list
	 *
	 * @access	public
	 * @return	array
	 */
	  
    function list_content($limit = 25, $offset = 0, $select = NULL, 
    					  $where = NULL, $order_by = NULL, $order_direction = 'ASC')
	{
		$limit = ($this->input->post('limit')) ? $this->input->post('limit') : $limit;
		$offset = ($this->input->post('offset')) ? $this->input->post('offset') : $offset;
		$select = ($this->input->post('select')) ? $this->input->post('select') : $select;

		if($this->input->post('where'))
		{
			foreach ($this->input->post('where') as $key => $value) 
			{
				if($value == '')
				{
					$where[$key.' LIKE'] = '%'.$value.'%';
				}
				else 
				{
					$where[$key] = $value;
				}
			}
		}

		$order_by = ($this->input->post('order_by')) 
					 ? $this->input->post('order_by') 
					 : $order_by;

		$order_direction = ($this->input->post('order_direction')) 
							? $this->input->post('order_direction') 
							: $order_direction;
		
        // Reformat_content
		$_result = $this->content_model
                        ->list_content($limit, $offset, $select, $where, $order_by, $order_direction);
		
		echo json_encode($this->reformat_content($_result));

		return $_result;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Get content list
	 *
	 * @access	public
     * @param   string $order_by database's field name
     * @param   string $order_direction ASC | DESC
	 * @return	array
	 */
	  
    function sort_content($order_by = NULL, $order_direction = 'ASC')
	{
		$order_by = ($this->input->post('order_by')) 
                     ? $this->input->post('order_by') 
                     : $order_by;

		$order_direction = ($this->input->post('order_direction')) 
                            ? $this->input->post('order_direction') 
                            : $order_direction;
		
		$_where = array();
		
		if($this->input->post('where'))
		{
			foreach ($this->input->post('where') as $key => $value) 
			{
				if($value == '')
				{
					$_where[$key.' LIKE'] = '%'.$value.'%';
				}
				else 
				{
					$_where[$key] = $value;
				}
			}
		}
		
		$_result = $this->content_model
                        ->list_content('', '', '', $_where, $order_by, $order_direction);
        $_json_result = $this->reformat_content($_result);
        
        if(is_array($_result))
        {
            echo json_encode($_json_result);    

            return $_result;
        }
        else 
        {
            $this->output->set_status_header('500');    
            echo $_json_result; 
        }
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Search content list
	 *
	 * @access	public
     * @param   string $criteria database's field name
     * @param   string $keyword word to find
	 * @return	array
	 */
	  
	function search_content($criteria = NULL, $keyword = NULL)
	{
		$criteria = ($this->input->post('criteria')) ? $this->input->post('criteria') : $criteria;
		$keyword = ($this->input->post('keyword')) ? $this->input->post('keyword') : $keyword;
		
		if($this->input->post('where'))
		{
			foreach ($this->input->post('where') as $key => $value) 
			{
				if($value == '')
				{
					$_where[$key.' LIKE'] = '%'.$value.'%';
				}
				else 
				{
					$_where[$key] = $value;
				}
			}
		}
		
		$_where[$criteria.' LIKE'] = '%'.$keyword.'%';
		
		$_result = $this->content_model->list_content('', '', '', $_where);
        $_json_result = $this->reformat_content($_result);
		
		if(is_array($_result))
		{
			echo json_encode($_json_result);	

			return $_result;
		}
		else 
		{
			$this->output->set_status_header('500');	
			echo $_json_result;	
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Create content 
	 *
	 * @access	public
	 * @param	array	$data
	 */
	
	function create_content($data = NULL)
	{
        $_data = (is_null($data)) ? $this->input->post() : $data;
		
		if(is_null($_data))
		{
			$this->output->set_status_header('204');
		}
		else 
		{
            if(isset($_data['id']))
            {
                unset($_data['id']);
            } 
            
			$_result = $this->content_model->create_content($_data);
			
			if(is_int($_result))
			{
				$this->output->set_status_header('201');	
				echo $_result;
				return $_result;
			}
		}	 
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Update content 
	 *
	 * @access	public
	 * @param	array	$data - array of data to update
	 */
	
	function update_content($data = NULL)
	{
		$_data = (is_null($data)) ? $this->input->post() : $data;
		$_id = $_data['id'];
		
		unset($_data['id']);
		
		if(is_null($_data))
		{
			$this->output->set_status_header('204');	
		}
		else 
		{
			$_result = $this->content_model->update_content($_id, $_data);
		
			if($_result)
			{
				echo 'Updated';
			}
			else 
			{
				$this->output->set_status_header('204');	
				echo 'Update failed';
			}
		}
				 
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Delete content 
	 *
	 * @access	public
	 * @param	array|int	$id
	 */
	 
	function delete_content($id = NULL)
	{
		$id = (is_null($id)) ? $this->input->post('id') : $id;
		
		if( ! is_array($id))
		{
			$id = array(0 => $id);
		}
		
		$_return = $this->content_model->delete_content($id);
		
		if(is_int($_return))
		{
			$this->output->set_status_header('200');	
			echo 'Deleted '.$_return.' row(s)';
			
			return $_return;
		}
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
		$_group = $this->content_model->list_content(1000, 0, NULL, NULL, 'name');
		
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


/* End of file MY_Controller.php */
/* Location: ./system/core/MY_Controller.php */