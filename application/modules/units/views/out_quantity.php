<div class="card">
    <?php if($this->permission->method('units','create')->access()): ?>
    <div class="card-header">
        <h4>Product Out Declaration <small class="float-right"> </small></h4>
    </div>

    <?php endif; ?>

    <div class="row">
        <!--  table area -->
        <div class="col-sm-12 col-md-12">
            <div class="card-body">


                <?php echo form_open_multipart('units/products/product_out',array('class' => 'form-vertical', 'id' => 'insert_purchase','name' => 'insert_purchase'))?>
                <input name="url" type="hidden" id="url"
                       value="<?php echo base_url("purchase/purchase/purchaseitem") ?>" />

                <table class="table table-bordered table-hover" id="purchaseTable">
                    <thead>
                    <tr>
                        <th class="text-center" width="20%"><?php echo display('item_information') ?><i
                                    class="text-danger">*</i></th>
                        <th class="text-center"><?php echo display('stockqnt') ?></th>
                        <th class="text-center"><?php echo display('quantity') ?> <i class="text-danger">*</i>
                        </th>
                        <th class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody id="addPurchaseItem">
                    <tr>
                        <td class="span3 supplier">
                            <input type="text" name="product_name" required="" class="form-control product_name"
                                   onkeypress="product_list(1);" placeholder="Item Name" id="product_name_1"
                                   tabindex="5">
                            <input type="hidden" class="autocomplete_hidden_value product_id_1"
                                   name="product_id[]" id="SchoolHiddenId">
                            <input type="hidden" class="sl" value="1">
                        </td>

                        <td class="wt">
                            <input type="text" id="available_quantity_1"
                                   class="form-control text-right stock_ctn_1" placeholder="0.00" readonly="">
                        </td>

                        <td class="text-right">
                            <input type="number" min="1" name="product_quantity[]" id="cartoon_1"
                                   class="form-control text-right store_cal_1" onkeyup="calculate_store(1);"
                                   onchange="calculate_store(1);" placeholder="0.00" value="" min="0" tabindex="6"
                                   required>
                        </td>


                        <td>
<!--                            <button class="btn btn-danger-soft red text_align_right" type="button"-->
<!--                                    value="--><?php //echo display('delete')?><!--" onclick="deletePurchaseRow(this)"-->
<!--                                    tabindex="8"><i class="fa fa-minus-circle m-r-2"></i></button>-->
                            <button  name="add-invoice-item" id="add_invoice_item" class="btn btn-info-soft text_align_right" type="button"
                                     value="Add" onclick="addmore('addPurchaseItem');"
                                     tabindex="8"><i class="fa fa-plus-circle m-r-2"></i></button>
                        </td>

                    </tr>
                    </tbody>
<!--                    <tfoot>-->
<!--                    <tr>-->
<!--                        <td colspan="2">-->
<!--                            <input type="button" id="add_invoice_item" class="btn btn-success"-->
<!--                                   name="add-invoice-item" onclick="addmore('addPurchaseItem');"-->
<!--                                   value="Add More item" tabindex="9">-->
<!--                        </td>-->
<!--                        <td class="text-right;" colspan="2"><b>--><?php //echo display('grand_total') ?><!--:</b>-->
<!--                        </td>-->
<!--                        <td class="text-right">-->
<!--                            <input type="text" id="grandTotal" class="text-right form-control"-->
<!--                                   name="grand_total_price" value="0.00" readonly="readonly">-->
<!--                        </td>-->
<!--                    </tr>-->
<!--                    </tfoot>-->
                </table>
                <div class="form-group row">
                    <div class="col-sm-6">
                        <input type="submit" id="add_purchase" class="btn btn-success btn-large" name="add-purchase"
                               value="Submit">
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo MOD_URL.$module;?>/assets/js/script.js"></script>