<section class="title">
	<h4>RHU Q1 - Preview</h4>
</section>
<section class="preview" id="preview">
<div id="report_preview_container">
<h4 class="report-header"><?php echo $quartername;?> - <?php echo $year;?></h4>
<h4 class="report-header">Municipality/City: <?php echo ucwords($default_city);?></h4>  
<h4 class="report-header">Province: <?php echo ucwords($default_province);?></h4>
<div class="report_preview_body">
    <div class="maternal-care report-section">
        <h3 class="report-sub-header">MATERNAL CARE</h3>
        <table border="0" class="">
        	<thead>
        		<tr>
        			<th class="th-preview">Indicators</th>
        			<th class="th-preview" width="30px">Eligible Population</th>
        			<th class="th-preview" width="30px">No.</th>
                    <th class="th-preview" width="30px">% Rate</th>
        			<th class="th-preview" width="200px">Interpretation</th>
                    <th class="th-preview" width="200px">Recommendations/ Action Taken</th>
        		</tr>
        	</thead>
        	<tbody>
                <tr>
                    <td class="td-preview first-row-preview">Pregnant women with 4 or more Prenatal visits?</td>
        			<td class="td-preview"><?php echo $eligible_pop_maternal1; ?></td>
        			<td class="td-preview"><?php echo $maternal['total2']; ?></td>
                    <td class="td-preview"><?php echo $maternal_per1; ?></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr>  
                <tr>
                    <td class="td-preview first-row-preview">Pregnant women given 2 doses of Tetanus Toxoid?</td>
        			<td class="td-preview"><?php echo $eligible_pop_maternal1; ?></td>
        			<td class="td-preview"><?php echo $maternal['total2']; ?></td>
                    <td class="td-preview"><?php echo $maternal_per2; ?></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr>   
                <tr>
                    <td class="td-preview first-row-preview">Pregnant women given TT2 plus?</td>
        			<td class="td-preview"><?php echo $eligible_pop_maternal1; ?></td>
        			<td class="td-preview"><?php echo $maternal['total3']; ?></td>
                    <td class="td-preview"><?php echo $maternal_per3; ?></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr>   
                <tr>
                    <td class="td-preview first-row-preview">Pregnant women given complete Iron w/ Folic Acid?</td>
        			<td class="td-preview"><?php echo $eligible_pop_maternal1; ?></td>
        			<td class="td-preview"><?php echo $maternal['total4']; ?></td>
                    <td class="td-preview"><?php echo $maternal_per4; ?></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr>  
                <tr>
                    <td class="td-preview first-row-preview">Pregnant women given Vitamin A</td>
        			<td class="td-preview"><?php echo $eligible_pop_maternal1; ?></td>
        			<td class="td-preview"><?php echo $maternal['total5']; ?></td>
                    <td class="td-preview"><?php echo $maternal_per5; ?></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr>  
                <tr>
                    <td class="td-preview first-row-preview">Postpartum women with at least 2 Postpartum visits</td>
        			<td class="td-preview"><?php echo $eligible_pop_maternal2; ?></td>
        			<td class="td-preview"><?php echo $maternal['total6']; ?></td>
                    <td class="td-preview"><?php echo $maternal_per6; ?></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr>  
                <tr>
                    <td class="td-preview first-row-preview">Postpartum women given complete Iron</td>
        			<td class="td-preview"><?php echo $eligible_pop_maternal2; ?></td>
        			<td class="td-preview"><?php echo $maternal['total7']; ?></td>
                    <td class="td-preview"><?php echo $maternal_per7; ?></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr>  
                <tr>
                    <td class="td-preview first-row-preview">Postpartum women given Vitamin A</td>
        			<td class="td-preview"><?php echo $eligible_pop_maternal2; ?></td>
        			<td class="td-preview"><?php echo $maternal['total8']; ?></td>
                    <td class="td-preview"><?php echo $maternal_per8; ?></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr>  
                <tr>
                    <td class="td-preview first-row-preview">Postpartum women initiated breastfeeding</td>
        			<td class="td-preview"><?php echo $eligible_pop_maternal2; ?></td>
        			<td class="td-preview"><?php echo $maternal['total9']; ?></td>
                    <td class="td-preview"><?php echo $maternal_per9; ?></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr>  
        	</tbody>
        </table>			
    </div>
    <div class="family-planning report-section">
        <h3 class="report-sub-header">FAMILY PLANNING</h3>
        <table border="0" class="">
        	<thead>
        		<tr>
        			<th class="th-preview">Indicators</th>
        			<th class="th-preview" width="30px">Current Users (Begin Qtr.)</th>
        			<th class="th-preview" width="30px">New Acceptors</th>
                    <th class="th-preview" width="30px">Others</th>
                    <th class="th-preview" width="30px">Drop-out</th>
                    <th class="th-preview" width="30px">Current Users (End Qtr.)</th>
                    <th class="th-preview" width="50px">CPR Col.6/TP x 14.5%x85%</th>
        			<th class="th-preview" width="150px">Interpretation</th>
                    <th class="th-preview" width="150px">Recommendations/ Action Taken</th>
        		</tr>
        	</thead>
        	<tbody>
                <tr>
                    <td class="td-preview first-row-preview">a. Female Ster/BTL</td>
        			<td class="td-preview"><?php echo $family_planning['fstr_cu_begin']; ?></td>
        			<td class="td-preview"><?php echo $family_planning['fstr_na']; ?></td>
                    <td class="td-preview"><?php echo $family_planning['fstr_others']; ?></td>
                    <td class="td-preview"><?php echo $family_planning['fstr_dropout']; ?></td>
                    <td class="td-preview"><?php echo $fstr_cu_end; ?></td>
        			<td class="td-preview"><?php echo $fstr_cpr; ?></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr>  
                <tr>
                    <td class="td-preview first-row-preview">b. Male Ster/Vasectomy</td>
        			<td class="td-preview"><?php echo $family_planning['mstr_cu_begin']; ?></td>
        			<td class="td-preview"><?php echo $family_planning['mstr_na']; ?></td>
                    <td class="td-preview"><?php echo $family_planning['mstr_others']; ?></td>
        			<td class="td-preview"><?php echo $family_planning['mstr_dropout']; ?></td>
                    <td class="td-preview"><?php echo $mstr_cu_end; ?></td>
                    <td class="td-preview"><?php echo $mstr_cpr ?></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr>   
                <tr>
                    <td class="td-preview first-row-preview">c. Pills</td>
        			<td class="td-preview"><?php echo $family_planning['pills_cu_begin']; ?></td>
        			<td class="td-preview"><?php echo $family_planning['pills_na']; ?></td>
                    <td class="td-preview"><?php echo $family_planning['pills_others']; ?></td>
        			<td class="td-preview"><?php echo $family_planning['pills_dropout']; ?></td>
                    <td class="td-preview"><?php echo $pills_cu_end; ?></td>
                    <td class="td-preview"><?php echo $pills_cpr; ?></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr>    
                <tr>
                    <td class="td-preview first-row-preview">d. IUD</td>
        			<td class="td-preview"><?php echo $family_planning['iud_cu_begin']; ?></td>
        			<td class="td-preview"><?php echo $family_planning['iud_na']; ?></td>
                    <td class="td-preview"><?php echo $family_planning['iud_others']; ?></td>
        			<td class="td-preview"><?php echo $family_planning['iud_dropout']; ?></td>
                    <td class="td-preview"><?php echo $iud_cu_end; ?></td>
                    <td class="td-preview"><?php echo $iud_cpr; ?></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr>   
                <tr>
                    <td class="td-preview first-row-preview">e. Injectables(DMPA)</td>
        			<td class="td-preview"><?php echo $family_planning['dmpa_cu_begin']; ?></td>
        			<td class="td-preview"><?php echo $family_planning['dmpa_na']; ?></td>
                    <td class="td-preview"><?php echo $family_planning['dmpa_others']; ?></td>
        			<td class="td-preview"><?php echo $family_planning['dmpa_dropout']; ?></td>
                    <td class="td-preview"><?php echo $dmpa_cu_end; ?></td>
                    <td class="td-preview"><?php echo $dmpa_cpr; ?></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr>  
                <tr>
                    <td class="td-preview first-row-preview">f. NFP-CM</td>
        			<td class="td-preview"><?php echo $family_planning['nfpcm_cu_begin']; ?></td>
        			<td class="td-preview"><?php echo $family_planning['nfpcm_na']; ?></td>
                    <td class="td-preview"><?php echo $family_planning['nfpcm_others']; ?></td>
        			<td class="td-preview"><?php echo $family_planning['nfpcm_dropout']; ?></td>
                    <td class="td-preview"><?php echo $nfpcm_cu_end; ?></td>
                    <td class="td-preview"><?php echo $nfpcm_cpr; ?></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr>  
                <tr>
                    <td class="td-preview first-row-preview">g. NFP-BBT</td>
        			<td class="td-preview"><?php echo $family_planning['nfpbbt_cu_begin']; ?></td>
        			<td class="td-preview"><?php echo $family_planning['nfpbbt_na']; ?></td>
                    <td class="td-preview"><?php echo $family_planning['nfpbbt_others']; ?></td>
        			<td class="td-preview"><?php echo $family_planning['nfpbbt_dropout']; ?></td>
                    <td class="td-preview"><?php echo $nfpbbt_cu_end; ?></td>
                    <td class="td-preview"><?php echo $nfpbbt_cpr; ?></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr>  
                <tr>
                    <td class="td-preview first-row-preview">h. NFP-STM</td>
        			<td class="td-preview"><?php echo $family_planning['nfpstm_cu_begin']; ?></td>
        			<td class="td-preview"><?php echo $family_planning['nfpstm_na']; ?></td>
                    <td class="td-preview"><?php echo $family_planning['nfpstm_others']; ?></td>
        			<td class="td-preview"><?php echo $family_planning['nfpstm_dropout']; ?></td>
                    <td class="td-preview"><?php echo $nfpstm_cu_end; ?></td>
                    <td class="td-preview"><?php echo $nfpstm_cpr; ?></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr>  
                <tr>
                    <td class="td-preview first-row-preview">i. NFPD-Standard Days Method</td>
        			<td class="td-preview"><?php echo $family_planning['nfdsdm_cu_begin']; ?></td>
        			<td class="td-preview"><?php echo $family_planning['nfdsdm_na']; ?></td>
                    <td class="td-preview"><?php echo $family_planning['nfdsdm_others']; ?></td>
        			<td class="td-preview"><?php echo $family_planning['nfdsdm_dropout']; ?></td>
                    <td class="td-preview"><?php echo $nfdsdm_cu_end; ?></td>
                    <td class="td-preview"><?php echo $nfdsdm_cpr; ?></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr>  
                <tr>
                    <td class="td-preview first-row-preview">j. NFP-LAM</td>
        			<td class="td-preview"><?php echo $family_planning['nfplam_cu_begin']; ?></td>
        			<td class="td-preview"><?php echo $family_planning['nfplam_na']; ?></td>
                    <td class="td-preview"><?php echo $family_planning['nfplam_others']; ?></td>
        			<td class="td-preview"><?php echo $family_planning['nfplam_dropout']; ?></td>
                    <td class="td-preview"><?php echo $nfplam_cu_end; ?></td>
                    <td class="td-preview"><?php echo $nfplam_cpr; ?></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr>  
                <tr>
                    <td class="td-preview first-row-preview">k. Condom</td>
        			<td class="td-preview"><?php echo $family_planning['condom_cu_begin']; ?></td>
        			<td class="td-preview"><?php echo $family_planning['condom_na']; ?></td>
                    <td class="td-preview"><?php echo $family_planning['condom_others']; ?></td>
        			<td class="td-preview"><?php echo $family_planning['condom_dropout']; ?></td>
                    <td class="td-preview"><?php echo $condom_cu_end; ?></td>
                    <td class="td-preview"><?php echo $condom_cpr; ?></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr>  
        	</tbody>
        </table>			
    </div>
    <div class="dental-care report-section">
        <h3 class="report-sub-header">DENTAL CARE</h3>
        <table border="0" class="">
        	<thead>
        		<tr>
        			<th class="th-preview">Indicators</th>
        			<th class="th-preview" width="30px">Elig. Pop.</th>
        			<th class="th-preview" width="30px">Male</th>
                    <th class="th-preview" width="30px">Female</th>
                    <th class="th-preview" width="30px">Total</th>
                    <th class="th-preview" width="30px">%</th>
        			<th class="th-preview" width="150px">Interpretation</th>
                    <th class="th-preview" width="150px">Recommendations/ Action Taken</th>
        		</tr>
        	</thead>
        	<tbody>
                <tr>
                    <td class="td-preview first-row-preview">Orally Fit Childen 12-71 mos old?</td>
        			<td class="td-preview"><?php echo $eligible_pop_dental1; ?></td>           
        			<td class="td-preview"><?php echo $dental['orally_fit_1271_mo_m']; ?></td>
                    <td class="td-preview"><?php echo $dental['orally_fit_1271_mo_f']; ?></td>
                    <td class="td-preview"><?php echo $dental['orally_fit_1271_mo']; ?></td>
                    <td class="td-preview"><?php echo $orally_fit_1271_per; ?></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr>  
                <tr>
                    <td class="td-preview first-row-preview">Childen 12-71 mos old provided w/ BOHC?</td>
        			<td class="td-preview"><?php echo $eligible_pop_dental1; ?></td>
        			<td class="td-preview"><?php echo $dental['bohc_1271_mo_m']; ?></td>
                    <td class="td-preview"><?php echo $dental['bohc_1271_mo_f']; ?></td>
                    <td class="td-preview"><?php echo $dental['bohc_1271_mo']; ?></td>
                    <td class="td-preview"><?php echo $bohc_1271_mo_per; ?></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr> 
                <tr>
                    <td class="td-preview first-row-preview">Adolescent & Youth (10-24 yrs) given BOHC?</td>
        			<td class="td-preview"><?php echo $eligible_pop_dental2; ?></td>
        			<td class="td-preview"><?php echo $dental['bohc_1024_yo_m']; ?></td>
                    <td class="td-preview"><?php echo $dental['bohc_1024_yo_f']; ?></td>
                    <td class="td-preview"><?php echo $dental['bohc_1024_yo']; ?></td>
                    <td class="td-preview"><?php echo $bohc_1024_yo_per; ?></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr>    
                <tr>
                    <td class="td-preview first-row-preview">Pregnant women provided with BOHC</td>
        			<td class="td-preview"><?php echo $eligible_pop_dental3; ?></td>
        			<td class="td-preview">-</td>
                    <td class="td-preview"><?php echo $dental['bohc_pregnant']; ?></td>
                    <td class="td-preview"><?php echo $dental['bohc_pregnant']; ?></td>
                    <td class="td-preview"><?php echo $bohc_pregnant_per; ?></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr>    
                <tr>
                    <td class="td-preview first-row-preview">Older Person 60 yrs old & above provided with BOHC?</td>
        			<td class="td-preview"><?php echo $eligible_pop_dental4; ?></td>
        			<td class="td-preview"><?php echo $dental['bohc_60plus_yo_m']; ?></td>
                    <td class="td-preview"><?php echo $dental['bohc_60plus_yo_f']; ?></td>
                    <td class="td-preview"><?php echo $dental['bohc_60plus_yo']; ?></td>
                    <td class="td-preview"><?php echo $bohc_60plus_yo_per; ?></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr>   
        	</tbody>
        </table>			
    </div>
    <div class="child-care report-section">
        <h3 class="report-sub-header">CHILD CARE</h3>
        <table border="0" class="">
        	<thead>
        		<tr>
        			<th class="th-preview">Indicators</th>
        			<th class="th-preview" width="30px">Elig. Pop.</th>
        			<th class="th-preview" width="30px">Male</th>
                    <th class="th-preview" width="30px">Female</th>
                    <th class="th-preview" width="30px">Total</th>
                    <th class="th-preview" width="30px">%</th>
        			<th class="th-preview" width="150px">Interpretation</th>
                    <th class="th-preview" width="150px">Recommendations/ Action Taken</th>
        		</tr>
        	</thead>
        	<tbody>
                <tr>
                    <td class="td-preview first-row-preview">Infant given BCG?</td>
        			<td class="td-preview"><?php echo $eligible_pop_childcare1; ?></td>
        			<td class="td-preview"><?php echo $childcare['bcgM_total']; ?></td>
                    <td class="td-preview"><?php echo $childcare['bcgF_total']; ?></td>
        			<td class="td-preview"><?php echo $bcg_Total; ?></td>
                    <td class="td-preview"><?php echo $bcg_per; ?></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr>  
                <tr>
                    <td class="td-preview first-row-preview">Infant given DPT1?</td>
        			<td class="td-preview"><?php echo $eligible_pop_childcare1; ?></td>         
        			<td class="td-preview"><?php echo $childcare['dpt1M_total']; ?></td>
                    <td class="td-preview"><?php echo $childcare['dpt1F_total']; ?></td>
        			<td class="td-preview"><?php echo $dpt1_Total; ?></td>
                    <td class="td-preview"><?php echo $dpt1_per; ?></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr>   
                <tr>
                    <td class="td-preview first-row-preview">Infant given DPT2?</td>
        			<td class="td-preview"><?php echo $eligible_pop_childcare1; ?></td>
        			<td class="td-preview"><?php echo $childcare['dpt2M_total']; ?></td>
                    <td class="td-preview"><?php echo $childcare['dpt2F_total']; ?></td>
        			<td class="td-preview"><?php echo $dpt2_Total; ?></td>
                    <td class="td-preview"><?php echo $dpt2_per; ?></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr>    
                <tr>
                    <td class="td-preview first-row-preview">Infant given DPT3?</td>
        			<td class="td-preview"><?php echo $eligible_pop_childcare1; ?></td>
        			<td class="td-preview"><?php echo $childcare['dpt3M_total']; ?></td>
                    <td class="td-preview"><?php echo $childcare['dpt3F_total']; ?></td>
        			<td class="td-preview"><?php echo $dpt3_Total; ?></td>
                    <td class="td-preview"><?php echo $dpt3_per; ?></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr>   
                <tr>
                    <td class="td-preview first-row-preview">Infant given OPV1?</td>
        			<td class="td-preview"><?php echo $eligible_pop_childcare1; ?></td>
        			<td class="td-preview"><?php echo $childcare['opv1M_total']; ?></td>
                    <td class="td-preview"><?php echo $childcare['opv1F_total']; ?></td>
        			<td class="td-preview"><?php echo $opv1_Total; ?></td>
                    <td class="td-preview"><?php echo $opv1_per; ?></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr>  
                <tr>
                    <td class="td-preview first-row-preview">Infant given OPV2?</td>
        			<td class="td-preview"><?php echo $eligible_pop_childcare1; ?></td>
        			<td class="td-preview"><?php echo $childcare['opv2M_total']; ?></td>
                    <td class="td-preview"><?php echo $childcare['opv2F_total']; ?></td>
        			<td class="td-preview"><?php echo $opv2_Total; ?></td>
                    <td class="td-preview"><?php echo $opv2_per; ?></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr>  
                <tr>
                    <td class="td-preview first-row-preview">Infant given OPV3?</td>
        			<td class="td-preview"><?php echo $eligible_pop_childcare1; ?></td>
        			<td class="td-preview"><?php echo $childcare['opv3M_total']; ?></td>
                    <td class="td-preview"><?php echo $childcare['opv3F_total']; ?></td>
        			<td class="td-preview"><?php echo $opv3_Total; ?></td>
                    <td class="td-preview"><?php echo $opv3_per; ?></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr>  
                <tr>
                    <td class="td-preview first-row-preview">Infant given Hepa B1 w/in 24 hrs. after birth?</td>
        			<td class="td-preview"><?php echo $eligible_pop_childcare1; ?></td>
        			<td class="td-preview"><?php echo $childcare['hepa_b1_with_inM_total']; ?></td>
                    <td class="td-preview"><?php echo $childcare['hepa_b1_with_inF_total']; ?></td>
        			<td class="td-preview"><?php echo $hepa_b1_with_in_Total; ?></td>
                    <td class="td-preview"><?php echo $hepa_b1_with_in_per; ?></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr>  
                <tr>
                    <td class="td-preview first-row-preview">Infant given Hepa B1 more than 24 hrs. after birth?</td>
        			<td class="td-preview"><?php echo $eligible_pop_childcare1; ?></td>
        			<td class="td-preview"><?php echo $childcare['hepa_b1_more_thanM_total']; ?></td>
                    <td class="td-preview"><?php echo $childcare['hepa_b1_more_thanF_total']; ?></td>
        			<td class="td-preview"><?php echo $hepa_b1_more_than_Total; ?></td>
                    <td class="td-preview"><?php echo $hepa_b1_more_than_per; ?></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr>  
                <tr>
                    <td class="td-preview first-row-preview">Infant given Hepatitis B2?</td>
        			<td class="td-preview"><?php echo $eligible_pop_childcare1; ?></td>
        			<td class="td-preview"><?php echo $childcare['hepa_b2M_total']; ?></td>
                    <td class="td-preview"><?php echo $childcare['hepa_b2F_total']; ?></td>
                    <td class="td-preview"><?php echo $hepa_b2_Total; ?></td>
        			<td class="td-preview"><?php echo $hepa_b2_per; ?></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr>  
                <tr>
                    <td class="td-preview first-row-preview">Infant given Hepatitis B3?</td>
        			<td class="td-preview"><?php echo $eligible_pop_childcare1; ?></td>
        			<td class="td-preview"><?php echo $childcare['hepa_b3M_total']; ?></td>
                    <td class="td-preview"><?php echo $childcare['hepa_b3F_total']; ?></td>
                    <td class="td-preview"><?php echo $hepa_b3_Total; ?></td>
        			<td class="td-preview"><?php echo $hepa_b3_per; ?></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr>  
                <tr>
                    <td class="td-preview first-row-preview">Infant given anti-Measles?</td>
        			<td class="td-preview"><?php echo $eligible_pop_childcare1; ?></td>
        			<td class="td-preview"><?php echo $childcare['im_anti_measlesM_total']; ?></td>
                    <td class="td-preview"><?php echo $childcare['im_anti_measlesF_total']; ?></td>
                    <td class="td-preview"><?php echo $im_anti_measles_Total; ?></td>
        			<td class="td-preview"><?php echo $im_anti_measles_per; ?></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr>  
                <tr>
                    <td class="td-preview first-row-preview">Fully Immunized Child(0-11 mos)</td>
        			<td class="td-preview"><?php echo $eligible_pop_childcare1; ?></td>
        			<td class="td-preview"><?php echo $childcare['im_fully_under1M_total']; ?></td>
                    <td class="td-preview"><?php echo $childcare['im_fully_under1F_total']; ?></td>
                    <td class="td-preview"><?php echo $im_fully_under_Total; ?></td>
        			<td class="td-preview"><?php echo $im_fully_under_per; ?></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr>  
                <tr>
                    <td class="td-preview first-row-preview">Completely Immunized Child(12-23 mos)</td>
        			<td class="td-preview">-</td>
        			<td class="td-preview">-</td>
                    <td class="td-preview">-</td>
                    <td class="td-preview">-</td>
        			<td class="td-preview">-</td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr> 
                <tr>
                    <td class="td-preview first-row-preview">Total Livebirths</td>
        			<td class="td-preview">-</td>
        			<td class="td-preview"><?php echo $childcare['livebirths_M_total']; ?></td>
                    <td class="td-preview"><?php echo $childcare['livebirths_F_total']; ?></td>
                    <td class="td-preview"><?php echo $livebirths_Total; ?></td>                       livebirths_Total
        			<td class="td-preview"></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr> 
                <tr>
                    <td class="td-preview first-row-preview">Child Protected at Birth (CPAB)?</td>
        			<td class="td-preview">-</td>
        			<td class="td-preview"><?php echo $childcare['child_protected_M_total']; ?></td>
                    <td class="td-preview"><?php echo $childcare['child_protected_F_total']; ?></td>
                    <td class="td-preview"><?php echo $child_protected_Total; ?></td>
        			<td class="td-preview">-</td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr> 
                <tr>
                    <td class="td-preview first-row-preview">Infant age 6 mos. seen</td>
        			<td class="td-preview">-</td>
        			<td class="td-preview"><?php echo $childcare['child_protected_M_total']; ?></td>
                    <td class="td-preview"><?php echo $childcare['child_protected_F_total']; ?></td>
                    <td class="td-preview"><?php echo $infant_6mos_seen_Total; ?></td>
        			<td class="td-preview">-</td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr> 
                <tr>
                    <td class="td-preview first-row-preview">Infant exclusively breastfeed until 6th mos?</td>
        			<td class="td-preview">-</td>
        			<td class="td-preview"><?php echo $childcare['breastfeed_6th_M_total']; ?></td>
                    <td class="td-preview"><?php echo $childcare['breastfeed_6th_F_total']; ?></td>
                    <td class="td-preview"><?php echo $breastfeed_6th_Total; ?></td>
        			<td class="td-preview">-</td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr> 
                <tr>
                    <td class="td-preview first-row-preview">Infant 0-11 mos. referred for newborn screening</td>
        			<td class="td-preview">-</td>
        			<td class="td-preview"><?php echo $childcare['referred_nb_screeningM_total']; ?></td>
                    <td class="td-preview"><?php echo $childcare['referred_nb_screeningF_total']; ?></td>
                    <td class="td-preview"><?php echo $referred_nb_screening_Total; ?></td>
        			<td class="td-preview">-</td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr> 
        	</tbody>
        </table>			
    </div>
    <div class="dental-care report-section">
        <h3 class="report-sub-header">DISEASES CONTROL</h3>
        <table border="0" class="">
        	<thead>
        		<tr>
        			<th class="th-preview">Malaria (endemic areas)</th>
        			<th class="th-preview" width="30px">Male</th>
        			<th class="th-preview" width="30px">Female</th>
                    <th class="th-preview" width="30px">Pregnant</th>
                    <th class="th-preview" width="30px">Total</th>
                    <th class="th-preview" width="30px">Morbidity Rate</th>
                    <th class="th-preview" width="30px">Annual Parasite Incidents Rate</th>
        			<th class="th-preview" width="150px">Interpretation</th>
                    <th class="th-preview" width="150px">Recommendations/ Action Taken</th>
        		</tr>
        	</thead>
        	<tbody>
                <tr>
                    <td class="td-preview first-row-preview">Malaria Case?</td>
        			<td class="td-preview"></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
                    <td class="td-preview"></td>
                    <td class="td-preview"></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr>  
                <tr>
                    <td class="td-preview">&nbsp;&nbsp;? 5 yo ?</td>
        			<td class="td-preview"></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
                    <td class="td-preview"></td>
                    <td class="td-preview"></td>
                    <td class="td-preview"></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr>  
                <tr>
                    <td class="td-preview">&nbsp;&nbsp;? >=5 yo ?</td>
        			<td class="td-preview"></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
                    <td class="td-preview"></td>
                    <td class="td-preview"></td>
                    <td class="td-preview"></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr>   
                <tr>
                    <td class="td-preview first-row-preview">Confirmed malaria Case?</td>
        			<td class="td-preview"></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
                    <td class="td-preview"></td>
                    <td class="td-preview"></td>
                    <td class="td-preview"></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr>    
                <tr>
                    <td class="td-preview first-row-preview">By Species</td>
        			<td class="td-preview"></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
                    <td class="td-preview"></td>
                    <td class="td-preview"></td>
                    <td class="td-preview"></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr>   
                <tr>
                    <td class="td-preview">&nbsp;&nbsp;? P.falciparum ?</td>
        			<td class="td-preview"></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
                    <td class="td-preview"></td>
                    <td class="td-preview"></td>
                    <td class="td-preview"></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr>  
                <tr>
                    <td class="td-preview">&nbsp;&nbsp;? P.vivax ?</td>
        			<td class="td-preview"></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
                    <td class="td-preview"></td>
                    <td class="td-preview"></td>
                    <td class="td-preview"></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr>  
                <tr>
                    <td class="td-preview">&nbsp;&nbsp;? P.ovale ?</td>
        			<td class="td-preview"></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
                    <td class="td-preview"></td>
                    <td class="td-preview"></td>
                    <td class="td-preview"></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr>  
                <tr>
                    <td class="td-preview first-row-preview">By Method</td>
        			<td class="td-preview"></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
                    <td class="td-preview"></td>
                    <td class="td-preview"></td>
                    <td class="td-preview"></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr>  
                <tr>
                    <td class="td-preview">&nbsp;&nbsp;? Slide ?</td>
        			<td class="td-preview"></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
                    <td class="td-preview"></td>
                    <td class="td-preview"></td>
                    <td class="td-preview"></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr>  
                <tr>
                    <td class="td-preview">&nbsp;&nbsp;? RDT ?</td>
        			<td class="td-preview"></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
                    <td class="td-preview"></td>
                    <td class="td-preview"></td>
                    <td class="td-preview"></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr>  
                <tr>
                    <td class="td-preview first-row-preview">Infant given anti-Measles?</td>
        			<td class="td-preview"></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
                    <td class="td-preview"></td>
                    <td class="td-preview"></td>
                    <td class="td-preview"></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr>  
                <tr>
                    <td class="td-preview first-row-preview">Households at risk?</td>
        			<td class="td-preview"></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
                    <td class="td-preview"></td>
                    <td class="td-preview"></td>
                    <td class="td-preview"></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr>  
                <tr>
                    <td class="td-preview first-row-preview">Households given ITN?</td>
        			<td class="td-preview"></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
                    <td class="td-preview"></td>
                    <td class="td-preview"></td>
                    <td class="td-preview"></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr>  
                <tr>
                    <td class="td-preview first-row-preview"></td>
        			<td class="td-preview"></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
                    <td class="td-preview"></td>
                    <td class="td-preview">Mortality Rate</td>
                    <td class="td-preview">Case Fatality Rate</td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr>  
                <tr>
                    <td class="td-preview first-row-preview">Malaria Death</td>
        			<td class="td-preview"></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
                    <td class="td-preview"></td>
                    <td class="td-preview"></td>
                    <td class="td-preview"></td>
        			<td class="td-preview"></td>
                    <td class="td-preview"></td>
        		</tr>  
        	</tbody>
        </table>			
    </div>
</div>
</div>
</section>
<section class="item">
		<table border="0" class="table-list">
			<thead>
				<tr>
					<th width="200px"></th>
					<th width="500px">Interpretation</th>
					<th width="500px">Recommendations/Action Taken</th>
				</tr>
			</thead>
			<tbody>
      <?php $attributes = array('class' => 'crud', 'id' => 'frm_view', 'target' => '_blank'); ?>      
      <?php echo form_open(base_url().'admin/rhu_q1/show', $attributes); ?>
                <tr>  
                    <?php echo form_hidden('q1_quarter', $quarter);?>
                    <?php echo form_hidden('q1_year', $year);?>
                    <td><strong>MATERNAL CARE</strong></td>
					<td class="text-area-interpretation"><textarea name="maternal_int" id="maternal_int" rows="4" cols="50"></textarea></td>
					<td class="text-area-recommendation"><textarea name="maternal_rec" id="maternal_rec" rows="4" cols="50"></textarea></td>
				</tr>
                <tr>  
                    <td><strong>FAMILY PLANNING</strong></td>
					<td class="text-area-interpretation"><textarea name="family_int" id="family_int" rows="4" cols="50"></textarea></td>
					<td class="text-area-recommendation"><textarea name="family_rec" id="family_rec" rows="4" cols="50"></textarea></td>
				</tr> 
                <tr>  
                    <td><strong>DENTAL CARE</strong></td>
					<td class="text-area-interpretation"><textarea name="dental_int" id="dental_int" rows="4" cols="50"></textarea></td>
					<td class="text-area-recommendation"><textarea name="dental_rec" id="dental_rec" rows="4" cols="50"></textarea></td>
				</tr>
                <tr>  
                    <td><strong>CHILD CARE</strong></td>
					<td class="text-area-interpretation"><textarea name="childcare_int" id="childcare_int" rows="4" cols="50"></textarea></td>
					<td class="text-area-recommendation"><textarea name="childcare_rec" id="childcare_rec" rows="4" cols="50"></textarea></td>
				</tr>
                <tr>  
                    <td><strong>DISEASES CONTROL</strong></td>
					<td class="text-area-interpretation"><textarea name="diseases_int" id="diseases_int" rows="4" cols="50"></textarea></td>
					<td class="text-area-recommendation"><textarea name="diseases_rec" id="diseases_rec" rows="4" cols="50"></textarea></td>
				</tr>
                <tr>  
                    <td></td>
					<td>
                        <?php $data = array('name'=>'q1_ppoty','id'=>'q1_ppoty','value'=>$ppoty); ?>
                        <?php echo form_input($data); ?>
                    </td>
                    <td>
                        <button id="rpt_show" type="submit" name="btnAction" value="generate_rhu_q1" class="btn blue">Show</button>
                        <button id="rpt_download" type="submit" name="btnAction" value="generate_rhu_q1_dl" class="btn blue">Download</button>
                    </td>
				</tr            
      <?php echo form_close(); ?>
			</tbody>
		</table>
</section>
