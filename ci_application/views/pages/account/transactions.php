<div id="page-transactions">
    <div class="content-wrapper">
        <div class="content-left">
            <?php echo $this->load->view('templates/navigation-sidebar'); ?>
        </div>
        <div class="content-right copy">
            <div class="panel" style="margin-top: 15px;">
                <div class="panel-title">
                    <h2>Transaction History</h2>
                </div>
                <div class="panel-body">
                    <div class="panel-box panel-box-borderless">
                        <table class="table table-responsive">
                            <thead>
                            <tr>
                                <th><b>Buyer</b></th>
                                <th><b>Asset</b></th>
                                <th><b>Commission</b></th>
                                <th><b>Purchase Price</b></th>
                                <th><b>Purchase Time</b></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if ($sales): ?>
                                <?php $counter = 0; ?>
                                <?php foreach ($sales as $sale): ?>
                                    <tr <?php if ($counter % 2) echo 'class=odd'; ?>>
                                        <td><small><?php echo $sale->display_name ? $sale->display_name : 'User # ' . $sale->user_id; ?></small></td>
                                        <td><small>
                                                <?php
                                                $asset = $this->assets_model->get_asset($sale->asset_id);
                                                echo $asset->asset_title ? $asset->asset_title : "Asset #" . $sale->asset_id;
                                                ?>
                                            </small></td>
                                        <td>
                                            <small><b>
                                                    <?php if ($sale->model_btc > 0): ?>
                                                        &#579;<?php echo round($sale->model_btc, 6); ?>
                                                    <?php else: ?>
                                                        $<?php echo number_format($sale->model_usd, 2); ?>
                                                    <?php endif; ?>
                                                </b></small>
                                        </td>
                                        <td>
                                            <small>
                                                <?php if ($sale->purchase_price_btc > 0): ?>
                                                    &#579;<?php echo round($sale->purchase_price_btc, 6); ?>
                                                <?php else: ?>
                                                    $<?php echo number_format($sale->purchase_price_usd, 2); ?>
                                                <?php endif; ?>
                                            </small>
                                        </td>
                                        <td><small><?php echo date('F j, Y, g:i a', $sale->purchase_created); ?></small></td>
                                    </tr>
                                    <?php $counter++; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" style="text-align: center; font-weight: bold;"><small>No transactions found.</small></td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>