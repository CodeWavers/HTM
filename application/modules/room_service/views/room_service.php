<div class="card">
    <?php if($this->permission->method('Room_service_model','create')->access()): ?>
    <div class="card-header">
        <h4>Service<small class="float-right"><button type="button"
        class="btn btn-primary btn-md" data-target="#add0" data-toggle="modal"><i class="fa fa-plus-circle"
        aria-hidden="true"></i>
       Add Service</button></small></h4>
    </div>
    <?php endif; ?>
    <div id="add0" class="modal fade" role="dialog">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <strong>Add Service</strong>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-12">
                            <div class="panel">
                                <div class="panel-body">
                                    <?php echo form_open('room_service/room_service/create') ?>
                                    <?php echo form_hidden('service_id', (!empty($intinfo->service_id)?$intinfo->service_id:null)) ?>
                                    <div class="form-group row">
                                        <label for="service_name"
                                            class="col-sm-4 col-form-label">Service Name
                                            <span class="text-danger">*</span></label>
                                            <div class="col-sm-8">
                                                <input name="service_name" class="form-control" type="text"
                                                placeholder="Service Name" id="service_name"
                                                value="">
                                            </div>
                                        </div>

                                    <div class="addService">
                                        <div id="service" class="service">
                                            <div class="form-group row">
                                                <label for="no_of_people" class="col-sm-4 col-form-label">Variation<span class="text-danger">*</span></label>
                                                <div class="col-sm-3">
                                                    <input name="v_name[]" autocomplete="off" class="v_name form-control" type="text"  value="" id="v_name" placeholder="Variation">
                                                </div>
                                                <div class="col-sm-3">
                                                    <input name="rate[]" autocomplete="off" class="rate form-control" type="text"  value="" id="rate" placeholder="Rate">
                                                </div>

                                                <div  class=" col-sm-2">
                                                    <a href="#" id="add_service" class=" btn-sm btn btn-black-soft add_service" ><i class="fa fa-plus-circle m-r-2"></i></a>
                                                </div>

                                            </div>


                                        </div>
                                    </div>
                                        <div class="form-group text-right">
                                            <button type="reset"
                                            class="btn btn-primary w-md m-b-5"><?php echo display('reset') ?></button>
                                            <button type="submit"
                                            class="btn btn-success w-md m-b-5"><?php echo display('ad') ?></button>
                                        </div>
                                        <?php echo form_close() ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
        <div id="edit" class="modal fade" role="dialog">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <strong><?php echo display('update');?></strong>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body editinfo">
                    </div>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
        <div class="row">
            <!--  table area -->
            <div class="col-sm-12">
                <div class="card-body">
                    <div class="table-responsive">
                    <table width="100%" id="exdatatable" class="datatable table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th width="10%"><?php echo display('sl_no') ?></th>
                                <th width="20%"Service Name</th>
                                <th width="20%">Variation & Rate</th>
                                <th width="10%" class="text-center"><?php echo display('action') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($facilitilist)) {
                            ?>
                            <?php $sl = 1; ?>
                            <?php foreach ($facilitilist as $type) { ?>
                            <tr class="<?php echo ($sl & 1)?"odd gradeX":"even gradeC" ?>">
                                <td><?php echo $sl; ?></td>
                                <td><?php echo html_escape($type['service_name']); ?></td>
                                <td><?php echo $type['var_list']; ?></td>

                                <td class="text-center">
                                    <?php if($this->permission->method('Room_service_model','update')->access()): ?>
                                    <input name="url" type="hidden"
                                    id="url_<?php echo html_escape($type['service_id']); ?>"
                                    value="<?php echo base_url("room_service/room_service/updateintfrm") ?>" />
                                    <a onclick="editinfo('<?php echo html_escape($type['service_id']); ?>')"
                                        class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left"
                                    title="Update"><i class="ti-pencil-alt text-white" aria-hidden="true"></i></a>
                                    <?php endif;
                                    if($this->permission->method('Room_service_model','delete')->access()): ?>
                                    <a href="<?php echo base_url("room_service/delete/".html_escape($type['service_id'])) ?>"
                                        onclick="return confirm('<?php echo display("are_you_sure") ?>')"
                                        class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="right"
                                    title="Delete "><i class="ti-trash"></i></a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php $sl++; ?>
                            <?php } ?>
                            <?php } ?>
                        </tbody>
                        </table> <!-- /.table-responsive -->
                    </div>
                    </div>
                </div>



            </div>
        </div>



<script type="text/javascript">
    $(document).ready(function(){



        //  console.log(service)

        $("#add_service").click(function(){

            $(".addService").append("<div id=\"service\" class=\"service\">\n" +
                "                                            <div class=\"form-group row\">\n" +
                "                                                <label for=\"no_of_people\" class=\"col-sm-4 col-form-label\">Variation<span class=\"text-danger\">*</span></label>\n" +
                "                                                <div class=\"col-sm-3\">\n" +
                "                                                    <input name=\"v_name[]\" autocomplete=\"off\" class=\"v_name form-control\" type=\"text\"  value=\"\" id=\"v_name\" placeholder=\"Variation\" \">\n" +
                "                                                </div>\n" +
                "                                                <div class=\"col-sm-3\">\n" +
                "                                                    <input name=\"rate[]\" autocomplete=\"off\" class=\"rate form-control\" type=\"text\"  value=\"\" id=\"rate\" placeholder=\"Rate\">\n" +
                "                                                </div>\n" +
                "\n" +
                "                                                <div  class=\" col-sm-2\">\n" +
                "                                                    <a href=\"#\" id=\"\" class=\"btn-sm btn btn-danger-soft remove_service\" ><i class=\"fa fa-minus-circle m-r-2\"></i></a>\n" +
                "                                                </div>\n" +
                "\n" +
                "                                            </div>\n" +
                "\n" +
                "\n" +
                "                                    </div>");
        });

        //  $('select').select2();


    });



    $("body").on("click",".remove_service",function(e){
        $(this).parents('.service').remove();
        //the above method will remove the user_data div
    });



</script>