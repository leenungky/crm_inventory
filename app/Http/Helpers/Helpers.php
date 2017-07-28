<?php
	namespace App\Http\Helpers;		
	use \Session; 
	use App\Http\Helpers\WebCurl;
	use App\Http\Helpers\Api;
	use Illuminate\Support\Facades\DB;

	class Helpers{		
		public static function getIdInsert($controll){
			$req = $controll->data["req"]; 
			$tbl_name = 'product';
			$order_no = $req->input("order_id"," ");
			$is_awb_new = 0;
			$order_no = self::get_rw_build($controll->company_id, "product");			
	        $ins = array(
	            "order_no" => $order_no,
	            "company_id" => $controll->company_id,
	            "phone" => $req->input("phone"," "),
	            "recipient_name" => $req->input("nama",""),               
	            "email" => $req->input("email"," "),
	            "merchant_id" => $req->input("merchant"," "),	                        
	            "origin" => $req->input("origin_address",""),	            
	            "dest" => $req->input("dest_address",""),	            	            
                "panjang" => $req->input("panjang", "0"),
                "lebar" => $req->input("lebar", "0"),
                "tinggi" => $req->input("tinggi", "0"),
                "weight" => $req->input("weight", "0"),
                "oweight" => $req->input("oweight", "0"),
                "rweight" => $req->input("rweight", "0"),	            
	            "created_at" =>date("Y-m-d H:i:s")
	            );

	       	$ins_member = $ins;
	        $isexist = DB::table("inventory")
            	->where("order_no", $ins["order_no"])
            	->whereNull("deleted_at")
            	->where("company_id", $controll->company_id)
            	->first();                        	
            if (isset($isexist)){            	            	
            	return null;
            }else{                   	
            	$ins["resi_no"] = $req->input("resi_no", "");
            	$id  = DB::table("inventory")->insertGetId($ins);        
            	$count = DB::table("product")->where("order_no", $ins["order_no"])->count();
	            	if ($count==0){
	            		 $insPickup = array(
				        	"order_no" =>$ins["order_no"],
				        	"company_id" => $controll->company_id,
				        	"merchant_id" => $req->input("merchant"," "),
				        	"customer_phone" => $req->input("phone"," "),
				        	"customer_email" => $req->input("email"," "),
				        	"weight" => $req->input("weight","0"),
				        	"origin" => $req->input("origin_address",""),
				        	"destination" => $req->input("dest_address",""),				        	
				        	"created_at" => date("Y-m-d H:i:s")
		        		);
	            		DB::table("product")->insert($insPickup);
	            	}            	
            	return array("id" => $id, "is_awb_new" => $is_awb_new, "order_no" => $ins["order_no"]);            	
            } 
	        
	    }

	    public static function insertInvetoryHistory($id, $controll, $arr_is_status){	    	
	    	$user = $controll->data["user"];
	    	$req  = $controll->data["req"];
	    	$company_id = $controll->company_id;
	    	 $status = $req->input("type");	    	
	    	// $delivery_type = $req->input("del_type", "");
	    	// if (strtolower($delivery_type)=="pilih"){	    			    		
	    	if ($arr_is_status["is_return"]){
	    		$delivery_type = "return";
	    	}else if ($arr_is_status["is_delivery"]){
	    		$delivery_type = "delivery";
	    	}else{
	    		$delivery_type = "";
	    	}
	    	
	    	$ins_history = array(                
                "id_inv" => $id,
                "company_id" => $company_id,
                "order_no" => $req->input("orderNo",""),
                "inventory_courier_id" => $req->session()->get("courier", ""),                               
                "status" => $status,
                "remark" => $req->input("remark" , ""),
                "logistic_name" => $user->first_name." ".$user->last_name,
                "delivery_type" => $delivery_type,
                "last_update" => date("Y-m-d H:i:s")
            );

            $isexist = DB::table("inventory_history")
            	->where("order_no", $ins_history["order_no"])
            	->where("status", $status)
            	->where("last_update",">", date("Y-m-d H:i").":00")
            	->whereNull("deleted_at")
            	->first();            
            if (isset($isexist)){
            	die();
            }else{
            	DB::table("inventory_history")->insert($ins_history);            	
            }
	    }

	    public static function insertInvetory($data, $req){
 			$data["isrounded"] 	= $req->input("isrounded", "0");
            $data["panjang"] 	= $req->input("panjang", "0");
            $data["lebar"] 		= $req->input("lebar", "0");
            $data["tinggi"]	 	= $req->input("tinggi", "0");            
	    	$id = DB::table("inventory")->insertGetId($data);
	    	return $id;
	    }

	    public static function get_inventory_table($inventory, $type){
	    	if ($type == "product"){
	    		return self::get_inventory_product($inventory);	    			    	
	    	}
	    }

	    

	    public static function get_inventory_product($inventory){	    	
	    	$data = array(
	    		"company_id"		=> $inventory->company_id,
	    		"order_no" 			=> $inventory->order_no,
	    		"merchant_id" 		=> $inventory->merchant_id,
	    		"phone" 			=> $inventory->customer_phone, 
	    		"email" 			=> $inventory->customer_email, 
	    		"origin" 			=> $inventory->origin,	    		
	    		"dest" 				=> $inventory->destination,	    		
	    		'recipient_name' 	=> $inventory->customer_name,	    		
	    		"created_at" 		=> date('Y-m-d H:i:s')
	    		);
	    	return $data;
	    }

	    public static function get_inventory_merchant_pickup($inventory){
	    	$data = array(
	    		"order_no" 			=> $inventory->order_number,
	    		"merchant_name" 	=> $inventory->merchant_name,
	    		"phone" 			=> $inventory->customer_phone, 
	    		"email"	 		=> $inventory->customer_email, 
	    		"origin" 			=> $inventory->pickup_location, 
	    		"address_type" 		=> "LOKER",
	    		"dest" 				=> $inventory->popbox_location,	    		
	    		"tbl_name" 			=> 'tb_merchant_pickup',
	    		"recipient_name" 	=> "-",
	    		"oweight"			=> $inventory->weight,
	    		"rweight"			=> $inventory->weight,
	    		"upload_date" 		=> date('Y-m-d H:i:s')
	    		);
	    	return $data;
	    }

	    public static function get_inventory_merchant_return($inventory){
	    	$merchant_return = DB::table("tb_merchant_return")
	    		->where("merchant_name", $inventory->merchant_name)
	    		->first();
	    	$dest = $inventory->merchant_name;
	    	if (isset($merchant_return)){
	    		if (isset($merchant_return->seller_address)){
	    			$dest = $merchant_return->seller_address;	
	    		}	    		
	    	}
	    	$data = array(
	    		"order_no" 			=> $inventory->tracking_no,
	    		"merchant_name" 	=> $inventory->merchant_name,
	    		"phone" 			=> "", 
	    		"email" 			=> "", 
	    		"origin" 			=> $inventory->locker_name, 
	    		"address_type" 		=> "ALAMAT",
	    		"dest" 				=> $dest,	    		
	    		"tbl_name" 			=> 'locker_activities_return',
	    		"recipient_name" 	=> $inventory->merchant_name,
	    		"upload_date" 		=> date('Y-m-d H:i:s')
	    		);
	    	return $data;
	    }

	     public static function get_inventory_order($inventory){	
	    	$data = array(
	    		"order_no" 			=> $inventory->invoice_id,
	    		"merchant_name" 	=> "PopBox Asia",
	    		"phone" 			=> $inventory->phone,
	    		"email" 			=> $inventory->email,
	    		"origin" 			=> "PopBox Asia",
	    		"dest" 				=> $inventory->address,
	    		"address_type" 		=> "LOKER",
	    		"tbl_name" 			=> 'orders',
	    		"recipient_name" 	=> $inventory->customer_name,
	    		"upload_date" 		=> date('Y-m-d H:i:s')
	    		);
	    	return $data;
	    }	    

	    public static function get_inventory_service($inventory){
	    	$merchant = DB::table("companies")->where("prefix", $inventory->prefix)->first();
	    	$data = array(
	    		"order_no" 			=> $inventory->invoice_id,
	    		"merchant_name" 	=> $merchant->name,	    		
	    		"email" 			=> $inventory->cust_email,
	    		"origin" 			=> "",	    		
	    		"tbl_name" 			=> 'tb_merchant_service',	    		
	    		"upload_date" 		=> date('Y-m-d H:i:s')
	    	);
	    	if ( $inventory->status == "COURIER_TAKEN" && $inventory->merchant_status == "PROCESS COMPLETED" ){
	    		$data["dest"] = $inventory->locker;
	    		$data["phone"] = $inventory->cust_phone;
	    		$data["recipient_name"] = $inventory->cust_name;
	    		$data["address_type"] = "LOKER";
	    	}else{
	    		$data["dest"] = $merchant->name;
	    		$data["recipient_name"] = $merchant->name;
	    		$data["phone"] = "-";
	    		$data["address_type"] = "ADDRESS";
	    	}
	    	return $data;
	    }

	     public static function get_rw_build($company_id, $tablename) {
	    	$pref = date("ymd");		
	    	$sql = "SELECT count(*) as total from ".$tablename."  where SUBSTRING(order_no,1,6)='".$pref."' AND company_id=".$company_id; 
			$countdb = DB::select($sql);	
			$prefix = "000".($countdb[0]->total+1);
			$prefix = substr($prefix,-4);		
			return date("ymd").$prefix;
		}

		public static function get_rw_build_transaction() {
	    	$pref = date("ymd");		
	    	$sql = "SELECT count(*) as total from inventory_transaction  where SUBSTRING(order_no,4,6)='".$pref."'";	
			$countdb = DB::select($sql);	
			$prefix = "000".($countdb[0]->total+1);
			$prefix = substr($prefix,-4);		
			return "PAA".date("ymd").$prefix;
		}

		public static function generateRandomString($length = 4) {
			  $characters = '0123456789';
			  $randomString = '';
			  for ($i = 0; $i < $length; $i++) {
			    $randomString .= $characters[rand(0, strlen($characters) - 1)];
			  }			  
			  return $randomString;
		}


		public static function find_courier($name){
			$courier = DB::table("inventory_courier")->select("id")->where("name", $name)->first();
			if (isset($courier)){				
				return $courier->id;
			}else{
				$ins = array("company_id" => 1, "name"=> $name, "created_at" => date("Y-m-d h:i:s"));
				$id = DB::table("inventory_courier")->insertGetId($ins);
				return $id;
			}
		}

		
	}
?>