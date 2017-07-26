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
				            <li>{{str_replace("name","Nama toko",$error)}}</li>
				        @endforeach 
				    </ul>
			    </div>
		    </div>
		@endif 
		<br/>
		<div class="row">				
			<div class="col-md-12">		
				<form method="post" action="/merchant/create" class="formsubmit">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">					
					<div class="form-group">
					    <label for="email">Nama</label>
						 <input type="text" class="form-control" id="name" name="name" placeholder="input nama" value="{{ old('name') }}" required>
					</div>		
					<div class="form-group">
					    <label for="email">Phone</label>
						 <input type="text" class="form-control" id="phone" name="phone" placeholder="input phone" value="{{ old('name') }}" required>
					</div>				
					<div class="form-group">
					    <label for="pwd">Address:</label>
					    <textarea class="form-control" cols="3" name="address" placeholder="input address" required>{{ old('address') }}</textarea>
					</div>					
					<div class="form-group">
					    <label for="pwd">Description:</label>
					    <textarea class="form-control" cols="3" name="description" placeholder="input description">{{ old('description') }}</textarea>
					</div>
					
					<button type="submit" class="btn btn-primary">Submit</button>
					<a href="/merchant/list" class="btn btn-primary">Cancel</a>
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