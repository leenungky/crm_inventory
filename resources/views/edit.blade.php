<script type="text/javascript" src="{{ URL::asset('js/custom.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('js/jquery.validate.js') }}"></script>

        <div class="row">                 
            <div class="col-md-12">
              <div class="status-barang"><span class="id-detail">{{$history->order_no}}</span></div>
           </div>                      
        
         </div>
            <div class="row">
                <div class="col-md-12">                          
                    <form action="update" method="post" id="updateform">
                        {{ csrf_field() }}
                        <input type="hidden" name="id" value="{{$history->id}}">
                        <input type="hidden" name="order_no" value="{{$history->order_no}}">
                        <input type="hidden" name="status" value="{{$status}}">
                        <input type="hidden" name="date" value="{{$date}}">
                        <div class="box-input list-record">
                            <div class="row row-input">
                                <div class="col-md-3">
                                    Remark
                                </div>
                                <div class="col-md-9">
                                    <textarea rows="3" class="form-control" name="remark">{{$history->remark}}</textarea>
                                </div>
                            </div>
                            
                            <div class="row row-input">
                                <div class="col-md-3">
                                    Delivery type
                                </div>
                                <div class="col-md-9">
                                    <select class="form-control" name="delivery_type" id="delivery_type" required>
                                        <option value="">Pilih</option>

                                        @if ($history->delivery_type=="delivery")
                                            <option value="delivery" selected>delivery</option>
                                        @else
                                            <option value="delivery">delivery</option>
                                        @endif

                                        @if ($history->delivery_type=="return")
                                            <option value="return" selected>return</option>
                                        @else
                                            <option value="return">return</option>
                                        @endif

                                        @if ($history->delivery_type=="popshop")
                                            <option value="popshop" selected>popshop</option>
                                        @else
                                            <option value="popshop">popshop</option>
                                        @endif

                                        @if ($history->delivery_type=="internal")
                                            <option value="internal" selected>internal</option>
                                        @else
                                            <option value="internal">internal</option>
                                        @endif                                        
                                    </select>
                                </div>
                            </div>

                          <div class="row row-input">
                            <div class="col-md-3">
                                Courier company
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" name="courier_company">                                    
                                    <option value="">Pilih Courier</option>
                                    <option value="internal">Internal</option>
                                    <option value="3PL">3PL</option>    
                                </select>
                            </div>
                            <div class="col-md-3">
                                Courier name
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" name="courier">
                                    @foreach ($couriers as $key => $value)
										@if (isset($courier))
											@if ( $value->id == $courier->id )
												<option value="{{$value->id}}" selected>{{$value->name}}</option>
											@else
												<option value="{{$value->id}}">{{$value->name}}</option>
											@endif
										@else
											<option value="{{$value->id}}">{{$value->name}}</option>
										@endif
                                    @endforeach
                                    
                                </select>                               
                            </div>              
                        </div>                                  
                            <div class="row row-input">                                
                                <div class="col-md-3">
                                    Original weight
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="oweight" class="form-control" value="{{$inventory->oweight}}" required />
                                </div>
                                <div class="col-md-3">
                                        Rounded Weight
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="rweight" class="form-control" value="{{$inventory->rweight}}" />
                                </div>
                            </div>
                        </div>
                        <div class="box-input list-record">
                            <div class="row row-input">
                                <div class="col-md-2">
                                    Pjg
                                </div>
                                <div class="col-md-2">
                                    <input type="text" name="panjang" class="form-control" value="{{$inventory->panjang}}" />
                                </div>

                                <div class="col-md-2">
                                    Lebar
                                </div>
                                <div class="col-md-2">
                                    <input type="text" name="lebar" class="form-control" value="{{$inventory->lebar}}" />
                                </div>
                            
                                <div class="col-md-2">
                                    Tinggi
                                </div>
                                <div class="col-md-2">
                                    <input type="text" name="tinggi" class="form-control" value="{{$inventory->tinggi}}" />
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
                                    <input type="text" name="weight" class="form-control" value="{{$inventory->weight}}" />
                                </div>
                            </div>                        
                        </div>                

                        <div class="box-input border-input">                                                    
                            <div class="row row-input">
                                <div class="col-md-4">
                                    Merchant *
                                </div>
                                <div class="col-md-8">
                                        <select class="form-control" name="merchant" required>
                                            <option value="">Pilih Merchant</option>
                                            @foreach ($merchants as $key => $value)
                                                @if (trim($value->id)==trim($inventory->merchant_id))
                                                    <option value="{{$value->id}}" selected>{{$value->name}}</option>
                                                @else
                                                    <option value="{{$value->id}}">{{$value->name}}</option>
                                                @endif
                                            @endforeach 
                                        </select>                                    
                                </div>
                            </div>
                            <div class="row row-input">
                                <div class="col-md-4">
                                    No Resi 3PL
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="resi_no" class="form-control" placeholder="Search for..." value="{{$inventory->resi_no}}" >                           
                                </div>
                            </div>
                             <div class="row row-input">
                                <div class="col-md-4">
                                    Name
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="nama" class="form-control" placeholder="Search for..." value="{{$inventory->recipient_name}}" required>                           
                                </div>
                            </div>
                            <div class="row row-input">
                                <div class="col-md-4">
                                    Phone *
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="phone" class="form-control" placeholder="Search for..." value="{{$inventory->phone}}" required>                           
                                </div>
                            </div>
                            <div class="row row-input">
                                <div class="col-md-4">
                                    Origin *
                                </div>
                                <div class="col-md-8">
                                    <input type="radio" name="rd_origin" value="address" checked="chekced" />Address
                                    <input type="radio" name="rd_origin" value="loker"/>Loker
                                </div>
                            </div>
                            <div class="row row-input origin-address">
                                <div class="col-md-4">                              
                                </div>
                                <div class="col-md-8">
                                    <textarea class="form-control" name="origin_address">{{$inventory->origin}}</textarea>
                                </div>
                            </div>
                            <div class="row row-input origin-loker">
                                <div class="col-md-4">                          
                                </div>
                                <div class="col-md-8">
                                    <input id="origin_loker" name="origin_loker" class="form-control" >              
                                </div>
                            </div>                  
                            <div class="row row-input">
                                <div class="col-md-4">
                                    Destination *
                                </div
>                                <div class="col-md-8">
                                    <input type="radio" name="rd_dest" value="address" checked="checked" />Address
                                    <input type="radio" name="rd_dest" value="loker" />Loker
                                </div>
                            </div>
                            <div class="row row-input dest-address">
                                <div class="col-md-4">                          
                                </div>
                                <div class="col-md-8">
                                    <textarea class="form-control" name="dest_address">{{$inventory->dest}}</textarea>
                                </div>
                            </div>
                            <div class="row row-input dest-loker">
                                <div class="col-md-4">                              
                                </div>
                                <div class="col-md-8">
                                    <input id="dest_loker" name="dest_loker" class="form-control">            
                                </div>
                            </div>                  

                            <div class="row row-input">
                                <div class="col-md-4">
                                    Email
                                </div>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="email" value="{{$inventory->email}}" placeholder="Search for...">
                                </div>
                            </div>

                            <div class="row row-input">
                                <div class="col-md-4">                  
                                </div>                                
                            </div>
                        </div>   
                        
                            <div class="row row-input">       
                                <div class="col-md-12" style="text-align: center;">
                                  <input type="submit" class="btn btn-primary" value="update">        
                                </div>
                            </div>
                        
                    </form>
            </div>
               
        </div>
    
<script type="text/javascript">
    
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
   
   
    $(document).ready(function(){     
        $( "#dest_loker" ).autocomplete({
            source: availableLoker
        });

        $( "#origin_loker" ).autocomplete({
            source: availableLoker
        });

    
        $( "#dest_loker" ).autocomplete({
            source: availableLoker
        });
        

        $( "textarea[name='origin_address']" ).autocomplete({
            source: availableOrigins
        });

        $( "textarea[name='dest_address']" ).autocomplete({
            source: availabledest
        });
    
        @if (Session::has("message"))
            $("#txt-msg").html("{{Session::get("message")}}");
            $(".alert-warning").show();         
        @endif
    });
 
    $(document).ready(function(){
        $("#updateform").validate();
    })
</script>
