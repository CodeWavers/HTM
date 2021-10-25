<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8 text-center">
            <?php if($this->session->userdata('msg')) { ?>
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <?php echo $this->session->userdata('msg');
                    $this->session->unset_userdata('msg');
                    ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<div class="card">

    <?php if($this->permission->method('room_reservation','read')->access()): ?>
    <div class="col-lg-12 col-xl-12" >
        <!--Basic Line Chart-->
        <!--         <a class="btn btn-dark-green" data-toggle="popover-hover" data-img="//placehold.it/100x50">Hover over me</a>-->
        <div class="card md-6 mb-4 height_400 " >
            <!--             <div class="card-header">-->
            <!--                 <div class="d-flex justify-content-between align-items-center">-->
            <!--                     <div>-->
            <!--                         <h6 class="fs-17 font-weight-600 mb-0">Room</h6>-->
            <!--                     </div>-->
            <!--                 </div>-->
            <!--             </div>-->

            <div class="row">

                <style type="text/css" scoped>
                    div{
                        /*border: 1px solid black;*/
                        /*scrollbar*/
                    }

                    div::-webkit-scrollbar {
                        width: 12px;               /* width of the entire scrollbar */
                    }

                    div::-webkit-scrollbar-track {
                        background: #ffffff30;        /* color of the tracking area */
                    }

                    div::-webkit-scrollbar-thumb {
                        background-color: #ffffff30;    /* color of the scroll thumb */
                        border-radius: 20px;       /* roundness of the scroll thumb */
                        border: 3px solid #4ea752;  /* creates padding around scroll thumb */
                    }

                </style>

                <?php foreach ($floor_rooms as $floor) {?>

                    <div class="col-sm-6 col-md-6 col-cxl-4" >
                        <div class="card card-stats statistic-box mb-4 height_400" style="border-color: #26c6da;">
                            <div class="card-header card-header-info card-header-icon position-relative border-0 text-center px-3 py-0">
                                <div class="d-flex justify-content-between align-items-center ">
                                    <div class="card-icon d-flex align-items-center justify-content-center ">
                                        <p class="card-category text-uppercase fs-20 font-weight-bold" style="color: whitesmoke">
                                            <?php echo $floor['floor_name']?></p>
                                    </div>
                                    <!--                                 <div>-->
                                    <!--                                     <h6 class="fs-17 font-weight-600 mb-0">--><?php //echo $floor['floor_name']?><!--</h6>-->
                                    <!--                                 </div>-->
                                </div>
                            </div>

                            <!--                         --><?php //echo $floor['room_nos']?>
                            <div class="card-body p-2 col-sm-12" id="abc" style="max-height:481px; overflow-y: auto; overflow-x: hidden">


                                <div class="row">
                                    <?php echo $floor['room_nos']?>







                                </div>

                            </div>

                        </div>
                    </div>

                <?php } ?>

            </div>


        </div>

    </div>
</div>
<?php endif; ?>
<script type="text/javascript">

    $(document).ready(function(){
        $('[data-toggle="popover-hover"]').popover({
            html: true,
            trigger: 'hover',
            placement: 'bottom',
            content: function () { return '<div class="card border-secondary mb-3" style="max-width: 18rem;">\n' +

                '  <div class="card-body text-secondary">\n' +
                '    <h5 class="card-category text-uppercase fs-14 font-weight-bold">' + $(this).data('email') + '</h5>\n' +
                '    <h5 class="card-category text-uppercase fs-14 font-weight-bold">' + $(this).data('phone') + '</h5>\n' +
                '    <h5 class="card-category text-uppercase fs-14 font-weight-bold">Check In:' + $(this).data('ci') + '</h5>\n' +
                '    <h5 class="card-category text-uppercase fs-14 font-weight-bold">Check Out:' + $(this).data('co') + '</h5>\n' +
                '  </div>\n' +
                '</div>'; }
        });

    });



</script>