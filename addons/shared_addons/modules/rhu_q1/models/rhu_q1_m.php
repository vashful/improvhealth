<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Group model
 *
 * @author Phil Sturgeon - PyroCMS Dev Team
 * @package PyroCMS
 * @subpackage Groups module
 * @category Modules
 *
 */
class Rhu_q1_m extends MY_Model
{
	/**
	 * Check a rule based on it's role
	 *
	 * @access public
	 * @param string $role The role
	 * @param array $location
	 * @return mixed
	 */
    public function get_maternal_quarterreport($q, $y)
    {
            if($q==1)
                {$between = "1 and 3";}
            elseif($q==2)
                {$between = "4 and 6";}
            elseif($q==3)
                {$between = "7 and 9";}  
            elseif($q==4)
                {$between = "10 and 12";}
            
            $query = $this->db->query("
                SELECT
                    ( SELECT count(*) FROM default_prenatals WHERE ( (MONTH(FROM_UNIXTIME(tt1)) BETWEEN $between and YEAR(FROM_UNIXTIME(tt1)) = $y) and (tt2 > 0 || tt3 > 0 || tt4 > 0 || tt5 > 0 ) ) || ( (MONTH(FROM_UNIXTIME(tt2)) = $q and YEAR(FROM_UNIXTIME(tt2)) = $y) and (tt3 > 0 || tt4 > 0 || tt5 > 0 || tt1 > 0 ) ) || ( (MONTH(FROM_UNIXTIME(tt3)) = $q and YEAR(FROM_UNIXTIME(tt3)) = $y) and (tt4 > 0 || tt5 > 0 || tt2 > 0 || tt1 > 0 ) ) || ( (MONTH(FROM_UNIXTIME(tt4)) = $q and YEAR(FROM_UNIXTIME(tt4)) = $y) and (tt5 > 0 || tt3 > 0 || tt2 > 0 || tt1 > 0 ) ) || ( (MONTH(FROM_UNIXTIME(tt5)) BETWEEN $between and YEAR(FROM_UNIXTIME(tt5)) = $y) and (tt4 > 0 || tt3 > 0 || tt2 > 0 || tt1 > 0 ) ) ) as total2,
                    ( SELECT count(*) FROM default_prenatals WHERE ( tt2 > 0 and (   (MONTH(FROM_UNIXTIME(tt2)) BETWEEN $between and YEAR(FROM_UNIXTIME(tt2)) = $y) || (MONTH(FROM_UNIXTIME(tt3)) BETWEEN $between and YEAR(FROM_UNIXTIME(tt3)) = $y) || (MONTH(FROM_UNIXTIME(tt4)) BETWEEN $between and YEAR(FROM_UNIXTIME(tt4)) = $y) || (MONTH(FROM_UNIXTIME(tt5)) BETWEEN $between and YEAR(FROM_UNIXTIME(tt5)) = $y)   ) ) ) as total3,
                    ( SELECT count(*) FROM default_prenatals WHERE  (MONTH(FROM_UNIXTIME(iron6_date)) BETWEEN $between and YEAR(FROM_UNIXTIME(iron6_date)) = $y) and iron5_date > 0 and iron4_date > 0 and iron3_date  > 0 and iron2_date > 0 and iron1_date > 0 ) as total4,
                    ( SELECT count(*) FROM default_prenatals WHERE  (MONTH(FROM_UNIXTIME(date_given_vit_a)) BETWEEN $between and YEAR(FROM_UNIXTIME(date_given_vit_a)) = $y) ) as total5, 
                    ( SELECT count(*) FROM default_postpartum WHERE ((MONTH(FROM_UNIXTIME(iron2_date)) BETWEEN $between and YEAR(FROM_UNIXTIME(iron2_date)) = $y) and (iron1_date > 0 || iron3_date > 0)) ) as total6,
                    ( SELECT count(*) FROM default_postpartum WHERE (iron1_tabs > 0 and iron2_tabs > 0 and iron3_tabs > 0) and (MONTH(FROM_UNIXTIME(iron3_date)) BETWEEN $between and YEAR(FROM_UNIXTIME(iron3_date)) = $y) ) as total7,
                    ( SELECT count(*) FROM default_postpartum WHERE (MONTH(FROM_UNIXTIME(vitamin_a)) BETWEEN $between and YEAR(FROM_UNIXTIME(vitamin_a)) = $y ) ) as total8,
                    ( SELECT count(*) FROM `default_postpartum` WHERE ((MONTH(FROM_UNIXTIME(delivery)) BETWEEN $between and YEAR(FROM_UNIXTIME(delivery)) = $y ) and (( breastfeeding - delivery ) / 60 ) < 61) ) as total9
                FROM dual
                ");
            $rows = $query->row_array();
            return $rows;          
    }
    
    public function get_familyplanning_quarterreport($q, $y)
    {
            if($q==1)
                {
                    $between = "1 and 3";
                    $begin_m = "1";  
                }
            elseif($q==2)
                {
                    $between = "4 and 6";
                    $begin_m = "4"; 
                }
            elseif($q==3)
                {
                    $between = "7 and 9";
                    $begin_m = "7"; 
                }  
            elseif($q==4)
                {
                    $between = "10 and 12";
                    $begin_m = "10";
                }
            
            $query = $this->db->query("
                SELECT  
                    (SELECT count(*)  FROM default_family_planning WHERE  method_id = 11 and client_type = 'CU' and (MONTH(FROM_UNIXTIME(date_started)) BETWEEN $between and YEAR(FROM_UNIXTIME(date_started)) = $y) ) as fstr_cu_begin,   
                    (SELECT count(*)  FROM default_family_planning WHERE  method_id = 11 and client_type = 'NA' and (MONTH(FROM_UNIXTIME(date_started)) BETWEEN $between and YEAR(FROM_UNIXTIME(date_started)) = $y) ) as fstr_na, 
                    (SELECT count(*)  FROM default_family_planning WHERE  method_id = 11 and ( client_type != 'NA' and client_type != 'CU' ) and (MONTH(FROM_UNIXTIME(date_started)) BETWEEN $between and YEAR(FROM_UNIXTIME(date_started)) = $y) ) as fstr_others,
                    (SELECT count(*)  FROM default_family_planning WHERE  method_id = 11 and (MONTH(FROM_UNIXTIME(drop_out_date)) BETWEEN $between and YEAR(FROM_UNIXTIME(drop_out_date)) = $y) ) as fstr_dropout,


                    (SELECT count(*)  FROM default_family_planning WHERE  method_id = 10 and client_type = 'CU' and (MONTH(FROM_UNIXTIME(date_started)) BETWEEN $between and YEAR(FROM_UNIXTIME(date_started)) = $y) ) as mstr_cu_begin,
                    (SELECT count(*)  FROM default_family_planning WHERE  method_id = 10 and client_type = 'NA' and (MONTH(FROM_UNIXTIME(date_started)) BETWEEN $between and YEAR(FROM_UNIXTIME(date_started)) = $y) ) as mstr_na, 
                    (SELECT count(*)  FROM default_family_planning WHERE  method_id = 10 and ( client_type != 'NA' and client_type != 'CU' ) and (MONTH(FROM_UNIXTIME(date_started)) BETWEEN $between and YEAR(FROM_UNIXTIME(date_started)) = $y) ) as mstr_others,  
                    (SELECT count(*)  FROM default_family_planning WHERE  method_id = 10 and (MONTH(FROM_UNIXTIME(drop_out_date)) BETWEEN $between and YEAR(FROM_UNIXTIME(drop_out_date)) = $y) ) as mstr_dropout,


                    (SELECT count(*)  FROM default_family_planning WHERE  method_id = 4 and client_type = 'CU' and (MONTH(FROM_UNIXTIME(date_started)) BETWEEN $between and YEAR(FROM_UNIXTIME(date_started)) = $y) ) as pills_cu_begin,  
                    (SELECT count(*)  FROM default_family_planning WHERE  method_id = 4 and client_type = 'NA' and (MONTH(FROM_UNIXTIME(date_started)) BETWEEN $between and YEAR(FROM_UNIXTIME(date_started)) = $y) ) as pills_na, 
                    (SELECT count(*)  FROM default_family_planning WHERE  method_id = 4 and ( client_type != 'NA' and client_type != 'CU' ) and (MONTH(FROM_UNIXTIME(date_started)) BETWEEN $between and YEAR(FROM_UNIXTIME(date_started)) = $y) ) as pills_others,   
                    (SELECT count(*)  FROM default_family_planning WHERE  method_id = 4 and (MONTH(FROM_UNIXTIME(drop_out_date)) BETWEEN $between and YEAR(FROM_UNIXTIME(drop_out_date)) = $y) ) as pills_dropout,     
                                          

                    (SELECT count(*)  FROM default_family_planning WHERE  method_id = 3 and client_type = 'CU' and (MONTH(FROM_UNIXTIME(date_started)) BETWEEN $between and YEAR(FROM_UNIXTIME(date_started)) = $y) ) as iud_cu_begin,
                    (SELECT count(*)  FROM default_family_planning WHERE  method_id = 3 and client_type = 'NA' and (MONTH(FROM_UNIXTIME(date_started)) BETWEEN $between and YEAR(FROM_UNIXTIME(date_started)) = $y) ) as iud_na, 
                    (SELECT count(*)  FROM default_family_planning WHERE  method_id = 3 and ( client_type != 'NA' and client_type != 'CU' ) and (MONTH(FROM_UNIXTIME(date_started)) BETWEEN $between and YEAR(FROM_UNIXTIME(date_started)) = $y) ) as iud_others,
                    (SELECT count(*)  FROM default_family_planning WHERE  method_id = 3 and (MONTH(FROM_UNIXTIME(drop_out_date)) BETWEEN $between and YEAR(FROM_UNIXTIME(drop_out_date)) = $y) ) as iud_dropout,

                    (SELECT count(*)  FROM default_family_planning WHERE  method_id = 2 and client_type = 'CU' and (MONTH(FROM_UNIXTIME(date_started)) BETWEEN $between and YEAR(FROM_UNIXTIME(date_started)) = $y) ) as dmpa_cu_begin,
                    (SELECT count(*)  FROM default_family_planning WHERE  method_id = 2 and client_type = 'NA' and (MONTH(FROM_UNIXTIME(date_started)) BETWEEN $between and YEAR(FROM_UNIXTIME(date_started)) = $y) ) as dmpa_na, 
                    (SELECT count(*)  FROM default_family_planning WHERE  method_id = 2 and ( client_type != 'NA' and client_type != 'CU' ) and (MONTH(FROM_UNIXTIME(date_started)) BETWEEN $between and YEAR(FROM_UNIXTIME(date_started)) = $y) ) as dmpa_others,
                    (SELECT count(*)  FROM default_family_planning WHERE  method_id = 2 and (MONTH(FROM_UNIXTIME(drop_out_date)) BETWEEN $between and YEAR(FROM_UNIXTIME(drop_out_date)) = $y) ) as dmpa_dropout,

                    (SELECT count(*)  FROM default_family_planning WHERE  method_id = 6 and client_type = 'CU' and (MONTH(FROM_UNIXTIME(date_started)) BETWEEN $between and YEAR(FROM_UNIXTIME(date_started)) = $y) ) as nfpcm_cu_begin,
                    (SELECT count(*)  FROM default_family_planning WHERE  method_id = 6 and client_type = 'NA' and (MONTH(FROM_UNIXTIME(date_started)) BETWEEN $between and YEAR(FROM_UNIXTIME(date_started)) = $y) ) as nfpcm_na, 
                    (SELECT count(*)  FROM default_family_planning WHERE  method_id = 6 and ( client_type != 'NA' and client_type != 'CU' ) and (MONTH(FROM_UNIXTIME(date_started)) BETWEEN $between and YEAR(FROM_UNIXTIME(date_started)) = $y) ) as nfpcm_others,
                    (SELECT count(*)  FROM default_family_planning WHERE  method_id = 6 and (MONTH(FROM_UNIXTIME(drop_out_date)) BETWEEN $between and YEAR(FROM_UNIXTIME(drop_out_date)) = $y) ) as nfpcm_dropout,

                    (SELECT count(*)  FROM default_family_planning WHERE  method_id = 5 and client_type = 'CU' and (MONTH(FROM_UNIXTIME(date_started)) BETWEEN $between and YEAR(FROM_UNIXTIME(date_started)) = $y) ) as nfpbbt_cu_begin,
                    (SELECT count(*)  FROM default_family_planning WHERE  method_id = 5 and client_type = 'NA' and (MONTH(FROM_UNIXTIME(date_started)) BETWEEN $between and YEAR(FROM_UNIXTIME(date_started)) = $y) ) as nfpbbt_na, 
                    (SELECT count(*)  FROM default_family_planning WHERE  method_id = 5 and ( client_type != 'NA' and client_type != 'CU' ) and (MONTH(FROM_UNIXTIME(date_started)) BETWEEN $between and YEAR(FROM_UNIXTIME(date_started)) = $y) ) as nfpbbt_others,
                    (SELECT count(*)  FROM default_family_planning WHERE  method_id = 5 and (MONTH(FROM_UNIXTIME(drop_out_date)) BETWEEN $between and YEAR(FROM_UNIXTIME(drop_out_date)) = $y) ) as nfpbbt_dropout,

                    (SELECT count(*)  FROM default_family_planning WHERE  method_id = 7 and client_type = 'CU' and (MONTH(FROM_UNIXTIME(date_started)) BETWEEN $between and YEAR(FROM_UNIXTIME(date_started)) = $y) ) as nfpstm_cu_begin,
                    (SELECT count(*)  FROM default_family_planning WHERE  method_id = 7 and client_type = 'NA' and (MONTH(FROM_UNIXTIME(date_started)) BETWEEN $between and YEAR(FROM_UNIXTIME(date_started)) = $y) ) as nfpstm_na, 
                    (SELECT count(*)  FROM default_family_planning WHERE  method_id = 7 and ( client_type != 'NA' and client_type != 'CU' ) and (MONTH(FROM_UNIXTIME(date_started)) BETWEEN $between and YEAR(FROM_UNIXTIME(date_started)) = $y) ) as nfpstm_others,
                    (SELECT count(*)  FROM default_family_planning WHERE  method_id = 7 and (MONTH(FROM_UNIXTIME(drop_out_date)) BETWEEN $between and YEAR(FROM_UNIXTIME(drop_out_date)) = $y) ) as nfpstm_dropout,

                    (SELECT count(*)  FROM default_family_planning WHERE  method_id = 9 and client_type = 'CU' and (MONTH(FROM_UNIXTIME(date_started)) BETWEEN $between and YEAR(FROM_UNIXTIME(date_started)) = $y) ) as nfdsdm_cu_begin,
                    (SELECT count(*)  FROM default_family_planning WHERE  method_id = 9 and client_type = 'NA' and (MONTH(FROM_UNIXTIME(date_started)) BETWEEN $between and YEAR(FROM_UNIXTIME(date_started)) = $y) ) as nfdsdm_na, 
                    (SELECT count(*)  FROM default_family_planning WHERE  method_id = 9 and ( client_type != 'NA' and client_type != 'CU' ) and (MONTH(FROM_UNIXTIME(date_started)) BETWEEN $between and YEAR(FROM_UNIXTIME(date_started)) = $y) ) as nfdsdm_others,
                    (SELECT count(*)  FROM default_family_planning WHERE  method_id = 9 and (MONTH(FROM_UNIXTIME(drop_out_date)) BETWEEN $between and YEAR(FROM_UNIXTIME(drop_out_date)) = $y) ) as nfdsdm_dropout,

                    (SELECT count(*)  FROM default_family_planning WHERE  method_id = 8 and client_type = 'CU' and (MONTH(FROM_UNIXTIME(date_started)) BETWEEN $between and YEAR(FROM_UNIXTIME(date_started)) = $y) ) as nfplam_cu_begin,
                    (SELECT count(*)  FROM default_family_planning WHERE  method_id = 8 and client_type = 'NA' and (MONTH(FROM_UNIXTIME(date_started)) BETWEEN $between and YEAR(FROM_UNIXTIME(date_started)) = $y) ) as nfplam_na, 
                    (SELECT count(*)  FROM default_family_planning WHERE  method_id = 8 and ( client_type != 'NA' and client_type != 'CU' ) and (MONTH(FROM_UNIXTIME(date_started)) BETWEEN $between and YEAR(FROM_UNIXTIME(date_started)) = $y) ) as nfplam_others,
                    (SELECT count(*)  FROM default_family_planning WHERE  method_id = 8 and (MONTH(FROM_UNIXTIME(drop_out_date)) BETWEEN $between and YEAR(FROM_UNIXTIME(drop_out_date)) = $y) ) as nfplam_dropout,

                    (SELECT count(*)  FROM default_family_planning WHERE  method_id = 1 and client_type = 'CU' and (MONTH(FROM_UNIXTIME(date_started)) BETWEEN $between and YEAR(FROM_UNIXTIME(date_started)) = $y) ) as condom_cu_begin,
                    (SELECT count(*)  FROM default_family_planning WHERE  method_id = 1 and client_type = 'NA' and (MONTH(FROM_UNIXTIME(date_started)) BETWEEN $between and YEAR(FROM_UNIXTIME(date_started)) = $y) ) as condom_na, 
                    (SELECT count(*)  FROM default_family_planning WHERE  method_id = 1 and ( client_type != 'NA' and client_type != 'CU' ) and (MONTH(FROM_UNIXTIME(date_started)) BETWEEN $between and YEAR(FROM_UNIXTIME(date_started)) = $y) ) as condom_others,
                    (SELECT count(*)  FROM default_family_planning WHERE  method_id = 1 and (MONTH(FROM_UNIXTIME(drop_out_date)) BETWEEN $between and YEAR(FROM_UNIXTIME(drop_out_date)) = $y) ) as condom_dropout
                FROM dual
                    ");
            $rows = $query->row_array();
            return $rows;
    }
    
    public function get_dental_quarterreport($q, $y)
    {
            if($q==1)
                {$between = "1 and 3";}
            elseif($q==2)
                {$between = "4 and 6";}
            elseif($q==3)
                {$between = "7 and 9";}  
            elseif($q==4)
                {$between = "10 and 12";}
                
            $now = time();
            
            $query = $this->db->query("
                SELECT
                    (SELECT count(*) FROM default_dental_health a INNER JOIN default_clients b on a.client_id = b.id WHERE  a.orally_fit = 'Y' and TIMESTAMPDIFF(MONTH, FROM_UNIXTIME(b.dob), FROM_UNIXTIME($now)) >=12 and TIMESTAMPDIFF(MONTH, FROM_UNIXTIME(b.dob), FROM_UNIXTIME($now)) <=71)  as orally_fit_1271_mo,
                    (SELECT count(*) FROM default_dental_health a INNER JOIN default_clients b on a.client_id = b.id WHERE  a.orally_fit = 'Y' and TIMESTAMPDIFF(MONTH, FROM_UNIXTIME(b.dob), FROM_UNIXTIME($now)) >=12 and TIMESTAMPDIFF(MONTH, FROM_UNIXTIME(b.dob), FROM_UNIXTIME($now)) <=71 and b.gender = 'male')  as orally_fit_1271_mo_m,
                    (SELECT count(*) FROM default_dental_health a INNER JOIN default_clients b on a.client_id = b.id WHERE  a.orally_fit = 'Y' and TIMESTAMPDIFF(MONTH, FROM_UNIXTIME(b.dob), FROM_UNIXTIME($now)) >=12 and TIMESTAMPDIFF(MONTH, FROM_UNIXTIME(b.dob), FROM_UNIXTIME($now)) <=71 and b.gender = 'female')  as orally_fit_1271_mo_f,
                    (SELECT count(*) FROM default_dental_health a INNER JOIN default_clients b on a.client_id = b.id WHERE (MONTH(FROM_UNIXTIME(a.date_given_bohc)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.date_given_bohc)) = $y) and TIMESTAMPDIFF(MONTH, FROM_UNIXTIME(b.dob), FROM_UNIXTIME($now))>=12 and TIMESTAMPDIFF(MONTH, FROM_UNIXTIME(b.dob), FROM_UNIXTIME($now))<= 71) as bohc_1271_mo,
                    (SELECT count(*) FROM default_dental_health a INNER JOIN default_clients b on a.client_id = b.id WHERE (MONTH(FROM_UNIXTIME(a.date_given_bohc)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.date_given_bohc)) = $y) and TIMESTAMPDIFF(MONTH, FROM_UNIXTIME(b.dob), FROM_UNIXTIME($now))>=12 and TIMESTAMPDIFF(MONTH, FROM_UNIXTIME(b.dob), FROM_UNIXTIME($now))<= 71 and b.gender = 'male') as bohc_1271_mo_m,
                    (SELECT count(*) FROM default_dental_health a INNER JOIN default_clients b on a.client_id = b.id WHERE (MONTH(FROM_UNIXTIME(a.date_given_bohc)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.date_given_bohc)) = $y) and TIMESTAMPDIFF(MONTH, FROM_UNIXTIME(b.dob), FROM_UNIXTIME($now))>=12 and TIMESTAMPDIFF(MONTH, FROM_UNIXTIME(b.dob), FROM_UNIXTIME($now))<= 71 and b.gender = 'female') as bohc_1271_mo_f,
                    (SELECT count(*) FROM default_dental_health a INNER JOIN default_clients b on a.client_id = b.id WHERE (MONTH(FROM_UNIXTIME(a.date_given_bohc)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.date_given_bohc)) = $y) and TIMESTAMPDIFF(MONTH, FROM_UNIXTIME(b.dob), FROM_UNIXTIME($now))>=120 and TIMESTAMPDIFF(MONTH, FROM_UNIXTIME(b.dob), FROM_UNIXTIME($now))<= 299) as bohc_1024_yo,
                    (SELECT count(*) FROM default_dental_health a INNER JOIN default_clients b on a.client_id = b.id WHERE (MONTH(FROM_UNIXTIME(a.date_given_bohc)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.date_given_bohc)) = $y) and TIMESTAMPDIFF(MONTH, FROM_UNIXTIME(b.dob), FROM_UNIXTIME($now))>=120 and TIMESTAMPDIFF(MONTH, FROM_UNIXTIME(b.dob), FROM_UNIXTIME($now))<= 299 and b.gender = 'male') as bohc_1024_yo_m,
                    (SELECT count(*) FROM default_dental_health a INNER JOIN default_clients b on a.client_id = b.id WHERE (MONTH(FROM_UNIXTIME(a.date_given_bohc)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.date_given_bohc)) = $y) and TIMESTAMPDIFF(MONTH, FROM_UNIXTIME(b.dob), FROM_UNIXTIME($now))>=120 and TIMESTAMPDIFF(MONTH, FROM_UNIXTIME(b.dob), FROM_UNIXTIME($now))<= 299 and b.gender = 'female') as bohc_1024_yo_f,
                    (SELECT count(*) FROM default_dental_health a INNER JOIN default_clients b on a.client_id = b.id WHERE (MONTH(FROM_UNIXTIME(a.date_given_bohc)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.date_given_bohc)) = $y) and b.gender = 'female') as bohc_pregnant,
                    (SELECT count(*) FROM default_dental_health a INNER JOIN default_clients b on a.client_id = b.id WHERE (MONTH(FROM_UNIXTIME(a.date_given_bohc)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.date_given_bohc)) = $y) and TIMESTAMPDIFF(MONTH, FROM_UNIXTIME(b.dob), FROM_UNIXTIME($now))>= 720) as bohc_60plus_yo,
                    (SELECT count(*) FROM default_dental_health a INNER JOIN default_clients b on a.client_id = b.id WHERE (MONTH(FROM_UNIXTIME(a.date_given_bohc)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.date_given_bohc)) = $y) and TIMESTAMPDIFF(MONTH, FROM_UNIXTIME(b.dob), FROM_UNIXTIME($now))>= 720 and b.gender = 'male') as bohc_60plus_yo_m,
                    (SELECT count(*) FROM default_dental_health a INNER JOIN default_clients b on a.client_id = b.id WHERE (MONTH(FROM_UNIXTIME(a.date_given_bohc)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.date_given_bohc)) = $y) and TIMESTAMPDIFF(MONTH, FROM_UNIXTIME(b.dob), FROM_UNIXTIME($now))>= 720 and b.gender = 'female') as bohc_60plus_yo_f
                FROM dual
                ");
            $rows = $query->row_array();
            return $rows;          
    }
    
    public function get_childcare_quarterreport($q, $y)
    {
        if($q==1)
            {$between = "1 and 3";}
        elseif($q==2)
            {$between = "4 and 6";}
        elseif($q==3)
            {$between = "7 and 9";}  
        elseif($q==4)
            {$between = "10 and 12";}
        
        $query = $this->db->query("
        SELECT
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'female' and (MONTH(FROM_UNIXTIME(a.im_bcg)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.im_bcg)) = $y) ) as bcgF_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'male' and (MONTH(FROM_UNIXTIME(a.im_bcg)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.im_bcg)) = $y) ) as bcgM_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'female' and (MONTH(FROM_UNIXTIME(a.im_dpt1)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.im_dpt1)) = $y) ) as dpt1F_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'male' and (MONTH(FROM_UNIXTIME(a.im_dpt1)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.im_dpt1)) = $y) ) as dpt1M_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'female' and (MONTH(FROM_UNIXTIME(a.im_dpt2)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.im_dpt2)) = $y) ) as dpt2F_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'male' and (MONTH(FROM_UNIXTIME(a.im_dpt2)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.im_dpt2)) = $y) ) as dpt2M_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'female' and (MONTH(FROM_UNIXTIME(a.im_dpt3)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.im_dpt3)) = $y) ) as dpt3F_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'male' and (MONTH(FROM_UNIXTIME(a.im_dpt3)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.im_dpt3)) = $y) ) as dpt3M_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'female' and (MONTH(FROM_UNIXTIME(a.im_polio1)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.im_polio1)) = $y) ) as opv1F_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'male' and (MONTH(FROM_UNIXTIME(a.im_polio1)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.im_polio1)) = $y) ) as opv1M_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'female' and (MONTH(FROM_UNIXTIME(a.im_polio2)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.im_polio2)) = $y) ) as opv2F_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'male' and (MONTH(FROM_UNIXTIME(a.im_polio2)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.im_polio2)) = $y) ) as opv2M_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'female' and (MONTH(FROM_UNIXTIME(a.im_polio3)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.im_polio3)) = $y) ) as opv3F_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'male' and (MONTH(FROM_UNIXTIME(a.im_polio3)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.im_polio3)) = $y) ) as opv3M_total,
        	
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'female' and (MONTH(FROM_UNIXTIME(a.im_hepa_b1_with_in)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.im_hepa_b1_with_in)) = $y) ) as hepa_b1_with_inF_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'male' and (MONTH(FROM_UNIXTIME(a.im_hepa_b1_with_in)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.im_hepa_b1_with_in)) = $y) ) as hepa_b1_with_inM_total,
        	
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'female' and (MONTH(FROM_UNIXTIME(a.im_hepa_b1_more_than)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.im_hepa_b1_more_than)) = $y) ) as hepa_b1_more_thanF_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'male' and (MONTH(FROM_UNIXTIME(a.im_hepa_b1_more_than)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.im_hepa_b1_more_than)) = $y) ) as hepa_b1_more_thanM_total,
        	
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'female' and (MONTH(FROM_UNIXTIME(a.im_hepa_b2)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.im_hepa_b2)) = $y) ) as hepa_b2F_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'male' and (MONTH(FROM_UNIXTIME(a.im_hepa_b2)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.im_hepa_b2)) = $y) ) as hepa_b2M_total,
        	
        		
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'female' and (MONTH(FROM_UNIXTIME(a.im_hepa_b3)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.im_hepa_b3)) = $y) ) as hepa_b3F_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'male' and (MONTH(FROM_UNIXTIME(a.im_hepa_b3)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.im_hepa_b3)) = $y) ) as hepa_b3M_total,
        
        		
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'female' and (MONTH(FROM_UNIXTIME(a.im_anti_measles)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.im_anti_measles)) = $y) ) as im_anti_measlesF_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'male' and (MONTH(FROM_UNIXTIME(a.im_anti_measles)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.im_anti_measles)) = $y) ) as im_anti_measlesM_total,
        		
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'female' and (MONTH(FROM_UNIXTIME(a.im_fully)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.im_fully)) = $y)) as im_fully_under1F_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'male' and (MONTH(FROM_UNIXTIME(a.im_fully)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.im_fully)) = $y)) as im_fully_under1M_total, 
            
            ( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'female' and (MONTH(FROM_UNIXTIME(a.im_bcg)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.im_bcg)) = $y) and (MONTH(FROM_UNIXTIME(a.im_dpt1)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.im_dpt1)) = $y) and (MONTH(FROM_UNIXTIME(a.im_dpt2)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.im_dpt2)) = $y) and (MONTH(FROM_UNIXTIME(a.im_dpt3)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.im_dpt3)) = $y) and (MONTH(FROM_UNIXTIME(a.im_polio1)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.im_polio1)) = $y) and (MONTH(FROM_UNIXTIME(a.im_polio2)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.im_polio2)) = $y) and (MONTH(FROM_UNIXTIME(a.im_polio3)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.im_polio3)) = $y) and (MONTH(FROM_UNIXTIME(a.im_hepa_b1_with_in)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.im_hepa_b1_with_in)) = $y) and (MONTH(FROM_UNIXTIME(a.im_hepa_b1_more_than)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.im_hepa_b1_more_than)) = $y) and (MONTH(FROM_UNIXTIME(a.im_hepa_b2)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.im_hepa_b2)) = $y)and (MONTH(FROM_UNIXTIME(a.im_hepa_b3)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.im_hepa_b3)) = $y) and (MONTH(FROM_UNIXTIME(a.im_anti_measles)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.im_anti_measles)) = $y) and (MONTH(FROM_UNIXTIME(a.im_bcg)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.im_bcg)) = $y)) as im_complete_under1F_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'male' and (MONTH(FROM_UNIXTIME(a.im_bcg)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.im_bcg)) = $y)) as im_complete_under1M_total, 
        	
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'female' and (MONTH(FROM_UNIXTIME(b.dob)) BETWEEN $between and YEAR(FROM_UNIXTIME(b.dob)) = $y)) as livebirths_F_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'male' and (MONTH(FROM_UNIXTIME(b.dob)) BETWEEN $between and YEAR(FROM_UNIXTIME(b.dob)) = $y)) as livebirths_M_total, 
        
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'female' and (MONTH(FROM_UNIXTIME(a.cp_date_assess)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.cp_date_assess)) = $y)) as child_protected_F_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'male' and (MONTH(FROM_UNIXTIME(a.cp_date_assess)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.cp_date_assess)) = $y)) as child_protected_M_total, 
            
            ( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'female' and (MONTH(FROM_UNIXTIME(a.nb_referral_date)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.nb_referral_date)) = $y) and TIMESTAMPDIFF(MONTH, FROM_UNIXTIME(a.nb_referral_date), FROM_UNIXTIME(b.dob)) >= 6) as infant_6mos_seen_F_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'male' and (MONTH(FROM_UNIXTIME(a.nb_referral_date)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.nb_referral_date)) = $y) and TIMESTAMPDIFF(MONTH, FROM_UNIXTIME(a.nb_referral_date), FROM_UNIXTIME(b.dob)) >= 6 ) as infant_6mos_seen_M_total,
            
            ( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'female' and (MONTH(FROM_UNIXTIME(a.bf_6)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.bf_6)) = $y)) as breastfeed_6th_F_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'male' and (MONTH(FROM_UNIXTIME(a.bf_6)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.bf_6)) = $y)) as breastfeed_6th_M_total,                   
            
            ( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'female' and (MONTH(FROM_UNIXTIME(a.nb_referral_date)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.nb_referral_date)) = $y) ) as  referred_nb_screeningF_total,
        	( SELECT count(*) FROM default_children_under_one a JOIN default_clients b ON b.id = a.client_id where b.gender = 'male' and (MONTH(FROM_UNIXTIME(a.nb_referral_date)) BETWEEN $between and YEAR(FROM_UNIXTIME(a.nb_referral_date)) = $y) ) as referred_nb_screeningM_total
        
        FROM dual
        ");
        $rows = $query->row_array();
        return $rows;
    }
}