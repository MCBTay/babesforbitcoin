
				<table class="table">
					<thead>
						<tr>
							<th colspan="2">Commission Rates</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td width="75%">Direct Contribution:</td>
							<td width="25%"><?php echo (1 - FEE_PURCHASE) * 100; ?>%</td>
						</tr>
						<tr>
							<td width="75%">Photosets/Videos:</td>
							<td width="25%"><?php echo (1 - FEE_PURCHASE) * 100; ?>%</td>
						</tr>
						<tr>
							<td width="75%">"HOT" Sets/Videos:</td>
							<td width="25%"><?php echo (1 - FEE_PURCHASE) * 100; ?>%</td>
						</tr>
						<tr>
							<td width="75%">Webcam Rates:</td>
							<td width="25%"><?php echo (1 - FEE_PURCHASE) * 100; ?>%</td>
						</tr>
						<tr>
							<td width="75%">Messages:</td>
							<td width="25%"><?php echo (1 - FEE_PURCHASE) * 100; ?>%</td>
						</tr>
					</tbody>
				</table>
				<table class="table">
					<thead>
						<tr>
							<th colspan="2">Fans</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td width="75%">Photos:</td>
							<td width="25%"><?php echo $contributors_photos; ?></td>
						</tr>
						<tr>
							<td width="75%">Online:</td>
							<td width="25%"><?php echo $contributors_online; ?></td>
						</tr>
						<tr>
							<td width="75%">Total:</td>
							<td width="25%"><?php echo $contributors_total; ?></td>
						</tr>
					</tbody>
				</table>
				<table class="table">
					<thead>
						<tr>
							<th colspan="2">Models</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td width="75%">Photos:</td>
							<td width="25%"><?php echo $models_photos; ?></td>
						</tr>
						<tr>
							<td width="75%">Videos:</td>
							<td width="25%"><?php echo $models_videos; ?></td>
						</tr>
						<tr>
							<td width="75%">Online:</td>
							<td width="25%"><?php echo $models_online; ?></td>
						</tr>
						<tr>
							<td width="75%">Total:</td>
							<td width="25%"><?php echo $models_total; ?></td>
						</tr>
					</tbody>
				</table>
				<table class="table">
					<thead>
						<tr>
							<th colspan="2">Messages</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td width="75%">Unread:</td>
							<td width="25%"><?php echo $messages_unread; ?></td>
						</tr>
						<tr>
							<td width="75%">Total:</td>
							<td width="25%"><?php echo $messages; ?></td>
						</tr>
					</tbody>
				</table>
