<section class="title">
	<h4>RHU Data Integration</h4>
</section>

<section class="item">

<?php if($this->current_user->group == 'staff'):?>
<?php echo anchor('admin/maintenance/download', 'Download Now!', array('class'=>'btn green')).' '; ?>	
<?php echo anchor('admin/maintenance/upload', 'Upload Now!', array('class'=>'btn red disabled')).' '; ?>	  
<?php elseif($this->current_user->group == 'rhu'):?>
<form>
<input type="file" name="uploaddb" />
<input type="submit" value="Upload BHS Database" name="uploaddbbtn"/>
</form>
<?php endif;?>
 
</section>


