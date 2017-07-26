 <div id="contents">
    <div class="container container-fluid">             
      
        
            <div class="row">
                <div class="col-md-12">                          
                    <form action="deleted" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="id" value="{{$id}}">
                        <input type="hidden" name="order_no" value="{{$order_no}}">
                        <input type="hidden" name="status" value="{{$status}}">                                     
                             <div class="row row-input">                                
                                <div class="col-md-12">
                                    <label>
                                        <input type="radio" name="rd_delete" value="one" checked="checked"/>Hapus Satu history ini?
                                    </label>
                                </div>
                            </div>       
                            <div class="row row-input">                                
                                <div class="col-md-12">
                                    <label>
                                        <input type="radio" name="rd_delete" value="all"/>Hapus Semua history?
                                    </label>
                                </div>
                            </div>                            
                                                
                             <div class="row row-input">       
                                <div class="col-md-12" style="text-align: center;">
                                  <input type="submit" class="btn btn-primary" value="hapus">        
                                </div>
                            </div>
                        
                           
                    </form>
                    </div>   
            </div>
               
        </div>
    </div>   


