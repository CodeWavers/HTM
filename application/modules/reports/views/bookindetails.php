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
<!--        --><?php //echo '<pre>';print_r($bookinfo);?>
<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover">


        <tbody>
            <tr>
                <td>
                    <div><strong><?php echo display('roomtype') ?></strong></div>
                </td>
                <td><?php echo html_escape(!empty(substr($room_name,0,-1))?substr($room_name,0,-1):null);?></td>
            </tr>

            <tr>
                <td>
                    <div><strong>Room No</strong></div>
                </td>
                <td><?php echo html_escape(!empty(substr($room_no,0,-1))?substr($room_no,0,-1):null);?></td>
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
                    <td><?php if($bookinfo->bookingstatus==0){ echo display('pending');}if($bookinfo->bookingstatus==2){ echo 'Checked In';}if($bookinfo->bookingstatus==4){ echo 'Confirmed';}if($bookinfo->bookingstatus==1){ echo "Cancel";}?></td>
                </tr>
                <tr>
                    <td>
                        <div><strong>No of People</strong></div>
                    </td>
                    <td><?php echo html_escape($bookinfo->nuofpeople);?></td>
                </tr>
                <tr>
                    <td>
                        <div><strong><?php echo display('number_of_rooms') ?></strong></div>
                    </td>
                    <td><?php echo html_escape($bookinfo->totalRoom);?></td>
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
        $service_total=0
        ?>
    <?php if ($booking_service) {?>

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

            <?php $x=0 ;
           ?>

            <?php foreach ($booking_service as $s)
            {?>
                <?php $x++?>
                <tr>
                    <td><?php echo $x ?></td>
                    <td>
                        <div><strong><?php echo $s->service_name?></strong></div>
                    </td>
                    <td>
                        <div><strong><?php echo $s->variation_name?></strong></div>
                    </td>
                    <td>
                        <div><strong><?php echo $s->rate?></strong></div>
                    </td>
                </tr>

            <?php } ?>


            </tbody>
        </table>
    <?php } ?>

        <table class="table table-striped table-nowrap">
            <thead>
                <tr>
                    <th>#</th>
                    <th><?php echo display('date') ?></th>
                    <th>Room No</th>
                    <th><?php echo display('price') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php    $x=0;
                foreach ($booking_details as $bd){



                                    $totaldiscount=0;
                                    $roomrate=0;

                                    $total=0;
                                    for($i = 0; $i < $datediff; $i++){
                                    $alldays= date("Y-m-d", strtotime($firstdate . ' + ' . $i . 'day'));
                                    $x++;
                                    $getroom=$this->db->select("*")->from('tbl_room_offer')->where('roomid',$bd->roomid)->where('offer_date',$alldays)->get()->row();
                                  //  echo '<pre>';print_r($getroom);
                                    if(!empty($getroom)){
                                        $singleDiscount=$getroom->offer*$bd->total_room;
                                        $totaldiscount=$totaldiscount+$singleDiscount;
                                      //  $roomrate=$bookinfo->roomrate-$totaldiscount;
                                        $roomrate=$bd->room_rate-$getroom->offer;
                                        }
                                    else{
                                        $roomrate=$bd->room_rate;
                                        }


                                    $price=$bd->total_room*$bd->room_rate;
                                    $total=$total+$price;
                ?>
                <tr>
                    <td>
                        <div><strong><?php echo $x;?></strong></div>
                    </td>
                    <td><?php echo html_escape($alldays);?></td>
                    <td><?php echo html_escape($bd->room_no);?></td>
                    <td><?php echo html_escape($roomrate);?></td>
                </tr>


            <?php
                                    }

                }






            ?>




            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-sm-8">
        </div>
        <div class="col-sm-4">
            <ul class="list-unstyled text-right">
                <?php if ($bookinfo->discount >0){?>
                    <li>
                        <strong>Discount:</strong> <?php if($currency->position==1){echo html_escape($currency->curr_icon);}?><?php echo $bookinfo->discount;?><?php if($currency->position==2){echo html_escape($currency->curr_icon);}?>
                    </li>
                <?php } ?>

                <?php if ($bookinfo->discount_night >0){?>
                    <li class="mb-4">
                        <strong>Discount(Per Night):</strong> <?php if($currency->position==1){echo html_escape($currency->curr_icon);}?><?php echo $bookinfo->discount_night;?><?php if($currency->position==2){echo html_escape($currency->curr_icon);}?>
                    </li>
                <?php } ?>

                <li>
                    <strong><?php echo display('subtotal') ?>:</strong> <?php echo $sub_total=$bookinfo->sub_total;

                    ?>
                </li>


                <li hidden>
                    <strong><?php echo display('service_charge') ?> <?php echo html_escape($storeinfo->servicecharge);?>%:</strong> <?php $scharge=0; $scharge=$storeinfo->servicecharge*$sub_total/100;
                    echo $scharge; ?>
                </li>
                <li hidden>
                    <strong><?php echo display('tax') ?> <?php echo html_escape($storeinfo->vat);?>%:</strong> <?php $tax=0; $tax=$storeinfo->vat*$sub_total/100;
                    echo $tax;?>
                </li>
                <li hidden>
                    <strong><?php echo display('taxes_and_service_charge') ?> :</strong> <?php echo $scharge+$tax;?>
                </li>

                <?php if ($bookinfo->service_total >0){?>
                <li>
                   <strong>Room Service:</strong> <?php if($currency->position==1){echo html_escape($currency->curr_icon);}?><?php echo $service_total=$bookinfo->service_total;?><?php if($currency->position==2){echo html_escape($currency->curr_icon);}?>
                </li>
                <?php } ?>
                <li>
                    <strong><?php echo display('grand_total') ?>:</strong> <?php if($currency->position==1){echo html_escape($currency->curr_icon);}?><?php echo number_format($service_total+$scharge+$tax+$sub_total,2);?><?php if($currency->position==2){echo html_escape($currency->curr_icon);}?>
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