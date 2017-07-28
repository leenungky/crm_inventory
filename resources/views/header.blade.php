<script type="text/javascript">
    var base_url = "{{config('config.url')}}";
    $(document).ready(function(){
        $(document).idleTimeout({
            inactivity: 3000000, 
            noconfirm: 900000,      
            sessionAlive:900000,
            redirect_url :base_url + "/user/logout"
        });
    });
</script>

<div class="row">
    		<div class="col-md-8">
    			<div class="row row-top-menu">
    				<div class="col-md-2 col-top-menu1">	
    					<!-- <img src="{{URL::asset('img/popbox-logo.png')}}" class="logo"> -->
    				</div>
    				<a href="/inbound"><div class="col-md-2 col-top-menu {{($type=="in") ? 'active' : ''}}">Inbound</div></a>
    				<a href="/outbound"><div class="col-md-2 col-top-menu {{($type=="out") ? 'active' : ''}}">Outbound</div></a>
                    <a href="/allorder"><div class="col-md-2 col-top-menu {{($type=="allorder") ? 'active' : ''}}">All Order</div></a>
                    <a href="/dashboard"><div class="col-md-2 col-top-menu {{($type=="dashboard") ? 'active' : ''}}">Dashboard</div></a>   
                     <div class="col-md-2 col-top-menu1">
                            <div class="dropdown">
                                <a class="dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                        Master Data
                                        <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">

                                    <li>
                                        <a href="/product/list">Barang</a>
                                    </li>                                  
                                    <li role="separator" class="divider"></li>
                                    <li>
                                         <a href="/courier/list">Courier</a>
                                    </li>                                      
                                    <li role="separator" class="divider"></li>
                                    <li>
                                        <a href="/merchant/list">Merchant</a>
                                    </li>                                                          
                                </ul>
                            </div>                      
                        </div>                 
    			</div>
    		</div>
    	<div class="col-md-4">
    	<div class="row">
    		<div class="col-md-11 col-top-menu-user">
    			<div class="dropdown">
					<button class="btn dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
						    {{\Auth::user()->first_name}} {{\Auth::user()->lasts_name}}
						    <span class="caret"></span>
					</button>
					<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">					    
				        <li><a href="user/logout">logout</a></li>
		  		    </ul>
				</div>    					
    		</div>
    		<div class="col-md-1 col-top-menu-user">
    			<img src="{{URL::asset('img/user.png')}}" class="user" />
        	</div>
    	</div>
 	</div>
</div>

@if (isset($type))
    <div class="row">
        <div class="col-md-12">
            <div class="status-barang">{{$type}}</strong></div>
        </div>          
    </div>
    <br/>    
    <div class="line"></div>

     <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>          
        </div>
        <div class="modal-body">
          
        </div>
       
      </div>
    </div>
  </div> 
@endif

