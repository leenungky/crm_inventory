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
			<form action="/merchant/list" method="get">
				<div class="col-md-2">
					Nama<br/>
					<input type="text" name="name" class="form-control" value="{{isset($filter["name"]) ? $filter["name"] : ""}}">
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
			<a href="/merchant/new">Create</a>
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
						<th><a href="/merchant/list?sort=nama{{$str_parameter}}">Nama</a>
			    			{!!isset($arrow_nama) ? $arrow_nama : ""!!}
			    		</th>
			    		<th>
			    			<a href="/merchant/list?sort=phone{{$str_parameter}}">Phone</a>			    			
			    		</th>			    								
			    		<th>
			    			<a href="/merchant/list?sort=address{{$str_parameter}}">Address</a>			    			
			    		</th>			    								
			    		<th>
			    			<a href="/merchant/list?sort=created{{$str_parameter}}">Created At</a>			    			
			    		</th>																		
						<th>Action</th>
					</thead>
					<tbody>
						@foreach ($merchants as $key => $value)
							<tr>
								<td>{{$value->name}}</td>
								<td>{{$value->phone}}</td>								
								<td>{{$value->address}}</td>								
								<td>{{$value->created_at}}</td>								
								<td>
									<a href="/merchant/edit/{{$value->id}}">
										<span class="edit"> 
					    					<span class="glyphicon glyphicon-pencil"></span>
					    				</span>
				    				</a> | 
				    				<a href="/merchant/delete/{{$value->id}}" class="confirmation">
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