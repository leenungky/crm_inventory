<!DOCTYPE html>
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
                <form method="post" action="/user/register" class="formsubmit">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">                                  
                    <div class="form-group">
                        <label for="email">Company Name/ Personal name</label>
                         <input type="text" class="form-control" id="name" name="name" placeholder="input company name" value="{{ old('name') }}" required>
                    </div>                  
                    <div class="form-group">
                        <label for="email">Phone</label>
                         <input type="text" class="form-control" id="phone" name="phone" placeholder="input phone" value="{{ old('phone') }}" required>
                    </div>                  
                    <div class="form-group">
                        <label for="email">Email</label>
                         <input type="text" class="form-control" id="email" name="email" placeholder="input email" value="{{ old('email') }}" required>
                    </div>                                      
                    <div class="form-group">
                        <label for="email">Address</label>
                         <textarea class="form-control" name="address" placeholder="input address" required>{{ old('address') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="email">Password</label>
                         <input type="text" class="form-control" id="password" name="password" placeholder="input password" value="{{ old('password') }}" required>
                    </div>                  
                    <div class="form-group">
                        <label for="email">Comfirm Password</label>
                         <input type="text" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="input comfirm password" value="{{ old('password_confirmation') }}" required>
                    </div>                  
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <a href="/" class="btn btn-primary">Cancel</a>
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
        $("select[name='role']").change(function(){
            var role = $("select[name='role'] option:selected").text();
            if (role=="staff"){
                $(".agent-user").show();
                $("#agent" ).attr( "required", "true" );
            }else{
                $(".agent-user").hide();                
                $("#agent").val($("#agent option:first").val());                
                $("#agent" ).attr( "required", "false" );
            }
        })
    });
</script>