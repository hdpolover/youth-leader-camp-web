<div class="d-grid gap-3 gap-lg-5">
	<!-- Card -->
	<div class="card">
		<div class="card-header border-bottom">
			<h4 class="card-header-title">Payment</h4>
		</div>
	</div>
	<!-- End Card -->

	<div class="row">
		<?php if(!empty($payment_batch)):?>
		<?php foreach($payment_batch as $key => $val):?>
		<div class="col col-sm-6 mb-4">
			<!-- Card -->
			<div class="card card-sm" style="max-width: 20rem;">
				<div class="card-header border-bottom">
					<h3 class="card-title" style="margin-bottom: 0px !important;"><?= $val->summit?></h3>
					<small>West Indonesian Time (GMT+7)</small>
					<br>
					<?php if(!is_null($val->payments)):?>
					<?php if($val->payments->status == 1):?>
					<span class="badge bg-secondary">pending</span>
					<?php elseif($val->payments->status == 2):?>
					<span class="badge bg-success">success</span>
					<?php elseif($val->payments->status == 3):?>
					<span class="badge bg-danger">canceled</span>
					<?php elseif($val->payments->status == 4):?>
					<span class="badge bg-warning">-</span>
					<?php else:?>
					<span class="badge bg-warning">-</span>
					<?php endif;?>
					<?php endif;?>
				</div>
				<div class="card-body">
					<div class="mb-4">
						<span class="card-subtitle">Open:</span>
						<h5><?= date("F d, Y H:i", $val->start_date)?></h5>
					</div>
					<div class="mb-4">
						<span class="card-subtitle">Close:</span>
						<h5><?= date("F d, Y 23:59", $val->end_date)?></h5>
					</div>
					<div>
						<span class="card-subtitle">Total (IDR)</span>
						<h3 class="text-primary">Rp<?= number_format($val->amount)?></h3>
						<b>OR</b>
						<span class="card-subtitle mt-1">Total (USD)</span>
						<h3 class="text-primary">$<?= $val->amount_usd?></h3>
					</div>
					<?php if(is_null($val->payments)):?>
					<button type="button" class="btn btn-outline-secondary btn-sm purchase-button w-100 mt-2"
						disabled>Pay (locked)</button>
					<button type="button" class="btn btn-warning btn-sm purchase-button w-100 mt-2"
						data-bs-toggle="modal" data-bs-target="#manual-transfer-<?= $val->id;?>">Manual
						Transfer</button>
					<?php else:?>
					<a href="<?= site_url('user/payments-transaction/'.$val->payments->id);?>"
						class="btn btn-warning btn-sm purchase-button w-100 mt-2">View Transaction</a>
					<?php endif;?>
					<a href="<?= site_url('user/payments-history/'.$val->id);?>"
						class="btn btn-info btn-sm purchase-button w-100 mt-2">History</a>
				</div>
			</div>
			<!-- End Card -->
		</div>
		<!-- Modal -->
		<div class="modal fade" id="manual-transfer-<?= $val->id;?>" tabindex="-1"
			aria-labelledby="manual-transfer-title-<?= $val->id;?>Label" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="manual-transfer-title-<?= $val->id;?>Label">Manual Transfer -
							<?= $val->summit;?></h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>

					<div class="modal-body">
						<form action="<?= site_url('api/payments/manualPayment')?>" method="POST"
							enctype="multipart/form-data" class="js-validate">
							<input type="hidden" name="payment_batch" value="<?= $val->id;?>">
							<input type="hidden" name="amount" value="<?= $val->amount;?>">
							<input type="hidden" name="amount_usd" value="<?= $val->amount_usd;?>">
							<div class="form-group">
								<label class="mb-2 form-label" for="">Method Payment</label>
								<div class="row gx-3 text-center" role="tablist">
									<?php if(!empty($payment_settings)):?>
									<?php $no = 1; foreach($payment_settings as $k => $v):?>
									<input type="hidden" name="code_method" value="<?= $v->code_method;?>">
									<div class="col-3 col-md-3 mb-3">
										<!-- Radio Check -->
										<div class="form-check form-check-card text-center"
											onclick="showGuide(<?= $v->id;?>)">
											<input class="form-check-input" type="radio" name="payment_setting"
												value="<?= $v->id;?>" id="method_payment-<?= $v->id;?>"
												<?= $no++ == 1 ? 'checked' : '';?>>
											<label class="form-check-label" for="method_payment-<?= $v->id;?>">
												<div class="h-100 payments-height-user d-flex align-items-center">
													<img class="w-100 h-auto mb-3"
														src="<?= base_url()?><?= $v->img_method;?>" alt="SVG">
												</div>
												<span class="d-block"><?= $v->payment_method;?></span>
											</label>
										</div>
										<!-- End Radio Check -->
									</div>
									<!-- End Col -->
									<?php endforeach;?>
									<?php endif;?>
								</div>
								<!-- End Row -->
							</div>

							<?php if(!empty($payment_settings)):?>
							<?php $no = 1; foreach($payment_settings as $k => $v):?>
							<div class="mb-3">
								<div id="data-<?= $v->id;?>" class="data <?= $no == 1 ? '' : 'd-none';?>">
									<label class="form-label" for="">Payment Data -
										<?= $v->payment_method;?></label>
									<?php if($v->data == "" || $v->data == null):?>
									<p>There is not yet payment data for this method</p>
									<?php else:?>
									<ul class="list-pointer list-pointer-sm list-pointer-primary">
										<?php foreach($v->data as $kk => $vv):?>
										<li class="list-pointer-item"><b><?= ucwords(str_replace("_", " ", $kk));?></b>:
											<?= $vv;?></li>
										<?php endforeach;?>
									</ul>
									<?php endif;?>
								</div>
							</div>
							<div class="mb-3">
								<div id="guide-<?= $v->id;?>" class="guide <?= $no == 1 ? '' : 'd-none';?>">
									<label class="form-label" for="">Payment Guide -
										<?= $v->payment_method;?></label>
									<div class="alert alert-soft-info small pb-0">
										<?= $v->tutorial == "" || $v->tutorial == null ? '<p>There is not yet payment guide for this method</p>' : $v->tutorial;?>
									</div>
								</div>
							</div>
							<?php $no++; endforeach;?>
							<?php endif;?>

							<div class="mb-3">
								<label for="poster-announcements" class="form-label">Payment Proof</label>
								<figure class="text-center">
									<img src="#" id="imgthumbnail" class="img-thumbnail img-fluid mb-2"
										alt="Thumbnail image"
										onerror="this.onerror=null;this.src='<?= base_url();?>assets/images/placeholder.jpg';">
								</figure>
								<div class="input-group">
									<input type="file" class="form-control form-control-sm imgprev" name="image"
										accept="image/*" id="poster-announcements">
								</div>
								<small class="text-muted">Max file size 1Mb</small>
							</div>

							<div class="form-group mt-2">
								<label for="">Remarks</label>
								<input type="text" name="remarks" class="form-control form-control-sm"
									placeholder="Name of Participant" required>
							</div>
					</div>

					<div class="modal-footer">
						<input type="hidden" name="id_payment_type" id="mdlManual_id">
						<button type="button" class="btn btn-outline-secondary btn-sm"
							data-bs-dismiss="modal">Cancel</button>
						<button type="submit" class="btn btn-soft-success btn-sm">Make Payment</button>
						</form>
					</div>
				</div>
			</div>
		</div>
		<!-- End Modal -->
		<?php endforeach;?>
		<?php endif;?>
	</div>
</div>

<script>
	function showGuide(id) {
		console.log(id);
		$('.data').addClass('d-none');
		$('.guide').addClass('d-none');
		$('#data-' + id).removeClass('d-none');
		$('#guide-' + id).removeClass('d-none');
	}

</script>