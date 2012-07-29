<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$plugin_info = array(
  'pi_name'           => 'Get Expresso Store Order',
  'pi_version'        => '.01',
  'pi_author'         => 'Sean Gravener',
  'pi_author_url'     => 'http://sean.gravener.net',
  'pi_description'    => 'determine if a member has purchased a particular entry_id (product) from the Expresso Store',
  'pi_usage'          => Ssc_get_expstore::usage()
);

class Ssc_expresso {

  var $return_data;
  
  /**
   * Constructor
   *
   */
  function Ssc_expresso()
  {
    $this->EE =& get_instance();
  }
  
  function is_owner()
  {
    
    // get tag paramaters
    $entry_id  = $this->_get_param('entry_id');
    $redirect  = $this->_get_param('redirect');
    $member_id = $this->_get_param('member_id');

    // make sure {member_id} is parsed
    $member_id = $this->EE->TMPL->parse_globals($member_id);

    $tags[0] = array(
      'order_id' => ''
    );

    if ($entry_id && is_numeric($entry_id)) {
      $tags[0] = array(
        'order_id' => $this->_first_owned_entry($member_id, $entry_id)
      );
    }
    elseif ($redirect) {
      $this->EE->functions->redirect($this->EE->functions->create_url($redirect));
    }

    return $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $tags);

  }

  function _first_owned_entry($member_id, $entry_id) 
  {

    // @todo test if expresso tables exist

    // get the first order owned by the member
    $this->EE->db->select('store_orders.order_id', 1);
    $this->EE->db->from('store_orders');
    $this->EE->db->join('store_order_items', 'store_order_items.order_id = store_orders.order_id');
    $this->EE->db->where('store_orders.member_id', $member_id);
    $this->EE->db->where('store_order_items.entry_id', $entry_id);

    $order_item = $this->EE->db->get();

    if ($order_item->num_rows() > 0) 
    { 
      $row = $order_item->row();
      $order_id = $row->order_id;
      return $order_id;
    }

  }
  

  // --------------------------------------------------------------------
  
  function _get_param($key, $default_value = '')
  {
    $val = $this->EE->TMPL->fetch_param($key);
    
    if($val == '') {
      return $default_value;
    }
    return $val;
  }
  
  
  function usage()
  {
    ob_start(); 
    ?>
    
    Use this plugin to determine if a member has purchased a particular entry_id (product) from the Expresso Store.

    Use redirect parameter to redirect member to the product page or error page.

    {exp:ssc_expstore:is_owner entry_id='{segment_3}' member_id='{member_id}' redirect='store/product/{segment_3}'}

    <?php
    $buffer = ob_get_contents();
  
    ob_end_clean(); 

    return $buffer;
  }

  // --------------------------------------------------------------------

}
// END CLASS

/* End of file */