<div class="sidebar-header">
    <a href="<?php echo base_url('dashboard/home') ?>" class="sidebar-brand">
        <img class="sidebar-brand_icon"
            src="<?php echo html_escape(base_url((!empty($setting->logo)?$setting->logo:'assets/img/sidebar-logo.png'))) ?>"
            alt="">
    </a>
</div>
<!--/.sidebar header-->
<?php $image = $this->session->userdata('image'); 
                $fullname = $this->session->userdata('fullname'); 
                ?>
<div class="profile-element d-flex align-items-center flex-shrink-0">
    <div class="avatar online">
        <img src="<?php echo html_escape(base_url((!empty($image)?$image:'assets/img/user-icon.png'))) ?>"
            class="img-fluid rounded-circle" alt="">
    </div>
    <div class="profile-text">
        <h6 class="m-0"><?php echo html_escape($fullname);?></h6>
    </div>
</div>
<!--/.profile element-->
<div class="sidebar-body">
    <nav class="sidebar-nav">
        <ul class="metismenu">
            <li class="<?php echo (($this->uri->segment(1)=="dashboard")?"mm-active":null) ?>">
                <a href="<?php echo base_url('dashboard/home') ?>"><i class="ti-home"></i>
                    <?php echo display('dashboard')?></a>
            </li>
            
                        <?php  
                                $path = 'application/modules/';
                                $map  = directory_map($path);
                                $HmvcMenu   = array();
                                if (is_array($map) && sizeof($map) > 0)
                                foreach ($map as $key => $value) {
                                    $menu = str_replace("\\", '/', $path.$key.'config/menu.php'); 
                                    if (file_exists($menu)) {
                                        @include($menu);
                                    }
                    
                                }
                // module name
                $HmvcMenu2["accounts"] = array(
                    //set icon
                    "icon"           => "<i class='ti-bag'></i>",

                    // stockmovment
                "c_o_a" => array( 
                        "controller" => "accounts",
                        "method"     => "show_tree",
                        "url"		 => "accounts/chart-of-account",
                        "permission" => "read"
                    ), 
                
                    "debit_voucher" => array( 
                        "controller" => "accounts",
                        "method"     => "debit_voucher",
                        "url"		 => "accounts/debit-voucher",
                        "permission" => "create"
                    ), 
                "credit_voucher" => array( 
                        "controller" => "accounts",
                        "method"     => "credit_voucher",
                        "url"		 => "accounts/credit-voucher",
                        "permission" => "read"
                    ), 
                    "contra_voucher" => array( 
                        "controller" => "accounts",
                        "method"     => "contra_voucher",
                        "url"		 => "accounts/contra-voucher",
                        "permission" => "read"
                    ),
                    "journal_voucher" => array( 
                        "controller" => "accounts",
                        "method"     => "journal_voucher",
                        "url"		 => "accounts/journal-voucher",
                        "permission" => "read"
                    ),  
                    "voucher_approval" => array( 
                        "controller" => "accounts",
                        "method"     => "aprove_v",
                        "url"		 => "accounts/voucher-approval",
                        "permission" => "create"
                    ), 
                    
                    "account_report" => array( 
                                        "voucher_report" => array( 
                                        "controller" => "accounts",
                                        "method"     => "voucher_report",
                                        "url"		 => "accounts/voucher-report",
                                        "permission" => "read"
                                        ), 

                                        "cash_book" => array( 
                                            "controller" => "accounts",
                                            "method"     => "cash_book",
                                            "url"		 => "accounts/cash-book",
                                            "permission" => "read"
                                        ), 
                                        "bank_book" => array( 
                                            "controller" => "accounts",
                                            "method"     => "bank_book",
                                            "url"		 => "accounts/bank-book",
                                            "permission" => "read"
                                        ), 
                                    
                                        "general_ledger" => array( 
                                            "controller" => "accounts",
                                            "method"     => "general_ledger",
                                            "url"		 => "accounts/general-ledger",
                                            "permission" => "read"
                                        ), 
                                        "trial_balance" => array( 
                                            "controller" => "accounts",
                                            "method"     => "trial_balance",
                                            "url"		 => "accounts/trial-balance",
                                            "permission" => "read"
                                        ),
                                        "profit_loss" => array( 
                                            "controller" => "accounts",
                                            "method"     => "profit_loss_report",
                                            "url"		 => "accounts/profit-loss",
                                            "permission" => "read"
                                        ),
                                    
                                    "cash_flow" => array( 
                                            "controller" => "accounts",
                                            "method"     => "cash_flow_report",
                                            "url"		 => "accounts/cash-flow",
                                            "permission" => "read"
                                        ),
                                    
                                        "coa_print" => array( 
                                            "controller" => "accounts",
                                            "method"     => "coa_print",
                                            "url"		 => "accounts/coa-print",
                                            "permission" => "read"
                                        ),  
                    ), 
                );


                // module name
                $HmvcMenu2["customer"] = array(
                    //set icon
                    "icon"           => "<i class='typcn typcn-user'></i>
                ", 
                //group level name
                    "customer_list" => array(
                        //menu name
                            "controller" => "customer_info",
                            "method"     => "index",
                            "url"        => "customer/customer-list",
                            "permission" => "read"
                        
                    )
                );
                $HmvcMenu2["hrm"] = array(
                    //set icon
                    "icon"           => "<i class='fa fa-users'></i>", 

                //group level name
                "attendance" => array( 
                        'atn_form'    => array( 
                            "controller" => "Home",
                            "method"     => "index",
                            "url"        => "hrm/attendance-list",
                            "permission" => "read"
                        ), 
                        'atn_report'  => array( 
                            "controller" => "Home",
                            "method"     => "attenlist",
                            "url"        => "hrm/attendance-report",
                            "permission" => "read"
                        ), 
                    ),
                    "award" => array(
                    "new_award" => array(
                        //menu name
                            "controller" => "Award_controller",
                            "method"     => "create_award",
                            "url"        => "hrm/award-list",
                            "permission" => "create"
                        
                    ),
                    ),
                    "circularprocess" => array(
                    'add_canbasic_info'  => array( 
                            "controller" => "Candidate",
                            "method"     => "caninfo_create",
                            "url"        => "hrm/new-candidate",
                            "permission" => "create"
                        ), 
                    'can_basicinfo_list' => array( 
                            "controller" => "Candidate",
                            "method"     => "candidateinfo_view",
                            "url"        => "hrm/manage-candidate",
                            "permission" => "read"
                        ),
                    "candidate_shortlist" => array(
                            "controller" => "Candidate_select",
                            "method"     => "create_shortlist",
                            "url"        => "hrm/candidate-shortlist",
                            "permission" => "create"
                    
                    ), 
                    "candidate_interview" => array(
                        //menu name
                            "controller" => "Candidate_select",
                            "method"     => "create_interview",
                            "url"        => "hrm/interview",
                            "permission" => "create"
                        
                    ),     
                    "candidate_selection" => array(
                
                            "controller" => "Candidate_select",
                            "method"     => "create_selection",
                            "url"        => "hrm/candidate-selection",
                            "permission" => "create"
                    
                    ),
                    ),
                    "department" => array(
                    "department_list" => array(
                        //menu name
                            "controller" => "Department_controller",
                            "method"     => "create_dept",
                            "url"        => "hrm/department",
                            "permission" => "create"
                    ), 
                    "add_division" => array(
                        //menu name
                            "controller" => "Division_controller",
                            "method"     => "division_form",
                            "url"        => "hrm/add-division",
                            "permission" => "create"
                        
                    ), 
                    "division_list" => array(
                        //menu name
                            "controller" => "Division_controller",
                            "method"     => "index",
                            "url"        => "hrm/manage-division",
                            "permission" => "read"
                        
                    ), 
                ),  
                "employee" => array(
                    "position" => array(
                            "controller" => "Employees",
                            "method"     => "create_position",
                            "url"        => "hrm/position-list-details",
                            "permission" => "create"
                    ), 
                    "add_employee" => array(
                            "controller" => "Employees",
                            "method"     => "viewEmhistory",
                            "url"        => "hrm/add-employee",
                            "permission" => "create"
                    ), 
                    "manage_employee" => array(
                            "controller" => "Employees",
                            "method"     => "manageemployee",
                            "url"        => "hrm/manage-employee",
                            "permission" => "read"
                    ), 
                    "emp_performance" => array(
                            "controller" => "Employees",
                            "method"     => "create_emp_performance",
                            "url"        => "hrm/employee-perfomance",
                            "permission" => "create"
                    ),     
                    "emp_sal_payment" => array(
                            "controller" => "Employees",
                            "method"     => "emp_payment_view",
                            "url"        => "hrm/employee-payment",
                            "permission" => "read"
                    ), 
                ),
                "leave" => array(
                "weekly_holiday" => array(
                            "controller" => "Leave",
                            "method"     => "create_weekleave",
                            "url"        => "hrm/weekly-holiday",
                            "permission" => "read"
                        
                    ), 
                    "holiday" => array(
                            "controller" => "Leave",
                            "method"     => "holiday_view",
                            "url"        => "hrm/holiday",
                            "permission" => "read"
                    ), 
                    "add_leave_type" => array(  
                        "controller" => "Leave",
                                "method"     => "add_leave_type",
                                "url"        => "hrm/leave-type",
                        "permission" => "read"
                    ),
                    "leave_application" => array(  
                        "controller" => "Leave",
                                "method"     => "others_leave",
                                "url"        => "hrm/leave-application",
                        "permission" => "read"
                    ),
                ),
                "loan" => array(
                    "loan_grand" => array(
                            "controller" => "Loan",
                            "method"     => "create_grandloan",
                            "url"        => "hrm/grant-loan",
                            "permission" => "read"
                    ), 
                    "loan_installment" => array(
                                "controller" => "Loan",
                                                "method"     => "create_installment",
                                                "url"        => "hrm/loan-installment",
                                "permission" => "read"
                        ), 
                    "loan_report" => array(
                                "controller" => "Loan",
                                                "method"     => "loan_report",
                                                "url"        => "hrm/loan-report",
                                "permission" => "read"  
                        ), 
                ),
                "payroll" => array(
                "salary_type_setup" => array(
                        //menu name
                            "controller" => "Payroll",
                            "method"     => "create_salary_setup",
                            "url"        => "hrm/salary-type-setup",
                            "permission" => "read"     
                    ), 
                    "salary_setup" => array(
                        //menu name
                            "controller" => "Payroll",
                            "method"     => "create_s_setup",
                            "url"        => "hrm/salary-setup",
                            "permission" => "create"
                    
                    ), 
                    "salary_generate" => array(
                        //menu name
                            "controller" => "Payroll",
                            "method"     => "create_salary_generate",
                            "url"        => "hrm/salary-generate",
                            "permission" => "create"
                    
                    ), 
                ),
                );
                // module name
                $HmvcMenu2["payment_setting"] = array(
                    //set icon
                    "icon"           => "<i class='ti-money'></i>
                ", 
                //group level name
                    "paymentmethod_list" => array(
                        //menu name
                            "controller" => "paymentmethod",
                            "method"     => "index",
                            "url"        => "payment_setting/payment-method-list",
                            "permission" => "read"
                        
                    ),
                    "paymentmethod_setup" => array(
                        //menu name
                            "controller" => "paymentmethod",
                            "method"     => "paymentsetup",
                            "url"        => "payment_setting/payment-setup",
                            "permission" => "read"
                        
                    )
                );
                // module name
                $HmvcMenu2["purchase"] = array(
                    //set icon
                    "icon"           => "<i class='ti-shopping-cart' aria-hidden='true'></i>
                ", 

                "purchase_item" => array( 
                        "controller" => "purchase",
                        "method"     => "index",
                        "url"        => "purchase/purchase-list",
                        "permission" => "read"
                    ),
                    "purchase_add" => array( 
                        "controller" => "purchase",
                        "method"     => "create",
                        "url"        => "purchase/purchase-create",
                        "permission" => "create"
                    ),
                    "purchase_return" => array( 
                        "controller" => "purchase",
                        "method"     => "return_form",
                        "url"        => "purchase/purchase-return",
                        "permission" => "create"
                    ),
                    "return_invoice" => array( 
                        "controller" => "purchase",
                        "method"     => "return_invoice",
                        "url"        => "purchase/invoice-return-list",
                        "permission" => "create"
                    ),
                );
                // module name
                $HmvcMenu2["reports"] = array(
                    //set icon
                    "icon"           => "<i class='ti-bar-chart' aria-hidden='true'></i>
                ", 

                "booking_report" => array( 
                        "controller" => "report",
                        "method"     => "index",
                        "url"        => "reports/booking-report",
                        "permission" => "read"
                    ),
                "purchase_report" => array( 
                        "controller" => "report",
                        "method"     => "productreport",
                        "url"        => "reports/purchase-report",
                        "permission" => "read"
                    ),
                    "stock_report" => array( 
                        "controller" => "report",
                        "method"     => "stockreport",
                        "url"        => "reports/stock-report",
                        "permission" => "read"
                    ),
                    
                );

                // module name
                $HmvcMenu2["room_service"] = array(
                            //set icon
                            "icon"           => "<i class='ti-headphone-alt'></i>",

                            // stockmovment
                            "service_add" => array(
                                "controller" => "room_service",
                                "method"     => "show_tree",
                                "url"		 => "room_service/",
                                "permission" => "read"
                            ),

                        );
                // module name
                $HmvcMenu2["room_facilities"] = array(
                    //set icon
                    "icon"           => "<i class='ti-view-grid'></i>
                ", 

                //group level name
                    "faciliti_list" => array(
                        //menu name
                            "controller" => "room_facilities",
                            "method"     => "index",
                            "url"        => "room_facilities/room-facilities-list",
                            "permission" => "read"
                        
                    ),
                    "faciliti_details_list" => array(
                        //menu name
                            "controller" => "room_facilitidetails",
                            "method"     => "index",
                            "url"        => "room_facilities/room-facilities-details-list",
                            "permission" => "read"
                        
                    ), 
                    "roomsize_list" => array(
                        //menu name
                            "controller" => "room_size",
                            "method"     => "index",
                            "url"        => "room_facilities/room-size-list",
                            "permission" => "read"
                        
                    ), 
                );
                // module name
                $HmvcMenu2["room_reservation"] = array(
                    //set icon
                    "icon"           => "<i class='ti-layout-slider-alt'></i>
                ", 
                //group level name
                    "booking_list" => array(
                        //menu name
                            "controller" => "room_reservation",
                            "method"     => "index",
                            "url"        => "room_reservation/booking-list",
                            "permission" => "read"
                        
                    ),
                );
                $HmvcMenu2["room_setting"] = array(
                    //set icon
                    "icon"           => "<i class='typcn typcn-spanner'></i>
                ", 
                //group level name
                    "bed_list" => array(
                        //menu name
                            "controller" => "bed_type",
                            "method"     => "index",
                            "url"        => "room_setting/bed-list",
                            "permission" => "read"
                        
                    ),
                    "starclass_list" => array(
                        //menu name
                            "controller" => "starclass",
                            "method"     => "index",
                            "url"        => "room_setting/star-class-list",
                            "permission" => "read"
                        
                    ), 
                    "bookingtype_list" => array(
                        //menu name
                            "controller" => "booking_type",
                            "method"     => "index",
                            "url"        => "room_setting/booking-type-list",
                            "permission" => "read"
                        
                    ), 
                    "floorplan_list" => array(
                        //menu name
                            "controller" => "floorplan",
                            "method"     => "index",
                            "url"        => "room_setting/floor-plan-list",
                            "permission" => "read"
                        
                    ),
                    "room_list" => array(
                        //menu name
                            "controller" => "room_details",
                            "method"     => "index",
                            "url"        => "room_setting/room-list",
                            "permission" => "read"
                        
                    ),
                    "room_image" => array(
                        //menu name
                            "controller" => "room_images",
                            "method"     => "index",
                            "url"        => "room_setting/room-images",
                            "permission" => "read"
                        
                    ), 
                );
                // module name
                $HmvcMenu2["units"] = array(
                    //set icon
                    "icon"           => "<i class='ti-pin-alt' aria-hidden='true'></i>
                ", 

                //group level name
                    "unit_list" => array(
                        //menu name
                            "controller" => "unitmeasurement",
                            "method"     => "index",
                            "url"        => "units/unit-measurement-list",
                            "permission" => "read"
                        
                    ), 
                //group level name
                    "ingradient_list" => array(
                        //menu name
                            "controller" => "products",
                            "method"     => "index",
                            "url"        => "units/product-list",
                            "permission" => "read"
                        
                    ), 
                    "supplier_list" => array(
                        //menu name
                            "controller" => "supplierlist",
                            "method"     => "index",
                            "url"        => "units/supplier-list",
                            "permission" => "read"
                        
                    ), 
                
                );   

                                if(isset($HmvcMenu2) && $HmvcMenu2!=null && sizeof($HmvcMenu2) > 0)
                                foreach ($HmvcMenu2 as $moduleName => $moduleData) {
                            
                                // check module permission 
                                if (file_exists(APPPATH.'modules/'.$moduleName.'/assets/data/env'))
                                if ($this->permission->module($moduleName)->access()) {
                        
                                $this->permission->module($moduleName)->access();
                            ?>
            <li class="<?php echo (($this->uri->segment(1)==$moduleName)?"mm-active":null) ?>">
                <a class="has-arrow material-ripple"
                    href="#"><?php echo (($moduleData['icon']!=null)?$moduleData['icon']:null) ?><?php echo display($moduleName) ?></a>
                <ul class="nav-second-level <?php echo (($this->uri->segment(1)==$moduleName)?"mm-show":null) ?>">
                    <?php foreach ($moduleData as $groupLabel => $label) {
									if ($groupLabel!='icon') 
									if((isset($label['controller']) && $label['controller']!=null) && ($label['method']!=null)) {
									   if($this->permission->check_label($groupLabel)->access()){ 
									   ?>
                    <li
                        class="<?php $url=explode('/',$label['url']); echo (($this->uri->segment(2)==$url[1])?"active":null) ?>">
                        <a
                            href="<?php echo base_url($label['url']) ?>"><?php echo display($groupLabel) ?></a>
                    </li>
                    <?php  } 
                            	} else { 
								if($this->permission->check_label($groupLabel)->access()){
								foreach ($label as $url)
								$liclass='';
                                $ulclass='';
								if(($this->uri->segment(1)==$moduleName)||(($moduleName.'/'.$this->uri->segment(2))==$url['url'])){
								$liclass='mm-active';
								   $ulclass='mm-show';
									}
								?>
                    <li class="<?php //echo $liclass; ?>">
                        <a class="has-arrow" href="#" aria-expanded="false"><?php echo display($groupLabel) ?></a>
                        <ul class="mm-collapse<?php echo $ulclass; ?> secondl">
                            <?php foreach ($label as $name => $value) {
                                            if($this->permission->check_label($name)->access()){ ?>
                            <li
                                class="<?php $url=explode('/',$value['url']); echo (($this->uri->segment(2)==$url[1])?"active":null) ?>">
                                <a
                                    href="<?php echo base_url($value['url']) ?>"><?php echo display($name) ?></a>
                            </li>
                            <?php 
                                            } //endif
                                        } //endforeach
                                        ?>
                        </ul>
                    </li>
                    <?php } ?>
                    <!-- endif -->
                    <?php } ?>
                    <!-- endforeach -->
                    <?php } ?>
                </ul>
            </li>
            <!-- end if -->
            <?php } ?>
            <!-- end foreach -->
            <?php } ?>
            <?php if($this->session->userdata('isAdmin')) {?>
            <li class="<?php echo (($this->uri->segment(1)=="setting")?"mm-active":null) ?>"><a
                    href="<?php echo base_url('setting') ?>"><i
                        class="ti-settings"></i><?php echo display('settings')?></a></li>
            <?php } ?>
        </ul>
    </nav>
</div><!-- sidebar-body -->