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
	    	<div class="col-md-6">
		    	<div class="row">
		    		@if ($type=="in")
			    		<div class="col-md-9">
			    			<div class="input-group">
			    				<input type="hidden" name="typeinv" value="{{$type}}"/>
						      	<input type="text" class="form-control" name="filter" placeholder="Search for..." onkeypress="onEnter(event)" >
						      	<span class="input-group-btn">
						      		<button type="button" class="btn icon-filter" aria-label="Help" tabindex="2">
						      			<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
						      		</button>
						      	</span>
						    </div>			    
			    		</div>	    		
			    		<div class="col-md-2">
			    			<input type="submit" class="btn btn-danger btn-rwb" value="Single AWB"/><br/>
			    			<!-- <button class="btn btn-danger btn-rwb">Single AWB</button><br/> -->
			    			<input type="submit" class="btn btn-danger multi-btn-rwb" data-toggle="modal" data-target="#upload-awb" value="Multiple AWB"/>
			    			<!-- <button class="btn btn-danger multi-btn-rwb" data-toggle="modal" data-target="#upload-awb">Multiple AWB</button> -->
			    		</div>
		    		@else
		    			<div class="col-md-12">
			    			<div class="input-group">
			    				<input type="hidden" name="typeinv" value="{{$type}}"/>
						      	<input type="text" class="form-control" name="filter" placeholder="Search for..." onkeypress="onEnter(event)">
						      	<span class="input-group-btn">
						      		<button type="button" class="btn icon-filter" aria-label="Help">
						      			<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
						      		</button>
						      	</span>					      	
						    </div>					    
			    		</div>		  
		    		@endif
		    	</div>
		    </div>
		    <div class="col-md-6">
		    	<div class="status-barang">Status barang <strong>{{$type}}</strong></div>
	    	</div>	    	
	    </div>    
	    <div class="row">
		    	<div class="col-md-12">
		    		<div class="alert alert-warning" role="alert">
					  	<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
					  	<span class="sr-only">Error:</span>
					  	<span id="txt-msg"></span>
					  	<div class="close-alert">Close</div>
					</div>
		    	</div>
		    </div>	    	    
	    
		    <div class="row">
		    	<div class="col-md-6">	    
		    		@if ($type!="history")		
		    		<div class="box-input list-record">
		    			<div class="row row-input">
			    			<div class="col-md-3">
			    				Remark
			    			</div>
			    			<div class="col-md-9">
			    				<textarea rows="3" class="form-control" name="remark"></textarea>
			    			</div>
			    		</div>
		    			
		    			<div class="row row-input">
			    			<div class="col-md-3">
			    				Delivery type
			    			</div>
			    			<div class="col-md-9">
			    				<select class="form-control" name="delivery_type" id="delivery_type" tabindex="1">
			    					<option value="">Pilih</option>
			    					<option value="delivery">delivery</option>
			    					<option value="return">return</option>			    					
		    					</select>
			    			</div>
			    		</div>

			    		<div class="row row-input">
			    			<div class="col-md-3">
			    				Courier company
			    			</div>
			    			<div class="col-md-3">
			    				<select class="form-control" name="courier_company" tabindex="3">
			    					<option value="">Pilih Courier</option>
			    					<option value="internal">Internal</option>
			    					<option value="3PL">3PL</option>			    					
			    				</select>
			    			</div>
			    			<div class="col-md-3">
			    				Courier name
			    			</div>
			    			<div class="col-md-3">
			    				<select class="form-control" name="courier" tabindex="4">
			    				<?php
			    					$courselected = "";
			    					if (isset($couriers)){
			    						 foreach ($couriers as $key => $value){ 
			    						 	if ($courier==$value->id){
			    						 		$courselected = "selected";
			    						 	}
			    				?>
			    							<option value="{{$value->id}}" {{$courselected}}>{{$value->name}}</option>
			    				<?php
			    						 }			    										    						
			    					}
			    				?>
			    				</select>			    				
			    			</div>			    
			    		</div>		
			    		<div class="row row-input">			    			
			    			<div class="col-md-3">
			    				Original Weight
			    			</div>
			    			<div class="col-md-3">
			    				<input type="text" name="oweight" class="form-control" value="{{$oweight}}" />
			    			</div>			    		
			    			<div class="col-md-3">
			    				Rounded Weight
			    			</div>
			    			<div class="col-md-3">
			    				<input type="text" name="rweight" class="form-control" value="{{$rweight}}" />
			    			</div>
			    		</div>
	
		    		</div>

		    		<div class="box-input list-record">
		    			<div class="row row-input">

			    			<div class="col-md-2">
			    				Panjang
			    			</div>
			    			<div class="col-md-2">
			    				<input type="text" name="panjang" class="form-control" value="{{$panjang}}" />
			    			</div>
			    			<div class="col-md-2">
			    				Lebar
			    			</div>
			    			<div class="col-md-2">
			    				<input type="text" name="lebar" class="form-control" value="{{$lebar}}" />
			    			</div>
			    		
			    			<div class="col-md-2">
			    				Tinggi
			    			</div>
			    			<div class="col-md-2">
			    				<input type="text" name="tinggi" class="form-control" value="{{$tinggi}}" />
			    			</div>			    			
			    		
		    			</div>

			    		<div class="row row-input">
			    			<div class="col-md-2">
			    				Hitung
			    			</div>
			    			<div class="col-md-7">
			    				<input type="checkbox" name="rounded" value="1"/> (Panjang X lebar X Tinggi) : 6000
			    			</div>
			    			<div class="col-md-3">
			    				<input type="text" name="weight" class="form-control" value="{{$weight}}" />
			    			</div>
			    		</div>			    	
		    		</div>
		    		@endif

		    		 {{ csrf_field() }}
		    		<div class="box-input border-input">
		    			<div class="row row-input">
			    			<div class="col-md-12">
			    				<div class="alert alert-info message" role="alert">
		  							<strong>Pemberitahuan!</strong>Data tidak ditemukan silahkan lengkapi form dibawah!
								</div>
			    			</div>
			    		</div>
			    		<div class="row row-input">
			    			<div class="col-md-4">
			    				Order ID *
			    			</div>
			    			<div class="col-md-8">
			    				<input type="text" name="order_id" class="form-control" value="{{$orderNo}}" placeholder="Search for..." readonly="true">
			    				<input type="hidden" name="is_generate" class="form-control" value="0" readonly="true">
			    			</div>
			    		</div>
			    		<div class="row row-input">
			    			<div class="col-md-4">
			    				No Resi 3PL
			    			</div>
			    			<div class="col-md-8">
			    				<input type="text" name="resi_no" class="form-control" value="{{$resi_no}}" placeholder="Search for..." readonly="true">		
			    			</div>
			    		</div>
			    		<div class="row row-input">
			    			<div class="col-md-4">
			    				Name
			    			</div>
			    			<div class="col-md-8">
			    				<input type="text" name="nama" class="form-control" value="{{$nama}}" placeholder="Search for..." readonly="true">		
			    			</div>
			    		</div>
			    		<div class="row row-input">
			    			<div class="col-md-4">
			    				Merchant *
			    			</div>

			    			<div class="col-md-8">
			    				<select class="form-control" name="merchant_id" required="true"> 
			    				<option>Pilih Merchant</option>
					    		@foreach($merchants as $key => $value)
					    			<option value="{{$value->id}}">{{$value->name}}</option>
					    		@endforeach
					    		</select>
			    			</div>
			    		</div>
			    		<div class="row row-input">
			    			<div class="col-md-4">
			    				Phone *
			    			</div>
			    			<div class="col-md-8">
			    				<input type="text" name="phone" class="form-control" placeholder="Search for..." readonly="true">
			    			</div>
			    		</div>
			    		
			    		<div class="row row-input origin-address">
			    			<div class="col-md-4">			    				
			    				Origin
			    			</div>
			    			<div class="col-md-8">			    				
			    				<textarea class="form-control" name="origin_address" readonly="true"></textarea>
			    			</div>
			    		</div>
			    		<div class="row row-input origin-loker">
			    			<div class="col-md-4">		    				
			    			</div>
			    			<div class="col-md-8">
			    				<input id="origin_loker" name="origin_loker" class="form-control" readonly="true">			    
			    			</div>
			    		</div>		    		
			    		
			    		<div class="row row-input">
			    			<div class="col-md-4">
			    				Destination		    				
			    			</div>
			    			<div class="col-md-8">			    				
			    				<textarea class="form-control" name="dest_address" readonly="true"></textarea>
			    			</div>
			    		</div>
			    		
			    		<div class="row row-input">
			    			<div class="col-md-4">
			    				Email *
			    			</div>
			    			<div class="col-md-8">
			    				<input type="text" class="form-control" name="email" placeholder="Search for..." readonly="true">
			    			</div>
			    		</div>

			    		<div class="row row-input">
			    			<div class="col-md-4">	    						    			
			    			</div>
			    			<div class="col-md-8">
			    				<div class="new">
			    					<input type="submit" class="left btn-save" name="type_save" value="SAVE" />
			    					<input type="submit" class="left btn-print" name="btn-print" value="SAVE & PRINT" />
			    					<!-- <div class="left btn-save" val="save">SAVE</div> -->
			    					<!-- <div class="left btn-print" val="save_print">SAVE & PRINT</div>	 -->
			    					<input type="hidden" name="type_save" value=""/>
			    				</div>
			    				<div class="read">			    					
			    					<input type="submit" class="left btn-print-read" val="save_print" value="PRINT">
			    					<!-- <div class="left btn-print-read" val="save_print">PRINT</div>	 -->
			    					<input type="hidden" name="type_save" value=""/>
			    				</div>		
			    			</div>
			    		</div>
			    	</div>		    	
		    	</div>
		    	<div class="col-md-6">
			    	<div id="table-div"></div>					
		    	</div>	    	
	    </div>
    </div>   
</div>

<div class="modal fade"  id="upload-awb" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Upload data</h4>
      </div>
      <div class="modal-body">
        <form action="uploadawb" method="post" enctype="multipart/form-data">
       		Silahkan download template <a href="/upload/data.xlsx">disini</a><br/><br/>
		    Select image to upload:
		    <input type="file" name="fileupload" id="fileupload"><br/>
		    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
		    <input type="submit" class="btn" value="Upload" name="submit">&nbsp;<button type="button" class="btn" data-dismiss="modal">Close</button>
		</form>
      </div>      
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="row" id="printableArea" style="display: none;">
	<div class="left" style="width: 230px; font-size: 8px; margin-left: 10px">
		<div><br/>
			<div style="font-size: 11px;font-weight: bold;">Tujuan : <span id="label-dest"></span></div><br/>
			AWB : <br/>
			<span style="font-size: 11px;font-weight: bold;" class="spancode"></span><br/><br/>    				
			Nama :<br/>
			<span id="qr-name" style="font-weight: bold;"></span><br/><br/>
			No Hp Penerima :<br/>
			<span style="font-size: 11px;font-weight: bold;" id="qr-phone"></span><br/>
			Weight : <span id="qr-weight" style="font-size: 11px;font-weight: bold;"></span><br/><br/>
			Tujuan : <br/>
			<div id="qr-full-address" style="font-size: 8px;;font-weight: bold;">
			</div>
		</div>
    </div>
    <div class="left" style="text-align: center;width: 100px;">
    			<br/>
    			<div>    				
    				<span style="font-size: 19px;font-weight: bold;">PopBox</span><br/>
    				<span style="font-size: 11px;">Scan barcode ini :</span>
    			</div>
    			<div style="width: 100px;padding: 10px 0px;">
				   	<img src="" alt="barcode" id="qrcode" />				   	
				</div>				
    		</div>
    		<div style="clear: both;"></div>    		
    		<div class="left" style="width: 350px; margin-left: 10px">
    			--------------------------------------------------------------------------
    		</div>
    		<br/>
    		<div style="clear: both;"></div>
    		<div class="left full-desc" style="width: 350px;font-size: 9px; margin-left: 10px">    				
					1. Datang ke loker Popbox<br/>
					2. Pilih menu MELETAKKAN BARANG<br/>
					3. Masukkan Username dan Password<br/>
					4. Pilih tombol SIMPAN<br/>
					5. Scan barcode / masukkan manual<br/>
					6. Pilih ukuran loker dan masukkan barang<br/>
    		</div>
    		<div style="clear: both;"></div><br/>
    		<div style="font-size: 12px;font-weight: bold;margin-left: 10px">
    			CS tlp. 021 2902 2537/8 atau email: info@popbox.asia
    		</div>
    	</div>
    
</body>
</html>

 <script>
 
	var availableOrigins = [
	  	@foreach ($origin_address as $key => $value)
			"{{trim($value->origin)}}",
		@endforeach 
	];  
	var availabledest = [
	  	@foreach ($dest_address as $key => $value)
			"{{trim($value->dest)}}",
		@endforeach
	];

	var availablemerchant = [
	  	@foreach ($merchants as $key => $value)
			"{{trim($value->name)}}",
		@endforeach
	];
	var typex="{{$type}}";

	$(document).ready(function(){ 	  
		$("input[name='filter']").focus(); 
 	

    	$("input[name='merchant-name']").autocomplete({
      		source: availablemerchant
    	});
    	

    	$("textarea[name='origin_address']" ).autocomplete({
      		source: availableOrigins
    	});

    	$( "textarea[name='dest_address']" ).autocomplete({
      		source: availabledest
    	});
	
 		@if (Session::has("message"))
 			$("#txt-msg").html("{!!Session::get("message")!!}");
 			$(".alert-warning").show(); 		
 		@endif


 	});
 

  $( function() {
  	if ($.urlParam('page') !=null){
  		$("#table-div").load(base_url + "/readHistory/{{$type}}?page=" + $.urlParam('page'));
  	}else{
  		$("#table-div").load(base_url + "/readHistory/{{$type}}");
  	}
  } );

  $(document).keydown(function(e) {
	if (e.keyCode == 27){
    	$("input[name='filter']").val("");
    	$("input[name='filter']").focus(); 
    }else if ($(e.target).closest("input")[0]) {
        return;
    }else if ($(e.target).closest("textarea")[0]) {
        return;    
    }else{
    	 $("input[name='filter']").focus(); 	
    }
    
});


  	$.urlParam = function(name){
	    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
	    if (results==null){
	       return null;
	    }
	    else{
	       return results[1] || 0;
	    }
	}

		
 </script>


 <script>
$(function() {
var availableTags = ["jQuery.com", "jQueryUI.com", "jQueryMobile.com", "jQueryScript.net", "jQuery", "Free jQuery Plugins"]; // array of autocomplete words
var minWordLength = 2;
function split(val) {
return val.split(' ');
}

function extractLast(term) {
	return split(term).pop();
}
$("input[name='origin_address']").bind("keydown", function(event) {
	if (event.keyCode === $.ui.keyCode.TAB && $(this).data("ui-autocomplete").menu.active) {
		event.preventDefault();
	}
	}).autocomplete({
		minLength: minWordLength,
		source: function(request, response) {
// delegate back to autocomplete, but extract the last term
			var term = extractLast(request.term);
			if(term.length >= minWordLength){
				response($.ui.autocomplete.filter( availableTags, term ));
			}
		},
		focus: function() {
		// prevent value inserted on focus
			return false;
		},
		select: function(event, ui) {
			var terms = split(this.value);
			// remove the current input
			terms.pop();
			// add the selected item
			terms.push(ui.item.value);
			// add placeholder to get the comma-and-space at the end
			terms.push("");
			this.value = terms.join(" ");
			return false;
		}
	});
});