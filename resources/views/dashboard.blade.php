<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
     @include('head')
     <style type="text/css" media="print">
     	   @media print {
			    @page { margin: 0px 6px; }
  				body  { margin: 0px 6px; }   					  
			}
     </style>
</head>
<body >
    <?php use App\Http\Helpers\Helpdesk; ?>
 
 <div id="contents">
    <div class="container container-fluid">            	
		@include('header')
		<div class="row">
			<div class="col-md-12">
				<div class="status-barang">Report <span class="id-detail">{{$from}} to {{$to}}</span></div>
			</div>
		</div>
		<br/>
		<div class="row">
		    <div class="col-md-4 sum-inbound">
		    	Inbound
		    	<div class="number">{{isset($db_delivery->inbound) ? $db_delivery->inbound : "0"}}</div>
	    	</div>
	    	<div class="col-md-4 sum-outbound">
	    		Outbound
	    		<div class="number">{{isset($db_delivery->outbound) ? $db_delivery->outbound : "0"}}</div>
	    	</div>
	    	<a href="/allorder?from={{$from}}&to={{$last_send}}&delivery=no" target="_blank">
		    	<div class="col-md-4 sum-not_outbound">
		    		Selisih
		    		<div class="number">{{isset($db_delivery->not_outbound) ? $db_delivery->not_outbound : "0"}}</div>
		    	</div>
	    	</a>
	    </div>
	    	    
	    <br/>
	    <div class="row">
	    	<div class="col-md-6">
	    		<div class="chart">
		    		<div class="col-md-12 ">
			    		<div class="label-chart">Merchant</strong></div>
		    		</div>	    		    		
		    		<div id="merchant-chart" style="height: 250px;"></div>
		    		<br/><br/>
		    		<div id="legend-merchant" class="donut-legend"></div>
	    		</div>
	    	</div>	    	
	    	<div class="col-md-6">
	    		<div class="chart">
		    		<div class="col-md-12">
			    		<div class="label-chart">Courier</strong></div>
		    		</div>	    		    		
		    		<div id="courier-chart" style="height: 250px;"></div><br/><br/>
		    		<div id="legend-courier" class="donut-legend"></div>
	    		</div>
	    	</div>
	    </div>
	</div>
</div>
</body>
</html>
<script type="text/javascript">
	var color_array = ['#03658C', '#7CA69E', '#F2594A', '#F28C4B', '#7E6F6A', '#36AFB2', '#9c6db2', '#d24a67', '#89a958', '#00739a', '#BDBDBD'];	
		var merchant_chart = Morris.Donut({
		  element: 'merchant-chart',
		  data: [
		  	@foreach ($db_merchant as $value)
		  		{label: "{{$value->merchant}}", value: {{$value->total}}},
		  	@endforeach
		  ],
		  colors: color_array
		}).on('click', function(i, row) {
			console.log(i, row);
		});;

		merchant_chart.options.data.forEach(function(label, i){
		    var legendItem = $('<span></span>').text(label['label']).prepend('<i>&nbsp;</i>');
		    legendItem.find('i').css('backgroundColor', merchant_chart.options.colors[i]);
		    $('#legend-merchant').append(legendItem)
		})
	


	
		var courier_chart =  Morris.Donut({
		  element: 'courier-chart',
		  data: [
		    @foreach ($db_courier as $value)
		  		{label: "{{$value->name}}", value: {{$value->total}}},
		  	@endforeach
		  ],
		  colors: color_array
		}).on('click', function(i, row) {
			console.log(i, row);
		});

		courier_chart.options.data.forEach(function(label, i){
		    var legendItem = $('<span></span>').text(label['label']).prepend('<i>&nbsp;</i>');
		    legendItem.find('i').css('backgroundColor', courier_chart.options.colors[i]);
		    $('#legend-courier').append(legendItem)
		})
	
	

</script>