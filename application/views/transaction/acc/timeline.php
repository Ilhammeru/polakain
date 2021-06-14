
	<div class="row">

		<div class="col-md-12">

			<div class="timeline">

				<?php

				$detail = json_decode($data['detail'], TRUE);

				foreach ($detail as $key => $row) :
				?>
					<div class="time-label">
						<span class="bg-red"><?php echo $key;?></span>
					</div>

					<?php
					foreach($row as $list) :

					endforeach;

				endforeach;

				?>

				<!-- timeline time label -->
				<div class="time-label">
					<span class="bg-red">10 Feb. 2014</span>
				</div>
				<!-- /.timeline-label -->

				<!-- timeline item -->
				<div>
					<i class="fas fa-envelope bg-blue"></i>
					<div class="timeline-item">
					<span class="time"><i class="fas fa-clock"></i> 12:05</span>
					<h3 class="timeline-header"><a href="#">Support Team</a> sent you an email</h3>

					<div class="timeline-body">
						Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles,
						weebly ning heekya handango imeem plugg dopplr jibjab, movity
						jajah plickers sifteo edmodo ifttt zimbra. Babblely odeo kaboodle
						quora plaxo ideeli hulu weebly balihoo...
					</div>
					<div class="timeline-footer">
						<a class="btn btn-primary btn-sm">Read more</a>
						<a class="btn btn-danger btn-sm">Delete</a>
					</div>
					</div>
				</div>
				<!-- END timeline item -->

				<!-- END timeline item -->
				<div>
					<i class="fas fa-clock bg-gray"></i>
				</div>

			</div>
			<!-- /.timeline -->

		</div>
		<!-- /div.col -->

	</div>
	<!-- /div.row -->