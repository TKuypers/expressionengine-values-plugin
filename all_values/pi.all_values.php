<?php
/**
 * ExpressionEngine 3.x All values plugin
 *
 * @package     ExpressionEngine
 * @plugin      All values
 * @author      Ties Kuypers
 * @copyright   Copyright (c) 2016 - Ties Kuypers
 * @link        http://expertees.nl/expressionengine-all-values-plugin
 * @license 
 *
 * Copyright (c) 2016, Expertees webdevelopment
 * All rights reserved.
 */
namespace Exp\All_values;

class All_values {

	public $return_data = "";

    public function __construct()
    {
    	error_reporting(E_ALL);
    }

    public function field()
    {
    	$field_name = ee()->TMPL->fetch_param('field_name', NULL);
    	$limit      = ee()->TMPL->fetch_param('limit', 100);
    	$offset     = ee()->TMPL->fetch_param('offset', 0);
    	$orderby    = ee()->TMPL->fetch_param('orderby', 'default');
    	$sort       = ee()->TMPL->fetch_param('sort', 'asc');

    	$tag_data   = ee()->TMPL->tagdata;

    	// check if we have a field
    	if($field_name != NULL)
    	{
			$site_id    = ee()->config->item('site_id');

			$field_query = "SELECT fields.field_id,
								   fields.field_name,
								   fields.field_type

			  				FROM exp_channel_fields AS fields
			  				WHERE fields.field_name=? AND site_id=?";

			$field_data = ee()->db->query($field_query, array($field_name, $site_id));
			if($field_data->num_rows() == 1)
			{
				$field      = $field_data->row();
				$field_id   = $field->field_id;
				$field_type = $field->field_type;

	    		$orderby    = ($orderby == 'default') ? ($field_type == 'grid') ? 'row_order' : $field_name : $orderby;
	    		$sort_query = "ORDER BY $orderby $sort LIMIT $offset,$limit";

	    		if($field_type == 'grid')
				{
					$columns_query = "SELECT field.*,
											 fields.col_name,
											 fields.col_id,
											 fields.col_type

							  		  FROM exp_channel_fields AS field
							  	
							  		  JOIN exp_grid_columns AS fields
							  		  ON fields.field_id=field.field_id

							  		  WHERE fields.field_id=$field_id";

					$columns = ee()->db->query($columns_query)->result();
				    $select = array();
				    $group  = array();
				    foreach($columns as $row)
				    {
				    	$col_id        = $row->col_id;
				    	$col_name      = $row->col_name;
				    	$col_type      = $row->col_type;
				    	$col_name_type = $col_name.'_type';
				    	$select[]      = "columns.col_id_$col_id AS $col_name, '$col_type' AS $col_name_type";
				    	$group[]       = "$col_name";
				    }

				    $select_str = implode(',', $select);
				    $group_str  = implode(',', $group);
				    $query = "SELECT columns.entry_id,
				    			     $select_str

				    		  FROM exp_channel_grid_field_$field_id AS columns 

				    		  GROUP BY LOWER($group_str) ";
				}
				else
				{
					$query = "SELECT rows.entry_id,
									 rows.field_id_$field_id AS $field_name

					  		  FROM exp_channel_fields AS field
					  	
							  JOIN exp_channel_data AS rows
					  		  ON rows.site_id=field.site_id AND field_id_$field_id IS NOT NULL

					  		  WHERE field.field_id=$field_id 

					  		  GROUP BY LOWER($field_name) ";
				}

				$query  .= $sort_query;
				$results = ee()->db->query($query)->result_array();

				// loop trough the results
				foreach($results as $result)
				{
					$variables[] = $result;
				}

				return ee()->TMPL->parse_variables($tag_data, $variables);
    		}

    	}
    }


    public static function usage()
    {
    	return '';
    }
}

/* End of file pi.all_values.php */
/* Location: ./system/user/addons/all_values/pi.all_values.php */