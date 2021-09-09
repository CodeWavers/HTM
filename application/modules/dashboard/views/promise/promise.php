<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="card">
            <div class="card-header">
                <h6><?php echo "Home Section 1" ?></h6>    
            </div>
            <div class="card-body">
                <!-- language -->
                <div class="table-responsive">
                    <table class="table table-bordered table-sm" language_list>
                        <thead>
                            <tr>
                                <th width="5%"><i class="fa fa-th-list"></i></th>
                                <th width="25%"><?php echo display('name'); ?></th>
                                <th width="30%"><?php echo display('description'); ?></th>
                                <th width="15%" class="text-center"><i class="fa fa-cogs"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($team_title)) { ?>
                            <?php $sl = 1 ?>
                            <tr>
                                <td><?php echo $sl++ ?></td>
                                <td><?php echo html_escape($team_title->widget_title); ?></td>
                                <td><?php echo html_escape($team_title->widget_desc); ?></td>
                                <td class="text-center">
                                    <a href="javascript:void(0)"
                                        onclick="promisetitle_edit('<?php echo html_escape($team_title->widgetid); ?>')"
                                        class="btn btn-primary custom_btn"><i class="ti-pencil-alt"></i></a>
                                </td>
                            </tr>
                            <?php } ?>
                            <?php if (!empty($team_title1)) { ?>
                            <?php $sl = 2 ?>
                            <tr>
                                <td><?php echo $sl++ ?></td>
                                <td><?php echo html_escape($team_title1->widget_title); ?></td>
                                <td><?php echo html_escape($team_title1->widget_desc); ?></td>
                                <td class="text-center">
                                    <a href="javascript:void(0)"
                                        onclick="promisetitle_edit('<?php echo html_escape($team_title1->widgetid); ?>')"
                                        class="btn btn-primary custom_btn"><i class="ti-pencil-alt"></i></a>
                                </td>
                            </tr>
                            <?php } ?>
                            <?php if (!empty($team_title2)) { ?>
                            <?php $sl = 3 ?>
                            <tr>
                                <td><?php echo $sl++ ?></td>
                                <td><?php echo html_escape($team_title2->widget_title); ?></td>
                                <td><?php echo html_escape($team_title2->widget_desc); ?></td>
                                <td class="text-center">
                                    <a href="javascript:void(0)"
                                        onclick="promisetitle_edit('<?php echo html_escape($team_title2->widgetid); ?>')"
                                        class="btn btn-primary custom_btn"><i class="ti-pencil-alt"></i></a>
                                </td>
                            </tr>
                            <?php } ?>
                            <?php if (!empty($team_title3)) { ?>
                            <?php $sl = 4 ?>
                            <tr>
                                <td><?php echo $sl++ ?></td>
                                <td><?php echo html_escape($team_title3->widget_title); ?></td>
                                <td><?php echo html_escape($team_title3->widget_desc); ?></td>
                                <td class="text-center">
                                    <a href="javascript:void(0)"
                                        onclick="promisetitle_edit('<?php echo html_escape($team_title3->widgetid); ?>')"
                                        class="btn btn-primary custom_btn"><i class="ti-pencil-alt"></i></a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <?php if (empty($team_title)) { ?>
                            <tr>
                                <th colspan="6" class="text-center text-danger">
                                    <?php echo display('record_not_found'); ?></th>
                            </tr>
                            <?php } ?>
                        </tfoot>
                    </table>
                </div>
                <!-- The Modal -->
                <div class="modal fade" id="promise_info" role="dialog">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title modal_ttl"></h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body" id="promise">

                            </div>
                        </div>
                    </div>
                </div>
            </div> 
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm" language_list>
                        <thead>
                            <tr>
                                <th width="5%"><i class="fa fa-th-list"></i></th>
                                <th width="5%"><?php echo display('picture'); ?></th>
                                <th width="15%"><?php echo display('width'); ?></th>
                                <th width="15%"><?php echo display('height'); ?></th>
                                <th width="10%" class="text-center"><i class="fa fa-cogs"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($company_list)) { ?>
                                <?php $sl = 1 ?>
                                <?php foreach ($company_list as $company) { ?>
                                    <tr>
                                        <td><?php echo $sl++ ?></td>
                                        <td>
                                            <img src="<?php echo base_url() . ((html_escape($company->image)) ? html_escape($company->image) : ('assets/img/user-icon.png')); ?>" alt="<?php echo html_escape($company->title) ?>" width="60%">
                                        </td>
                                        <td><?php echo html_escape($company->width) ?>px</td>
                                        <td><?php echo html_escape($company->height) ?>px</td>
                                        <td class="text-center">
                                            <a href="javascript:void(0)" onclick="promiseimage_edit('<?php echo html_escape($company->slid); ?>')" class="btn btn-primary custom_btn"><i class="ti-pencil-alt"></i></a>  
                                        </td> 
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                        </tbody> 
                        <tfoot>
                            <?php if (empty($company_list)) { ?>
                                <tr>
                                    <th colspan="6" class="text-center text-danger"><?php echo display('record_not_found'); ?></th>
                                </tr>
                            <?php } ?>
                        </tfoot>
                    </table>  
                </div>
                <!-- The Modal -->
                <div class="modal fade" id="promise_info" role="dialog">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title modal_ttl"></h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body" id="promise">

                            </div>                    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>