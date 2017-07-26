<!DOCTYPE html>
<html> 
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
		@if (count($errors))     
			<div class="row">				
				<div class="col-md-12 alert alert-danger">		
				    <ul>
				        @foreach($errors->all() as $error) 		            				            
				            <li>{{$error}}</li>
				        @endforeach 
				    </ul>
			    </div>
		    </div>
		@endif 
		<br/>
		<div class="row">				
			<div class="col-md-12">		
				<form method="post" action="/product/update/{{$product->id}}" class="formsubmit">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">					
					<div class="form-group">
					    <label for="email">Merchant * :</label>
						 <select class="form-control" name="merchant_id" required>
						 	<option>Pilih Merchant</option>
						 	@foreach($merchant as $key=>$value)
						 		<option value="{{$value->id}}">{{$value->name}}</option>
						 	@endforeach						 	
						 </select>
					</div>				
					<div class="form-group">
					    <label for="pwd">Customer Name * :</label>
					    <input type="text" name="customer_name" class="form-control"  value="{{$product->customer_name}}" required>
					</div>
					<div class="form-group">
					    <label for="pwd">Customer Phone * :</label>
					    <input type="text" name="customer_phone" class="form-control"  value="{{$product->customer_phone}}" required>
					</div>					
					<div class="form-group">
					    <label for="pwd">Customer Email :</label>
					    <input type="text" name="customer_email" class="form-control"  value="{{$product->customer_email}}">
					</div>
					<div class="form-group">
					    <label for="pwd">Harga * :</label>
					    <input type="text" name="price" class="form-control" value="{{$product->price}}" required>
					</div>
					<div class="form-group">
					    <label for="pwd">Berat * :</label>
					    <input type="text" name="weight" class="form-control" value="{{$product->weight}}" required>
					</div>
					<div class="form-group">
					    <label for="pwd">Origin :</label>
					    <textarea class="form-control" name="origin">{{$product->origin}}</textarea> 
					</div>
					<div class="form-group">
					    <label for="pwd">Destination :</label>
					    <textarea class="form-control" name="destination">{{$product->destination}}</textarea> 
					</div>
					<div class="form-group">
					    <label for="pwd">Detail Items :</label>
					    <textarea class="form-control" name="detail_items">{{old('detail_items')}}</textarea> 
					</div>					
					
					<button type="submit" class="btn btn-primary">Submit</button>
					<a href="/product/list" class="btn btn-primary">Cancel</a>
				</form>
			</div>
		</div>
	</div>	    	

</div>
</body>
</html>

<script type="text/javascript">
	$(document).ready(function(){	
		$( "input[name=name]" ).focus();
	});
</script>