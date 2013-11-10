<b>Target Client List</b>
<br />
<?php if (!empty($clients)): ?>
			<table border="1">
					<tr><td bgcolor="#D0D0FF" width="40">#</td><td bgcolor="#D0D0FF" width="80">Family S.N.</td><td width="150" bgcolor="#D0D0FF" align="left">Full Name</td><td width="30" bgcolor="#D0D0FF">Age</td><td bgcolor="#D0D0FF" width="30">Sex</td>
						<td bgcolor="#D0D0FF" width="110">Registration Date</td><td bgcolor="#D0D0FF" width="110">Date of Birth</td><td bgcolor="#D0D0FF" width="100">Philhealth</td><td bgcolor="#D0D0FF" width="90">Philhealth Type</td>
					</tr><?php $i=1;?><?php foreach ($clients as $client): ?><tr><td width="40"><?php echo $i++; ?></td><td width="80"><?php echo $client->serial_number; ?></td>	
							<td width="150" align="left"><?php echo trim($client->last_name); ?>, <?php echo trim($client->first_name); ?> <?php echo substr(trim($client->middle_name),0,1); ?>.</td>
							<td width="30"><?php $age = floor((time() - $client->dob)/31556926); ?>
						  <?php echo $age ? $age : '-'; ?></td><td width="30"><?php echo substr(ucfirst($client->gender),0,1); ?></td>
							<td width="110"><?php echo date('F j, Y', $client->registration_date); ?></td><td width="110"><?php echo date('F j, Y', $client->dob); ?></td><td width="100"><?php echo $client->philhealth ? $client->philhealth : '-'; ?></td><td width="90"><?php echo $client->philhealth_type ? $client->philhealth_type : '-'; ?></td>
							</tr>
					<?php endforeach; ?>
			</table>
	<?php endif; ?>