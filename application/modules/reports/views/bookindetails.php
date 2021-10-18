<div class="card">
    <div class="card-body" id="printArea">
        <div class="row">
            <div class="col-sm-6">
                <img src="<?php echo base_url();?><?php echo html_escape(!empty($commominfo->invoice_logo)?$commominfo->invoice_logo: 'assets/img/header-logo.png')?>" class="img-fluid mb-3" alt="">
                <br>
                <address>
                    <strong><?php echo html_escape($storeinfo->storename);?></strong><br>
                    <?php echo html_escape($storeinfo->address);?><br>
                <abbr title="Phone"><?php echo display('mobile') ?>:</abbr> <?php echo html_escape($storeinfo->phone);?>
            </address>
            <address>
                <strong><?php echo display('email') ?></strong><br>
                <a href="mailto:#"><?php echo html_escape($storeinfo->email);?></a>
            </address>
        </div>
        <div class="col-sm-6 text-right">
            <h1 class="h3"><?php echo display('booking_number') ?> #<?php echo html_escape($bookinfo->booking_number);?></h1>
            <div><?php echo display('booking_date') ?>: <?php echo html_escape($bookinfo->date_time);?></div>
            <div class="text-danger m-b-15"><?php echo display('payment_status') ?>:
                <?php if(isset($paymentinfo->paid_amount)){?>
                <?php if($paymentinfo->paid_amount < $bookinfo->total_price){ echo display('pending');}else{ echo display('complete');}?>
                <?php } else{echo display('pending');}?>
            </div>
            <address>
                <strong><?php echo display('guest_info') ?></strong><br>
                <?php echo html_escape((!empty($customerinfo->firstname)?$customerinfo->firstname.' '.$customerinfo->lastname:'User Deleted'));?><br>
                <?php echo display('address') ?>: <?php echo html_escape(!empty($customerinfo->address)?$customerinfo->address:null);?><br>
            <abbr title="Phone"><?php echo display('mobile') ?>:</abbr> <?php echo html_escape(!empty($customerinfo->cust_phone)?$customerinfo->cust_phone:null);?>
        </address>
        <address>
            <strong><?php echo display('email') ?></strong><br>
            <a href="mailto:#"><?php echo html_escape(!empty($customerinfo->email)?$customerinfo->email:null);?></a>
        </address>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover">
        <tbody>
            <tr>
                <td>
                    <div><strong><?php echo display('roomtype') ?></strong></div>
                </td>
                <td><?php echo html_escape(!empty($bookinfo->roomtype)?$bookinfo->roomtype:null);?></td>
            </tr>
            <tr>
                <td>
                    <div><strong><?php echo display('checkin') ?></strong></div>
                </td>
                <td><?php echo html_escape($bookinfo->checkindate);?></td>
                <tr>
                    <td>
                        <div><strong><?php echo display('checkout') ?></strong></div>
                    </td>
                    <td><?php echo html_escape($bookinfo->checkoutdate);?></td>
                </tr>
                <tr>
                    <td>
                        <div><strong><?php echo display('booking_status') ?></strong></div>
                    </td>
                    <td><?php if($bookinfo->bookingstatus==0){ echo display('pending');}if($bookinfo->bookingstatus==2){ echo display('complete');}if($bookinfo->bookingstatus==1){ echo "Cancel";}?></td>
                </tr>
                <tr>
                    <td>
                        <div><strong><?php echo display('adults') ?></strong></div>
                    </td>
                    <td><?php echo html_escape($bookinfo->nuofpeople);?></td>
                </tr>
                <tr>
                    <td>
                        <div><strong><?php echo display('number_of_rooms') ?></strong></div>
                    </td>
                    <td><?php echo html_escape($bookinfo->total_room);?></td>
                </tr>
                <tr>
                    <td>
                        <div><strong><?php echo display('nights') ?></strong></div>
                    </td>
                    <td><?php
                        $datetime1 = date_create($bookinfo->checkindate);
                        $datetime2 = date_create($bookinfo->checkoutdate);
                        $interval = date_diff($datetime1, $datetime2);
                        echo $totalnight=$interval->format('%a');
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php
        $firstdate = $bookinfo->checkindate;
        $lastdate = $bookinfo->checkoutdate;
        $datediff = strtotime($lastdate) - strtotime($firstdate);
        $datediff = floor($datediff/(60*60*24));
        ?>
        <table class="table table-bordered table-striped table-hover table-nowrap">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Service Name</th>
                    <th>Variation</th>
                    <th><?php echo display('price') ?></th>
                </tr>
            </thead>
            <tbody>

                <tr>
                    <td></td>
                    <td>
                        <div><strong></strong></div>
                    </td>
                    <td>   <div><strong></strong></div></td>
                    <td>   <div><strong></strong></div></td>
                </tr>

            </tbody>
        </table>
        <table class="table table-striped table-nowrap">
            <thead>
                <tr>
                    <th>#</th>
                    <th><?php echo display('date') ?></th>
                    <th><?php echo display('price') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                                    $totaldiscount=0;
                                    $roomrate=0;
                                    $x=0;
                                    $total=0;
                                    for($i = 0; $i < $datediff; $i++){
                                    $alldays= date("Y-m-d", strtotime($firstdate . ' + ' . $i . 'day'));
                                    $x++;
                                    $getroom=$this->db->select("*")->from('tbl_room_offer')->where('roomid',$bookinfo->roomid)->where('offer_date',$alldays)->get()->row();
                                    if(!empty($getroom)){
                                        $singleDiscount=$getroom->offer*$bookinfo->total_room;
                                        $totaldiscount=$totaldiscount+$singleDiscount;
                                        $roomrate=$bookinfo->roomrate-$totaldiscount;
                                        }
                                    else{
                                        $roomrate=$bookinfo->roomrate;
                                        }
                                    $price=$bookinfo->total_room*$bookinfo->roomrate;
                                    $total=$total+$price;
                ?>
                <tr>
                    <td>
                        <div><strong><?php echo $x;?></strong></div>
                    </td>
                    <td><?php echo html_escape($alldays);?></td>
                    <td><?php echo html_escape($roomrate);?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-sm-8">
        </div>
        <div class="col-sm-4">
            <ul class="list-unstyled text-right">
                <li>
                    <strong><?php echo display('subtotal') ?>:</strong> <?php $grprice=$bookinfo->total_room*$bookinfo->roomrate;
                    $grprice=($grprice*$totalnight)-$totaldiscount;
                    echo (($grprice!=0)?$grprice:$grprice=$bookinfo->roomrate);?>
                </li>
                <li>
                    <strong><?php echo display('service_charge') ?> <?php echo html_escape($storeinfo->servicecharge);?>%:</strong> <?php $scharge=0; $scharge=$storeinfo->servicecharge*$grprice/100;
                    echo $scharge; ?>
                </li>
                <li>
                    <strong><?php echo display('tax') ?> <?php echo html_escape($storeinfo->vat);?>%:</strong> <?php $tax=0; $tax=$storeinfo->vat*$grprice/100;
                    echo $tax;?>
                </li>
                <li>
                    <strong><?php echo display('taxes_and_service_charge') ?> :</strong> <?php echo $scharge+$tax;?>
                </li>
                <li>
                    <strong><?php echo display('grand_total') ?>:</strong> <?php if($currency->position==1){echo html_escape($currency->curr_icon);}?><?php echo number_format($scharge+$tax+$grprice,2);?><?php if($currency->position==2){echo html_escape($currency->curr_icon);}?>
                    <br /><strong><?php echo display('paid_amount') ?>:</strong> <?php if($currency->position==1){echo html_escape($currency->curr_icon);}?><?php if (!empty($paymentinfo->paymentamount)){echo $paymentinfo->paid_amount;} else echo "0";?><?php if($currency->position==2){echo html_escape($currency->curr_icon);}?>
                    <br /><strong><?php echo display('due_amount') ?>:</strong> <?php if($currency->position==1){echo html_escape($currency->curr_icon);}?><?php if (!empty($paymentinfo->paymentamount)){echo $bookinfo->total_price-$paymentinfo->paid_amount;} else echo html_escape($bookinfo->total_price);?><?php if($currency->position==2){echo html_escape($currency->curr_icon);}?>
                </li>
            </ul>
        </div>
    </div>
</div>
<div class="card-footer">
    <button type="button" class="btn btn-info mr-2"onclick="printContent('printArea')"><span
    class="fa fa-print"></span></button>
</div>
</div>