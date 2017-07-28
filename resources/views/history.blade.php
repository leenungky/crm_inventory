<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
     
    
</head>
<body>
     
 	   
<div class="row">
	<div class="col-md-12">
		<div class="box-input list-record">
			<div class="row record">
		    	<div class="col-md-6">
		    		<span id="total">{{$count}}</span> record	    	
		    	</div>
		    	<div class="col-md-6 download">
		    		<a href="/download?type={{$status}}" id="download">Download</a>
		    	</div>			
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
			    		Date
			    	</th>
			    	<th>
			    		Logistic Team
			    	</th>
			    	<th>
			    		Courier
			    	</th>
					<th>
			    		Action
			    	</th>					
					
			    			</thead>  					
			    			<tbody>	
			    				@foreach ($history as $key => $value)
			    					<tr>
			    						<td>
			    							{{$value->order_no}}
			    						</td>
										<td>
			    							{{$value->merchant_name}}
			    						</td>
			    						<td>
			    							{{$value->created_at}}
			    						</td>
			    						<td>
			    							{{$value->logistic_name}}
			    						</td>
			    						<td>
			    							{{$value->courier}}
			    						</td>	
										<td>
			    							<span class="edit" order-id="{{$value->order_no}}" val-id="{{$value->id}}" val-status="{{$value->status}}" val-date="{{$value->created_at}}"> 
			    								<span class="glyphicon glyphicon-pencil"></span>
			    							</span> 
			    						</td>
										
			    					</tr>
			    				@endforeach
			    			</tbody>
						</table>
					</div>
		    	</div>	    	
		    	<?php echo $history->links(); ?>
	    </div>
    

</body>
</html>