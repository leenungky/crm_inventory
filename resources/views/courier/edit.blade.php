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
			<div class="col-md-12">		
				<form method="post" action="/courier/update/{{$courier->id}}" class="formsubmit">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">					
					<div class="form-group">
					    <label for="email">Nama</label>
						 <input type="text" class="form-control" name="name" value="{{$courier->name}}" placeholder="input nama" required>
					</div>				
					<div class="form-group">
					    <label for="email">Company</label>
						<select class="form-control" name="company">
							<option>Pilih Company</option>
							@if ($courier->company=="internal")
								<option value="internal" selected>Internal</option>
							@else
								<option value="internal">Internal</option>
							@endif
							@if ($courier->company=="3PL")
								<option value="3PL" selected>3PL</option>
							@else
								<option value="3PL">3PL</option>
							@endif							
						</select>
					</div>					
					<div class="form-group">
					    <label for="pwd">Description:</label>
					    <textarea class="form-control" cols="3" name="description" placeholder="input description">{{$courier->description}}</textarea>
					</div>					
					<button type="submit" class="btn">Submit</button>
				</form>
			</div>
		</div>
	 </div>	    	
</div>
</body>
</html>