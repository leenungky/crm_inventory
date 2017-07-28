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
 <div id="contents">
    <div class="container container-fluid">            	
		@include('header')
    	<div class="row">
		    <div class="col-md-12">
		    	<div class="status-barang">All Order</strong></div>
	    	</div>	    	
	    </div>
	    <br/>
	    <form method="get" action="/allorder">
		    <div class="row">
		    	<div class="col-md-12">
		    		<div class="row">
		    			<div class="col-md-1" style="padding-right: 1px">
		    				From
		    				<input type="text" name="from" class="form-control datepicker" value="{{$from}}">
		    			</div>
		    			<div class="col-md-1"  style="padding-right: 1px">	
		    				To
		    				<input type="text" name="to" class="form-control datepicker"  value="{{$to}}">
		    			</div>
		    			<div class="col-md-1">	
		    				Order Id
		    				<input type="text" name="orderid" class="form-control"  value="{{$orderid}}">
		    			</div>		    			
		    			<div class="col-md-2"  style="padding-right: 1px">	    				
		    				Delivery Type
		    				<select class="form-control" name="delivery_type" id="delivery_type">
			    					<option value="">Pilih</option>
			    					@if ($delivery_type=="delivery")
			    						<option value="delivery" selected>delivery</option>
			    					@else 
			    						<option value="delivery">delivery</option>
			    					@endif
			    					@if ($delivery_type=="return")
			    						<option value="return" selected>return</option>
			    					@else
			    						<option value="return">return</option>
			    					@endif
		    				</select>
		    			</div>
		    			<div class="col-md-2">	    				
		    				Merchant
		    				<select class="form-control" name="merchant" id="merchant">
		    					<option value="">Pilih</option>
			    				@foreach ($merchants as $key => $value)
			    					@if ($value->name == $merchant)
			    						<option value="{{$value->id}}" selected>{{$value->name}}</option>
			    					@else
			    						<option value="{{$value->id}}">{{$value->name}}</option>
			    					@endif
			    				@endforeach
		    				</select>
		    			</div>
		    			<div class="col-md-2">	    				
		    				Courier Last
		    				<select class="form-control" name="courier" id="courier">
		    					<option value="">Pilih</option>
			    				@foreach ($couriers as $key => $value)
			    					@if ($value->id == $courier)
			    						<option value="{{$value->id}}" selected>{{$value->name}}</option>
			    					@else
			    						<option value="{{$value->id}}">{{$value->name}}</option>
			    					@endif
			    				@endforeach
		    				</select>
		    			</div>
		    			<div class="col-md-2" style="margin-top: 20px">
				    		  <input type="checkbox" name="delivery" value="no" {{empty($delivery)? "" : "checked"}}>No Delivery<br>
				    	</div>
		    			<div class="col-md-1" style="margin-top: 20px">
				    		<input type="submit" class="btn btn-primary find-order" value="find">			    	
				    	</div>
		    		</div>		    			    			    	
		    	</div>
		    </div>		    
	    </form>
	    
	     <div class="row" style=" margin-bottom: 9%;">		    			    			    		
		    		<div class="box-input list-record">		    			
		    			<div class="col-md-6" style="margin:1% 0">
			    			{{$count}} record
			    		</div>
			    		<div class="col-md-6" style="margin:1% 0;text-align: right;">
			    			<a href="/downloadall?{{$param_download}}">download</a>
			    		</div>
			    		<table class="table table-bordered">
			    			<thead>
			    				<th>
			    					Order Id
			    				</th>
			    				<th>
			    					Merchant
			    				</th>
			    				<th>
			    					Orig. Weight
			    				</th>
			    				<th>
			    					Round. Weight
			    				</th>
			    				<th>
			    					LAST IN
			    				</th>
			    				<th>
			    					LAST OUT
			    				</th>			    				
			    				<th>
			    					Remark IN
			    				</th>
			    				<th>
			    					Remark Out
			    				</th>			    				
			    				<th>
			    					Delivery Type
			    				</th>
			    				<th>
			    					Courier IN
			    				</th>		
			    				<th>
			    					Courier Out
			    				</th>		
			    				<th>
			    					Courier Last
			    				</th>		
			    				<th>
			    					Action
			    				</th>	    				
			    			</thead>
			    			<tbody>					    			
			    				@foreach ($row1 as $key => $value)
			    					@if ($value->order_no)
			    						<?php 
			    							$in = SiteHelpers::getMaxDayStatus($value->order_no, "in"); 
				    						$out = SiteHelpers::getMaxDayStatus($value->order_no, "out");
				    						$all = SiteHelpers::getMaxDayStatus($value->order_no);
			    							$days = SiteHelpers::getDistanceDay($value->status, $value->last_update);
			    							$notif_one_day = "";
				    						if ($days>0 && $all->status=="in"){
				    							$notif_one_day = 'class=notif-oneday';	
				    						}
			    						?>
				    					<tr>			
					    					<td {{$notif_one_day}}>
					    						<a href="/detailorder?orderid={{$value->order_no}}" target="_blank">{{$value->order_no}}
					    						</a>				    							
					    					</td>				    						
				    						<td {{$notif_one_day}}>
				    							{{$value->merchant}}					    						
					    					</td>	
					    					<td {{$notif_one_day}}>
				    							{{$value->oweight}}
				    						</td>
				    						<td {{$notif_one_day}}>
				    							{{$value->rweight}}
				    						</td>
				    					
				    						<td {{$notif_one_day}}>
				    							@if ($all->status=="in")
				    								<div class="in">{{isset($in->last_update) ? $in->last_update : ""}}</div> 
				    							@else
				    								{{isset($in->last_update) ? $in->last_update : ""}}
				    							@endif
				    						</td>
				    						<td {{$notif_one_day}}>
				    							@if ($all->status=="out")
				    								<div class="out">{{isset($out->last_update) ? $out->last_update : ""}}</div>
				    							@else
				    								{{isset($out->last_update) ? $out->last_update : ""}}
				    							@endif
				    						</td>				    						
				    						<td {{$notif_one_day}}>
				    							{{isset($in->remark) ? $in->remark : ""}}
				    						</td>
				    						<td {{$notif_one_day}}>
				    							{{isset($out->remark) ? $out->remark : ""}}
				    						</td>
				    						<td {{$notif_one_day}}>
				    							{{$value->delivery_type}}
				    						</td>
				    						<td {{$notif_one_day}}>
				    							{{isset($in->name) ? $in->name : ""}}
				    						</td>
				    						<td {{$notif_one_day}}>
				    							{{isset($out->name) ? $out->name : ""}}
				    						</td>		
				    						<td {{$notif_one_day}}>
				    							{{$value->courier_name}}
				    						</td>			    						
				    						<td {{$notif_one_day}}>		
				    							<?php
				    								$val_date = "";
				    								if ($all->status=="in"){
				    									if (isset($in->last_update)){
				    										$val_date = $in->last_update;
				    									}
				    								}else if ($all->status=="out"){
				    									if (isset($out->last_update)){
				    										$val_date = $out->last_update;
				    									}
				    								}
				    							?>
				    							<span class="edit" order-id="{{$value->order_no}}" val-id="{{$value->id}}" val-status="all" val-date="{{$val_date}}"> 
				    								<span class="glyphicon glyphicon-pencil"></span>
				    							</span>
				    						</td>
				    					</tr>
			    					@endif
			    				@endforeach				
			    			</tbody>
						</table>
						
						<?php
							$page = ['from' => $from, 'to' => $to];
							if (isset($delivery_type))
								$page['delivery_type'] = $delivery_type;
							if (isset($orderid))
								$page['orderid'] = $orderid;
							if (isset($merchant))
								$page['merchant'] = $merchant;
							if (isset($courier))
								$page['courier'] = $courier;
							if (!empty($delivery))
								$page['delivery'] = $delivery;
							echo $row1->appends($page)->links(); 
						?>
						
					</div>
		    	</div>	    	
	    </div>    
    </div>   
</div>


</html>

 <script>
 var typex="{{$type}}";

   
  </script>