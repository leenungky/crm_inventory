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
		<br/>
		<div class="row">	
			<form action="/product/list" method="get">
				<div class="col-md-2">
					Nama<br/>
					<input type="text" name="order_no" class="form-control" value="{{isset($filter["order_no"]) ? $filter["order_no"] : ""}}">
				</div>
				
				<div class="col-md-2">
					<br/>
					<input type="submit" value="find" class="btn">
				</div>
			</form>
		</div>
		<br/>
		<div class="row">	
			<div class="col-md-12">
			<a href="/product/new">Create</a>
			</div>
		</div>
		<br/>
		<div class="row">	
			<div class="col-md-12">
				<table class="table">
					<?php 
						$str_parameter = "";
						if (isset($order_by)){
							if ($order_by=="asc"){
								$str_parameter = "&order_by=desc";
							}
							else if ($order_by=="desc"){
								$str_parameter = "&order_by=asc";
							}	
						}
					?>
					<thead>
						<th><a href="/product/list?sort=order_no{{$str_parameter}}">Order / Awb</a>
			    			{!!isset($arrow_nama) ? $arrow_nama : ""!!}
			    		</th>
			    		<th>
			    			<a href="/product/list?sort=customer_name{{$str_parameter}}">Merchant</a>			    			
			    		</th>			    								
			    		<th>
			    			<a href="/product/list?sort=address{{$str_parameter}}">Customer Name</a>			    			
			    		</th>			    								
			    		<th>
			    			<a href="/product/list?sort=customer_phone{{$str_parameter}}">Customer Phone</a>			    			
			    		</th>																	
			    		<th>
			    			<a href="/product/list?sort=destination{{$str_parameter}}">Destination</a>			    			
			    		</th>
			    		<th>
			    			<a href="/product/list?sort=weight{{$str_parameter}}">Weight</a>			    			
			    		</th>	
						<th>Action</th>
					</thead>
					<tbody>
						@foreach ($products as $key => $value)
							<tr>
								<td>{{$value->order_no}}</td>
								<td>{{$value->name}}</td>								
								<td>{{$value->customer_name}}</td>	
								<td>{{$value->customer_phone}}</td>			
								<td>{{$value->destination}}</td>		
								<td>{{$value->weight}}</td>								
								<td>{{$value->created_at}}</td>								
								<td>
									<a href="/product/edit/{{$value->id}}">
										<span class="edit"> 
					    					<span class="glyphicon glyphicon-pencil"></span>
					    				</span>
				    				</a> | 
				    				<a href="/product/delete/{{$value->id}}" class="confirmation">
					    				<span class="delete">
				    						<span class="glyphicon glyphicon-remove"></span>
				    					</span> 
			    					</a>
								</td>
							</tr>																							
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	 </div>	    	
</div>
</body>
</html>