<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<title>Event Ticket</title>
<style>
* { box-sizing:border-box; font family: Arial, Helvetica, sans-serif; }
.ClearFix:after { content: ""; visibility: hidden; height: 0; display: block; clear: both; }
.TicketLogo { width: 60px; height: 60px;  border-radius: 50%;}
#qrcode img { height: 130px; width: 130px; max-width:130px; max-height: 130px; }
a.VideoButton, .VideoButton { display: inline-block; padding: 5px 10px; border: 1px solid #000; background-color: #000; color: #fff !important; font-size: 13px; font-weight: 400; border-radius: 5px; text-decoration: none;}
a.VideoButton:hover, .VideoButton:hover { color: #fff !important; }
.page-break {page-break-after: always;}
</style>
</head>
<body>
@foreach($ticket_details as $ticket_detail)
<div style="padding: 10px; margin: 0 auto; width: 100%; max-width: 500px;" class="page-break">
<div style="padding: 0 0 2px 0; text-align: center;">TIKO SAFI</div>
<div style="padding: 15px; width: 100%; background-color: #81a8e8; border: 1px solid #461b1b; border-radius: 5px;">
    <div style="overflow:auto ; padding: 0 0 15px 0;">
    	<div style="float: left; padding:0 80px 10px 0; "><img src="data:image/png;base64,{{ base64_encode(Storage::url($ticket_detail->event_image)) }}" class="TicketLogo"></div>
        <div style="float: right; color: #000;">
        	<div style="font-size: 12px; font-weight: bold; text-align: right;">ORDERID</div>
            <div style="font-size: 18px; font-weight: 300; text-align: right; color: #fff;">{{ $ticket_detail->order_id }}</div>
        </div>
    </div>

    <div style="padding: 0 5px 5px 0; color: #fff; font-size: 25px; font-weight: bold;">
    	FiestaVille 2024
    </div>

    <div style="height: 10px; position: relative;" class="ClearFix">
    	<div style="position: absolute; left: -21px; width: 10px; height: 10px; background-color: #fff; border-radius: 100%; border: 1px solid #bababa;"></div>
        <div style="position: absolute; right: -21px; width: 10px; height: 10px; background-color: #fff; border-radius: 100%; border: 1px solid #bababa;"></div>
        <div style="position: absolute; left: -23px; width: 7px; height: 12px; background-color: #fff;"></div>
        <div style="position: absolute; right: -23px; width: 7px; height: 12px; background-color: #fff;"></div>
    </div>
    <!------Left/Right-Components-------->
    <div style="margin: 5px 0 15px 0; color: #000;" class="ClearFix">
    	<div style="float: left; width: 65%">
            <div style="font-size: 12px; font-weight: bold;">DATE</div>
            <div style="font-size: 18px; font-weight: 300; color: #fff;">{{ $ticket_detail->event_start_date}}</div>
        </div>
        <div style="float: right; width: 30%">
            <div style="font-size: 12px; font-weight: bold; text-align: right;">TIME</div>
            <div style="font-size: 18px; font-weight: 300; text-align: right; color: #fff;">8.00pm - 10.00pm</div>
        </div>
    </div>
    <!-----End--Left/Right-Components-------------------->

    <div style="margin: 0 0 15px 0; color: #000;" class="ClearFix">
    	<div>
            <div style="font-size: 12px; font-weight: bold;">TICKET</div>
            <div style="font-size: 18px; font-weight: 300; color: #fff;">{{ $ticket_detail->id }}</div>
        </div>
    </div>
    <!------Left/Right-Components-------->
    <div style="margin: 0 0 15px 0; color: #000;" class="ClearFix">
        <div style="float: left; width: 65%;">
            <div style="font-size: 12px; font-weight: bold;">TICKET HOLDER</div>
            <div style="font-size: 18px; font-weight: 300; color: #fff;">{{ $ticket_detail->event_title }}</div>
        </div>
        <div style="float: right; width: 30%;">
            <div style="font-size: 12px; font-weight: bold; text-align: right;">PRICE</div>
            <div style="font-size: 18px; font-weight: 300; text-align: right; color: #fff;">KES {{ $ticket_detail->unit_price }}</div>
        </div>
    </div>
    <!-----End--Left/Right-Components-------------------->
    <!------Center-Ciomponent------->
    <div style="text-align: center;">
    	<div id="qrcode" style="margin: 0 auto; padding: 15px; width: 130px;"><img src="data:image/png;base64,{!! base64_encode(QrCode::format('svg')->size(50)->errorCorrection('H')->generate($ticket_detail->id)) !!}"/></div>
        <div style="overflow: hidden; margin: 0 auto; padding: 15px 0 0 0; width: auto;">
        	<div>
            	<div><a href="#" class="VideoButton" target="_blank">TIKO SAFI</a></div>
            </div>

        </div>
    </div>
    <!------End-Center-Component--------->
  </div>
</div>
@endforeach
</body>
</html>
