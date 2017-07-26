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
<body>
    <?php use App\Http\Helpers\Helpdesk; ?>
 
 <div id="contents">
    <div class="container container-fluid">            	
		@include('header')
		
    	 <div class="row">	
		    <div class="col-md-10">
		    	<div class="status-barang">Detail Order from : <span class="id-detail">{{$orderid}}</span></div>
	    	</div>	    	
		    <div class="col-md-2">
		    	<img src="data:image/png;base64,{{$qrcode}}">
	    	</div>	    	
	    </div>    

	     <div class="row" style=" margin-bottom: 9%;">		    	
		    	<div class="col-md-12">		    		
		    		<div class="box-input list-record">		    			
			    		<table class="table table-bordered">
			    			<thead>			    				
			    				<th>
			    					Origin
			    				</th>	
			    				<th>
			    					Destination
			    				</th>
			    				<th>
			    					Phone
			    				</th>    							    						
			    				<th>
			    					Status
			    				</th>			    							    							    				
			    				<th>
			    					Remark
			    				</th>
			    				<th>
			    					Delivery Type
			    				</th>
			    				<th>
			    					Courier
			    				</th>			    				
			    				<th>
			    					Date
			    				</th>				    							
			    			</thead>  					
			    			<tbody>					    			
			    				@foreach ($row as $key => $value)
			    					<tr>	
			    						<td>
			    							{{isset($inventory)? $inventory->origin : ""}}
			    						</td>		    						
			    						<td>
			    							{{isset($inventory)? $inventory->dest : ""}}
			    						</td>		    						
			    						<td>
			    							{{isset($inventory)? $inventory->phone : ""}}
			    						</td>		    						
			    						<td>
			    							@if ($value->status=="out")
			    								<div class="out">{{$value->status}}</div>
			    							@elseif ($value->status=="in")
			    								<div class="in">{{$value->status}}</div>
			    							@elseif ($value->status=="undel")
			    								<div class="undel">{{$value->status}}</div>
			    							@endif
			    						</td>
			    						<td>
			    							{{$value->remark}}
			    						</td>
			    						<td>
			    							{{$value->delivery_type}}
			    						</td>
			    						<td>
			    							{{$value->courier_name}}
			    						</td>
			    						<td>{{$value->last_update}}</td>			    						
			    					</tr>	
			    				@endforeach
			    						
			    			</tbody>
						</table>
						<?php echo $row->appends(['orderid' => $orderid])->links(); ?>
					</div>
		    	</div>	    	
	    </div>    
    </div>   
</div>


</html>

 <script>
 var typex="{{$type}}";
   $( function() {

    $( ".datepicker" ).datepicker({
    	dateFormat: "yy-mm-dd"
    });
  } );
   
  </script>