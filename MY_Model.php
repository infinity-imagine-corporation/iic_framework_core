<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class MY_Model extends CI_model {
	
    public function __construct()
    {
        parent::__construct();
    }
}

class IIC_Model extends MY_Model {
	
    // ------------------------------------------------------------------------
    // CONSTRUCTOR
    // ------------------------------------------------------------------------
    
    public function __construct()
    {
        parent::__construct();
    }
	
    // ------------------------------------------------------------------------
    // VARIABLE
    // ------------------------------------------------------------------------
	
    /**
     * Setup database
     */
    
    protected $table = array(
    							'main'		=> 'content'
							);
    
    // ------------------------------------------------------------------------
    // FUNCTION
    // ------------------------------------------------------------------------
    
    /**
     * Get content detail
     *
     * @access  public
     * @param   int     $id     
     * @return  array
     */
    
    function get_content($id)
    {       
        $this->db->where('id', $id);
        $_query = $this->db->get($this->table['main']);
        
        return $_query->row_array();
    }   
    
    // ------------------------------------------------------------------------
    
    /**
     * Get content list
     *
     * @access  public
     * @param   int     $limit
     * @param   int     $offset    
     * @param   string  $select     
     * @param   array   $where     
     * @param   string	$order_by     
     * @param   string	$order_direction      
     * @return  array
     */
    
    function list_content($limit = 25, $offset = 0, $select = NULL, $where = NULL, $order_by = NULL, $order_direction = 'ASC')
    {
    	// Select
    	if($select != '')
		{
			$this->db->select($select);
		}  
		
    	// Where
    	if(is_array($where))
		{
			$this->db->where($where);
		}   
		
		// Ordering
		if(is_null($order_by))
		{
			$this->db->order_by('id', 'DESC');
		}  
		else
		{
			$this->db->order_by($order_by, $order_direction);
		}
		      
        $_query = $this->db->get($this->table['main'], $limit, $offset);
        
        return $_query->result_array();
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Search content
     *
     * @access  public
     * @param   string  $keyword        
     * @param   string  $criteria   
     * @return  array
     */
    
    function search_content($keyword, $criteria, $limit = 25, $offset = 0)
    {   
        $this->db->like($criteria, $keyword);
        $_query = $this->db->get($this->table['main'], $limit, $offset);
        
        return $_query->result_array();
    }   
    
    // ------------------------------------------------------------------------
    
    /**
     * Create content 
     *
     * @access  public
     * @param   array   $data   
     */
    
    function create_content($data)
    {
        $this->db->insert($this->table['main'], $data);
		
		return TRUE;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Update content
     *
     * @access  public
     * @param   int     $id     
     * @param   array   $data   
     */
    
    function update_content($id, $data)
    {               
        $this->db->where('id', $id);
        $_query = $this->db->update($this->table['main'], $data);
		
		return TRUE;
    }
    
    // ------------------------------------------------------------------------
    
    /**
     * Delete content
     *
     * @access  public
     * @param   array   $id     
     */
    
    function delete_content($id)
    {       
        for($loop = 0; $loop < count($id); $loop++)
        {
            $this->db->where('id', $id[$loop]);
            $this->db->delete($this->table['main']);
        }
		
		return TRUE;
    }	
	
	// ------------------------------------------------------------------------
	
	/**
	 * Count content
	 *
	 * @access	public	
	 * @param 	array		$where		
	 * @return	integer
	 */
	
	function count_content($where = NULL)
	{		
		$this->db->select('COUNT(*) AS total');

		if(is_array($where) && count($where) > 0)
		{
			$this->db->where($where);
		}
		
		$_query = $this->db->get($this->table['main']);
		
		$_total = $_query->row_array();
		
		return $_total['total'];
	}		
	
	// ------------------------------------------------------------------------
	
	/**
	 * Empty main table
	 *
	 * @access	public		
	 * @return	bool
	 */
	
	function empty_content()
	{		
		$this->db->empty_table($this->table['main']);
		
		return TRUE;
	}

    // ------------------------------------------------------------------------
}


/* End of file MY_Model.php */
/* Location: ./application/core/MY_Model.php */