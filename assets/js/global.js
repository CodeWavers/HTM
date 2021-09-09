
$('#url_status').on('change',function(){
    'use strict';
    if($(this).val()==1){
        var url = $('#url').val();
            url = url.replace('https','http');
            $('#url').val(url);
    }
    else{
        var url = $('#url').val();
            url = url.replace('http','https');
            $('#url').val(url);
    }
});

    $(function () {
        'use strict';

        $('.bd-sidebar .with-sub').on('click', function (e) {
            e.preventDefault();
            $(this).parent().toggleClass('show');
            $(this).parent().siblings().removeClass('show');
        });

        $(document).on('click touchstart', function (e) {
            e.stopPropagation();
            // closing of sidebar menu when clicking outside of it
            if (!$(e.target).closest('.bd-header-menu-icon').length) {
                var sidebarTarg = $(e.target).closest('.bd-sidebar').length;
                if (!sidebarTarg) {
                    $('body').removeClass('bd-sidebar-show');
                }
            }
        });
        $('#sidebarToggle').on('click', function (e) {
            e.preventDefault();

            if (window.matchMedia('(min-width: 992px)').matches) {
                $('body').toggleClass('bd-sidebar-hide');
            } else {
                $('body').toggleClass('bd-sidebar-show');
            }
        });
        new PerfectScrollbar('.sidebar-body', {
            suppressScrollX: true
        });
        var emt = $("#emtycheck").val();
    if(emt){
            if(emt=="home"){
        new PerfectScrollbar('.message_widgets', {
            suppressScrollX: true
        });
        new PerfectScrollbar('.message_widgets2', {
            suppressScrollX: true
        });
        new PerfectScrollbar('.message_widgets3', {
            suppressScrollX: true
        });
        }
    }
    });
//search text
        $(document).ready(function () {
            'use strict';
            $("body").on("focus", ".search__text", function () {
                $(this).closest(".search").addClass("search--focus");
            }), $("body").on("blur", ".search__text", function () {
                $(this).val(""), $(this).closest(".search").removeClass("search--focus");
            });
             //select2
            $(".basic-single").select2();
        });

//calendar 
$(document).ready(function () {
    'use strict';
    if ($(".bd-clock")[0]) {
        var a = new Date;
        a.setDate(a.getDate()), setInterval(function () {
            var a = (new Date).getSeconds();
            $(".time__sec").html((a < 10 ? "0" : "") + a)
        }, 1e3), setInterval(function () {
            var a = (new Date).getMinutes();
            $(".time__min").html((a < 10 ? "0" : "") + a)
        }, 1e3), setInterval(function () {
            var a = (new Date).getHours();
            $(".time__hours").html((a < 10 ? "0" : "") + a)
        }, 1e3)
    }
})
'use strict';
var date = new Date();
date.setDate(date.getDate()-1);
$('.datepickers').bootstrapMaterialDatePicker({
format: 'YYYY-MM-DD',
startDate: new Date(),
shortTime: false,
date: true,
time: false,
monthPicker: false,
year: false,
switchOnClick: true,
});
date.setDate(date.getDate()-1);
$('.datepickerwithoutprevdate').bootstrapMaterialDatePicker({
format: 'YYYY-MM-DD',
startDate: new Date(),
shortTime: false,
date: true,
time: false,
monthPicker: false,
year: false,
switchOnClick: true,
minDate:new Date(),
});
'use strict';
$('.datepickerwithoutprevdate').change(function(){
$('.datepickerwithoutprevdates').bootstrapMaterialDatePicker({
format: 'YYYY-MM-DD',
startDate: new Date(),
shortTime: false,
date: true,
time: false,
monthPicker: false,
year: false,
switchOnClick: true,
});
date = $(this).val(); 
$('.datepickerwithoutprevdates').bootstrapMaterialDatePicker('setMinDate', date);
});
//time picker
$('.timepicker').bootstrapMaterialDatePicker({
    format: 'YYYY-MM-DD',
    startDate: new Date(),
    shortTime: true,
    date: false,
    time: true,
    monthPicker: false,
    year: false,
    switchOnClick: true,
    });
//base
var base = $('#base').val();
var baseurl = base;
//Active Menu
$(document).ready(function(){
    "use strict";
    $(this).find('.secondl li.active').parent().addClass("mm-show");
    $(this).find('.secondl li.active').parent().parent().addClass("mm-active");       
});
