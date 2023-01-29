<?php

function wpmlm_settings() {    
   

    ?>
    <div class="panel-heading">
        <h4 class="main-head"><i class="fa fa-cog" aria-hidden="true"></i></i> Settings </h4>
    </div>
    <section class="tile-area">
        <div class="container-fluid">
            <div class="card-columns">

                <div class="tile-single">
                    <div class="wooCommerce-earned">
                        <div class="accordion md-accordion" id="accordionGeneralSettings" role="tablist" aria-multiselectable="true">
                            <!-- Card header -->
                            <div class="card-header head" role="tab" id="headingGeneralSettings">
                                <a class="collapsed" data-toggle="collapse" data-parent="#accordionGeneralSettings" href="#collapseGeneralSettings"
                                aria-expanded="false" aria-controls="collapseGeneralSettings">
                                <h3 class="mb-0">
                                    <?php _e('General Settings','woocommerce-securewpmlm-unilevel'); ?><i class="fa fa-caret-down rotate-icon"></i>
                                </h3>
                                </a>
                            </div>
                            <!-- Card body -->
                            <div id="collapseGeneralSettings" class="collapse show" role="tabpanel" aria-labelledby="headingGeneralSettings" data-parent="#accordionGeneralSettings">
                                <div class="card-body">
                                    <div class="">
                                        <?php echo wpmlm_general_settings(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tile-single">
                    <div class="wooCommerce-earned">
                        <div class="accordion md-accordion" id="accordionBonusSettings" role="tablist" aria-multiselectable="true">
                            <!-- Card header -->
                            <div class="card-header head" role="tab" id="headingBonusSettings">
                                <a class="collapsed" data-toggle="collapse" data-parent="#accordionBonusSettings" href="#collapseBonusSettings"
                                aria-expanded="false" aria-controls="collapseBonusSettings">
                                <h3 class="mb-0">
                                    <?php _e('Bonus Settings','woocommerce-securewpmlm-unilevel'); ?><i class="fa fa-caret-down rotate-icon"></i>
                                </h3>
                                </a>
                            </div>
                            <!-- Card body -->
                            <div id="collapseBonusSettings" class="collapse show" role="tabpanel" aria-labelledby="headingBonusSettings"
                                data-parent="#accordionBonusSettings">
                                <div class="card-body">
                                    <div>
                                        <?php echo wpmlm_commission_settings(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tile-single">
                    <div class="wooCommerce-earned">
                        <div class="accordion md-accordion" id="accordionPayoutPurchaseSettings" role="tablist" aria-multiselectable="true">
                            <!-- Card header -->
                            <div class="card-header head" role="tab" id="headingPayoutPurchaseSettings">
                                <a class="collapsed" data-toggle="collapse" data-parent="#accordionPayoutPurchaseSettings" href="#collapsePayoutPurchaseSettings"
                                    aria-expanded="false" aria-controls="collapsePayoutPurchaseSettings">
                                    <h3 class="mb-0">
                                        <?php _e('Payout & Purchase Settings','woocommerce-securewpmlm-unilevel'); ?><i class="fa fa-caret-down rotate-icon"></i>
                                    </h3>
                                </a>
                            </div>
                            <!-- Card body -->
                            <div id="collapsePayoutPurchaseSettings" class="collapse show" role="tabpanel" aria-labelledby="headingPayoutPurchaseSettings"
                            data-parent="#accordionPayoutPurchaseSettings">
                                <div class="card-body">
                                    <div class="">
                                        <?php echo wpmlm_purchace_commission_settings(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </section>
    
    <?php
}