<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <title>NQCL - {{@$title}}</title>

    <!--=== CSS ===-->

    <!-- Bootstrap -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!--    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />-->

    <!-- jQuery UI -->
    <!--<link href="plugins/jquery-ui/jquery-ui-1.10.2.custom.css" rel="stylesheet" type="text/css" />-->
    <!--[if lt IE 9]>
            <link rel="stylesheet" type="text/css" href="plugins/jquery-ui/jquery.ui.1.10.2.ie.css"/>
    <![endif]-->

    <!-- Theme -->
    <link href="{{asset('assets/css/main.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/plugins.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/responsive.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/icons.css')}}" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="{{asset('assets/css/fontawesome/font-awesome.min.css')}}">
    <!--[if IE 7]>
            <link rel="stylesheet" href="assets/css/fontawesome/font-awesome-ie7.min.css">
    <![endif]-->

    <!--[if IE 8]>
            <link href="assets/css/ie8.css" rel="stylesheet" type="text/css" />
    <![endif]-->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'>


    <!--=== JavaScript ===-->

    <script type="text/javascript" src="{{asset('assets/js/libs/jquery-1.10.2.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js')}}"></script>

    <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="{{asset('assets/js/libs/lodash.compat.min.js')}}"></script>

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
            <script src="assets/js/libs/html5shiv.js"></script>
    <![endif]-->

    <!-- Smartphone Touch Events -->
    <script type="text/javascript" src="{{asset('plugins/touchpunch/jquery.ui.touch-punch.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('plugins/event.swipe/jquery.event.move.js')}}"></script>
    <script type="text/javascript" src="{{asset('plugins/event.swipe/jquery.event.swipe.js')}}"></script>

    <!-- General -->
    <script type="text/javascript" src="{{asset('assets/js/libs/breakpoints.js')}}"></script>
    <script type="text/javascript" src="{{asset('plugins/respond/respond.min.js')}}"></script> <!-- Polyfill for min/max-width CSS3 Media Queries (only for IE8) -->
    <script type="text/javascript" src="{{asset('plugins/cookie/jquery.cookie.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('plugins/slimscroll/jquery.slimscroll.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('plugins/slimscroll/jquery.slimscroll.horizontal.min.js')}}"></script>

    <!-- Page specific plugins -->
    <!-- Charts -->
    <!--[if lt IE 9]>
            <script type="text/javascript" src="plugins/flot/excanvas.min.js"></script>
    <![endif]-->
    <script type="text/javascript" src="{{asset('plugins/sparkline/jquery.sparkline.min.js')}}"></script> 
    <script type="text/javascript" src="{{asset('plugins/easy-pie-chart/jquery.easy-pie-chart.min.js')}}"></script>

    <script type="text/javascript" src="{{asset('plugins/daterangepicker/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
    <script type="text/javascript" src="{{asset('plugins/blockui/jquery.blockUI.min.js')}}"></script>

    <script type="text/javascript" src="{{asset('plugins/fullcalendar/fullcalendar.min.js')}}"></script>

    <!-- Noty -->
    <script type="text/javascript" src="{{asset('plugins/noty/jquery.noty.js')}}"></script>
    <script type="text/javascript" src="{{asset('plugins/noty/layouts/top.js')}}"></script>
    <script type="text/javascript" src="{{asset('plugins/noty/themes/default.js')}}"></script>

    <!-- Forms -->
    <script type="text/javascript" src="{{asset('plugins/uniform/jquery.uniform.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('plugins/select2/select2.min.js')}}"></script>

    <!-- App -->
    <script type="text/javascript" src="{{asset('assets/js/app.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/plugins.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/plugins.form-components.js')}}"></script>


    <!--=== DataTables ===-->
    <script type="text/javascript" src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('plugins/datatables/tabletools/TableTools.min.js')}}"></script> <!-- optional -->
    <script type="text/javascript" src="{{asset('plugins/datatables/colvis/ColVis.min.js')}}"></script> <!-- optional -->
    <script type="text/javascript" src="{{asset('plugins/datatables/DT_bootstrap.js')}}"></script>
    <script>
$(document).ready(function () {
    "use strict";
    App.init(); // Init layout and core plugins
    Plugins.init(); // Init all plugins
    FormComponents.init(); // Init all form-specific plugins
    $('#methodarea').hide();
    var base = window.location.href;
    var base_url = base.slice(0, base.lastIndexOf("/"));
    $('#culture-time').change(function () {
        id = $(this).val();
        $.get(base_url + '/client/' + id, function (resp) {
            $('#cname').val(resp[0].name);
            $('#cemail').val(resp[0].email);
            $('#caddress').val(resp[0].address);
            $('#coname').val(resp[0].contact_person);
            $('#cphone').val(resp[0].contact_phone);
            $('#ctype').val('NDQ' + resp[0].client_type + 'TEMP' + Math.floor((Math.random() * 1000000) + 1));
        });
    });
    $('select#METHODSPICK').change(function () {

        var value = $(this).val();
        if (value == 'Other') {
            $(this).prop('name', '');
            $("textarea#methodarea").prop('name', 'method');
            $("textarea#methodarea").prop('name', 'method');
            $("textarea#methodarea").prop('required', true);
            $("textarea#methodarea").show();
        } else {
            $('select#METHODSPICK').prop('name', 'method');
            $("textarea#methodarea").prop('name', '');
            $("textarea#methodarea").prop('required', false);
            $("textarea#methodarea").hide();
        }
    });
    $(".MNFDATE,.EXPDATE").datepicker({
        changeYear: true,
        dateFormat: 'M-yy'
    });
    $('a.ADDCONT').click(function () {
        $(this).hide();
        var secondcontact = '<div class="form-group CLASS2"><fieldset><legend>2<sup>nd</sup> Contact Person </legend><div class="col-md-4"><input id="" class="form-control" name="cont[]"  placeholder="Name" type="text"></div><div class="col-md-3"><input id="" class="form-control" name="cphone[]"  placeholder="Phone Number" type="text"/></div><div class="col-md-4"><input  class="form-control"  name="cemail[]" required="" placeholder="Email" type="text"></div><div class="col-md-1"><a href="#remove" class="REMCONT">-Rem</a></div></fieldset></div>';
        var $div = $('.FGROUP');
        $div.append(secondcontact);
    });
    $(document).on('click', 'a.REMCONT', function () {
        $('a.ADDCONT').show();
        $('.CLASS2').remove();
    });
    $(".uniform").click(function () {

        var favorite = [];
        $.each($(".uniform:checked"), function () {

            favorite.push($(this).val());
        });
        var message = "Hello kindly send me a quotation for the following tests: " + favorite.join(", ");
        $('textarea#tests_requested').val(message);
    });






    /*@if(Auth::check())
     @if (Session::has('success'))
     noty({
     type:'success',
     text: "{{Session::get('success')}}",
     })
     
     @else
     noty({
     type:'error',
     text: "{{Session::get('error')}}",
     })
     @endif
     @endif*/




});

$(document).ready(function () {
    $('input#UPROFILE').click(function () {
        if ($(this).is(':checked')) {
            $(this).hide();
            $('.CLIUPDATE').show();
        }
    });


    $('input#CPROFILE').click(function () {
        if ($(this).is(':checked')) {
            $(this).hide();
            $('.CPUPDATE').show();
        }
    });



    $('input#UPASS').click(function () {
        if ($(this).is(':checked')) {
            $(this).hide();
            $('.CLIPASS').show();
        }
    });

    $('input#CPUPASS').click(function () {
        if ($(this).is(':checked')) {
            $(this).hide();
            $('.CPCLIPASS').show();
        }
    });

    $("#CHILDUPDATER").click(function () {
        var r = confirm("Are you sure you want to update your profile");
        if (r == true) {
            document.getElementById("CHILDFORM").submit();
        } else {
            alert('No Changes Made')
        }
    });
    $("#PARENTUPDATER").click(function () {
        var r = confirm("Are you sure you want to update your profile");
        if (r == true) {
            document.getElementById("PARENTFORM").submit();
        } else {
            alert('No Changes Made')
        }
    });
});


    </script>
    <script type="text/javascript" src="{{asset('js/script.js')}}"></script>
    <!-- Demo JS -->
    <script type="text/javascript" src="{{asset('assets/js/custom.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/demo/pages_calendar.js')}}"></script>
</head>
