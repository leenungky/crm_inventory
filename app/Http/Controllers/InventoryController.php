<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use PHPExcel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Helpers\Helpers;
use Illuminate\Support\Facades\Input;
use Redirect;
use DNS2D;
use \PHPExcel_IOFactory, \PHPExcel_Style_Fill, \PHPExcel_Cell, \PHPExcel_Cell_DataType, \SiteHelpers;

class InventoryController extends Controller {
    
    public function __construct(Request $req){
        if(!\Auth::check()) {
            Redirect::to('')->send();
        }                
        $this->data["user"] = \Auth::user();
        $this->page = 15;
    }

	public function index(){
        return view('auth.login');
    }

    public function signin(){
    	return view('auth.login');
    }

    public function forgot() {
        return view('auth.forgot'); 
    }

    public function printdata() {        
        return view('print'); 
    }

    //merchant ambil dari table company field name
    public function inbound(Request $req){
        $count = DB::table("tb_merchant_pickup")
            ->where("order_number", $req->input("order_id"," "))
            ->count();
        $data = self::_getInOut($req, "in");                        
        return view('inbound', $data);
    }

    public function outbound(Request $req){
        $data = self::_getInOut($req, "out");                       
        return view('inbound', $data);
    }

    public function edit(Request $req){        
        $order_no = $req->input("order_no");
        $date     = $req->input("date", "");        
        $data["merchants"] = DB::table("tb_merchant_pickup")
                    ->select("merchant_name")
                    ->where("merchant_name","<>","")
                    ->groupBy("merchant_name")
                    ->orderBy("merchant_name", "asc")->get();          
        $data["lokers"] = DB::table("locker_locations")->select("name")->get();
        $data["history"] = DB::table("inventory_history")->where("order_no", $order_no)->where("last_update", $date)->first();
        
        $data["inventory"] = DB::table("inventory")->where("order_no", $order_no)->whereNull("deleted_at")->first();


        $data["origin_address"] = DB::select("select REPLACE(REPLACE(origin, '\n', ''), '\r', ' ') origin from inventory where origin<>'' group by origin order by id desc, origin asc limit 200");
        $data["dest_address"] = DB::select("select REPLACE(REPLACE(dest, '\n', ''), '\r', ' ') dest from inventory where dest<>'' group by dest order by id desc, dest asc limit 200");

        $data["courier_company"] = DB::table("inventory_courier_company")->select("id", "company")->orderBy("company")->get();

        $data["courier"] = DB::table("inventory_courier")->where("id", $data["history"]->inventory_courier_id)->first();        
        $company_courier = "";
        if (isset($data["courier"])){
            $company_courier = $data["courier"]->company_id;
        }
        $data["status"] = $req->input("status", "in");
        $data["date"] = $date;
        $data["couriers"] = DB::table("inventory_courier")->where("company_id", $company_courier)->get();
        return view("edit", $data);
    }

    public function update(Request $req){                        
        $id = $req->input("id","");
        $order_no = $req->input("order_no","");
        $rd_origin = $req->input("rd_origin","");
        $rd_dest = $req->input("rd_dest","");
        $status = $req->input("status", "");
        $date = $req->input("date", "");
        $arrHistory = array(
            "inventory_courier_id" => $req->input("courier",""),            
            "remark" => $req->input("remark",""),
            "delivery_type" => $req->input("delivery_type","")
        );
        $arrInventory = array(
            "merchant_name" => $req->input("merchant",""),
            "resi_no" =>  $req->input("resi_no",""),
            "phone" => $req->input("phone",""),
            "email" => $req->input("email",""),
            "recipient_name" => $req->input("nama",""),
            "panjang" => $req->input("panjang","0"),
            "lebar" => $req->input("lebar","0"),
            "tinggi" => $req->input("tinggi","0"),
            "weight" => $req->input("weight","0"),            
            "oweight" => $req->input("oweight","0"),            
            "rweight" => $req->input("rweight","0"),
            "isrounded" => $req->input("rounded","0")
        );

        if ($rd_origin=="address"){
            $arrInventory["origin"] =  $req->input("origin_address","");
        }else{
            $arrInventory["origin"] =  $req->input("origin_loker","");
        }

        if ($rd_dest=="address"){
            $arrInventory["dest"] =  $req->input("dest_address","");
        }else{
            $arrInventory["dest"] =  $req->input("dest_loker","");
        }        
        DB::table('inventory_history')->where('id_history', $id)->where("last_update", $date)->update($arrHistory);
        DB::table('inventory')->where('order_no', $order_no)->update($arrInventory);        
        if ($status=="in")
            return redirect('/inbound')->with('message', $order_no.', Berhasil diupdate');
        else if ($status=="out")
            return redirect('/outbound')->with('message', $order_no.', Berhasil diupdate');
        else{
            $url = "/allorder";
            if ($req->session()->has('paramsallorder')) {
                $url = $url."?".$req->session()->get('paramsallorder');
            }    
            return redirect($url)->with('message', $order_no.', Berhasil diupdate');
        }
    }

    public function deleteconf(Request $req){ 
        $id = $req->input("id", "");
        $order_no = $req->input("order_no", "");
        $status = $req->input("status", "");
        $data["order_no"]  = $order_no;
        $data["id"]  = $id;
        $data["status"]  = $status;
        return view("deleteconf", $data);
    }

    public function deleted(Request $req){   
        $id = $req->input("id", "");
        $order_no = $req->input("order_no", "");
        $status = $req->input("status", "");
        $rd_delete = $req->input("rd_delete", "");
        $msg = "";        
        $arrUpdate = array(
            "deleted_at" => date("Y-m-d h:i:s"),
            "deleted_by" => $this->data["user"]->first_name." ".$this->data["user"]->last_name
            );
        if ($rd_delete == "one"){ 
                DB::table('inventory_history')->where('id_history',  $id)->update($arrUpdate);    
                $msg = $order_no." berhasil dihapus untuk satu history terakhir";         
        }else if ($rd_delete == "all"){
            DB::table('inventory_history')->where('order_no',  $order_no)->update($arrUpdate);
            DB::table('inventory')->where('order_no',  $order_no)->update($arrUpdate);
            $msg = $order_no.", berhasil dihapus untuk semua history";
        }else{
            $msg = $order_no.", gagal dihapus";
        }

        if ($status=="in")
            return redirect('/inbound')->with('message', $msg);
        else
            return redirect('/outbound')->with('message', $msg);
    }

    public function find_latest_status(Request $req){        
        $order_no = $req->input("orderNo" , "");
        $status = $req->input("status", "");
        $delivery_type = $req->input("delivery_type", "");
        $data = DB::table("inventory_history")
            ->where("order_no" , $order_no)            
            ->whereNull("deleted_at")
            ->orderBy("id_history" , "DESC")
            ->first();
        if (isset($data)){
            $resp = array("response" => array ("code" => "200", "message" => "ok", "description" => $data->status, "date" => $data->last_update));
        }else{
            if ($status=="out"){                                                    
                if ($delivery_type == "popshop" || $delivery_type =="internal"){
                    $resp = array("response" => array ("code" => "200", "message" => "ok", "description" => $delivery_type, "date" => date('Y-m-d H:i:s')));
                }else{
                    $resp = array("response" => array ("code" => "301", "message" => "fail", "description" => "in_is_emtpy"));
                }                
            } else if ($status=="in"){
                if ($delivery_type == "popshop" || $delivery_type =="internal"){
                    $resp = array("response" => array ("code" => "301", "message" => "fail", "description" => "out_is_empty"));
                }else{
                    $resp = array("response" => array ("code" => "301", "message" => "fail", "description" => "empty"));       
                }
            } else{
                $resp = array("response" => array ("code" => "301", "message" => "fail", "description" => "empty"));
            }
        }
        return response()->json($resp);
    }

    public function find_status(Request $req){
        $order_no = $req->input("orderNo" , "");
        $status = $req->input("status" , "");

        $data = DB::table("inventory_history")
            ->where("status" , $status)
            ->where("order_no" , $order_no)            
            ->whereNull("deleted_at")
            ->where("last_update" , "<",  date("Y-m-d H:i").":00")
            ->first();
        if (isset($data)){
            $resp = array("response" => array("code" => "200", "message" => "ok","description" => "data ditemukan"), 
                "data" => array());
            return response()->json($resp);
        }else{
             $resp = array("response" => array("code" => "301", "message" => "failed","description" => "data tidak ditemukan"), 
                "data" => array());
            return response()->json($resp);
            exit();
        }
    }
  

    public function allorder(Request $req){                        
        $req->session()->put('paramsallorder', $_SERVER['QUERY_STRING']);
        $data["type"] = "allorder";
        $today  = date('Y-m-d');        
        $data["from"] = $req->input("from", $today);
        $data["to"] = $req->input("to", $today);        
        $data["orderid"]     = $req->input("orderid","");   
        $data["delivery_type"] = $req->input("delivery_type","");
        $data["merchant"] = $req->input("merchant","");
        $data["courier"] = $req->input("courier","");
        $data["delivery"] = $req->input("delivery","");

        
        if (empty($data["delivery"])){            
            $someUsers = self::_getAllOrderObject($data);                    
        }else{
            $someUsers = self::_getAllOrderObjectNotDeliver($data);            
        }
        $data["merchants"] = DB::table("tb_merchant_pickup")
                ->select("merchant_name")
                ->where("merchant_name","<>","")
                ->groupBy("merchant_name")
                ->orderBy("merchant_name", "asc")->get();

        $data["couriers"] = DB::table("inventory_courier")
                ->select("id","name")
                ->where("name","<>","")
                ->groupBy("name")
                ->orderBy("name", "asc")->get();

        $data["count"] =$someUsers->count();   
        $data["row1"] =$someUsers->paginate($this->page);        
        $data["param_download"] = http_build_query($req->input());                        
        return view('allorder', $data);
    }

   
    public function detailorder(Request $req){        
        $data["type"] = "detailorder";                                  
        $data["orderid"] = $req->input("orderid");        
        $data["row"] = DB::table("inventory_history")
            ->select(DB::raw("inventory_history.*, inventory_courier.name as courier_name"))
            ->where("order_no", $data["orderid"])
            ->leftJoin("inventory_courier", "inventory_courier.id", "=", "inventory_history.inventory_courier_id")
            ->orderBy("id_history", "desc")                
            ->paginate($this->page);       
        $data["inventory"] = DB::table("inventory")
            ->where("order_no", $data["orderid"])            
            ->first();
        $data["qrcode"] = DNS2D::getBarcodePNG($data["orderid"], "QRCODE", 5,5);
        return view('detailorder', $data);   
    }

    public function detailorder1(Request $req){
        $data["type"] = "detailorder";                                  
        $data["orderid"] = $req->input("orderid");        
        $data["row"] = DB::table("inventory_history")        
            ->leftJoin("inventory_courier", "inventory_courier.id", '=',"inventory_history.inventory_courier_id")
            ->where("order_no", $data["orderid"])
            ->orderBy("id_history", "desc")
            ->paginate($this->page);        
        return view('detailorder', $data);   
    }


    public function insertajax(Request $req){                  
        if (!$req->input("order_id") && !$req->input("phone") && !$req->input("origin_address") && !$req->input("dest_address") && !$req->input("email")){
            $resp = array("response" => array("code" => "301", "message" => "failed","description" => "parameter ada yang masih kurang"), 
                "data" => array());
            return response()->json($resp);
            exit();
        }
        
        $order_no = $req->input("order_id","");
        $courier = $req->input("courier","");
        $delivery_type = $req->input("delivery_type","");
        $req->session()->put("courier", $courier);
        $req->session()->put("delivery_type", $delivery_type);
        $status = $req->input("type" , "in");
        $arrInsert = Helpers::getIdInsert($req); 
        $id = $arrInsert["id"];
        if (isset($id)){
            date_default_timezone_set("Asia/Jakarta");
            $ins_history = array(
                "id_inv" => $id,
                "order_no" => $arrInsert["order_no"],
                "inventory_courier_id" => $req->input("courier",""),
                "status" => $status,
                "delivery_type" => $req->session()->get("delivery_type"),
                "logistic_name" => $this->data["user"]->first_name." ".$this->data["user"]->last_name,                
                "last_update" => date("Y-m-d H:i:s"),
            );
            $isexist = DB::table("inventory_history")
                ->where("order_no", $ins_history["order_no"])
                ->where("status", $status)
                ->where("last_update",">=", date("Y-m-d H:i").":00")
                ->first();
            if (isset($isexist)){
            }else{
                DB::table("inventory_history")->insert($ins_history);
            }           
            $inventory = DB::table("inventory")->where("order_no", $arrInsert["order_no"])->first();
            $msg = "Data berhasil disimpan";
            if ($req->input("is_generate")=="1"){
                $msg = "AWB <strong>".$order_no."</strong> berhasil dibuat";
            }
            if ($arrInsert["is_awb_new"]==1){
                $msg = "Data berhasil disimpan no awb <strong>".$order_no."</strong> menjadi <strong>". $arrInsert["order_no"]."</strong> Karena sudah digenerate oleh user lain";
            }
            $resp = array("response" => array("code" => "200", "message" => "ok","description" => $msg), 
                "data" => array( "inventory" => $inventory));
            return response()->json($resp);
        }else{
            $resp = array("response" => array("code" => "301", "message" => "failed","description" => "parameter ada yang masih kurang"), 
                "data" => array());
            return response()->json($resp);
            exit();
        }
    }

    public function find(Request $req){            
        $order_no = $req->input("orderNo");
        $req->session()->put("order_no", $order_no);        
        $courier = $req->input("courier","");
        $req->session()->put("courier", $courier);

        $weight = $req->input("oweight","");
        $req->session()->put("weight", $weight);
        $type = $req->input("type");        

        $isExist = true;
        $arr_is_status = array("is_return" => false, "is_delivery" => false, "is_popshop" => false, "is_internal" =>false);
        $inventory = DB::table("inventory")->where("order_no", $order_no)->whereNull("deleted_at")->first();
        if (empty($inventory)){
            $inventory = DB::table("tb_member_pickup")->where("invoice_id", $order_no)->first();
            if (empty($inventory)){
                $inventory = DB::table("tb_merchant_pickup")->where("order_number", $order_no)->first();
                if (empty($inventory)){
                    $inventory = DB::table("locker_activities_return")->where("tracking_no", $order_no)->first();                    
                    if (empty($inventory)){
                        $inventory = DB::table("orders")->where("invoice_id", $order_no)->first();
                        if (empty($inventory)){                            
                            $inventory = DB::table("tb_merchant_service")->where("invoice_id", $order_no)->first();
                            if (empty($inventory)){
                                $inventory = DB::table("inventory_transaction")->where("order_no", trim($order_no))->first();
                                if (empty($inventory)){
                                    $isExist = false;    
                                }else{
                                    $data = Helpers::get_inventory_table($inventory, "inventory_transaction");
                                }                                
                            }else{
                                $arr_is_status["is_delivery"] = true;
                                $data = Helpers::get_inventory_table($inventory, "merchant_service");
                            }                            
                        }else{
                            $arr_is_status["is_delivery"] = true;
                            $data = Helpers::get_inventory_table($inventory, "orders");
                        }
                    }else{
                        $arr_is_status["is_return"] = true;                        
                        $data = Helpers::get_inventory_table($inventory, "merchant_return");
                    }
                }else{
                    $arr_is_status["is_delivery"] = true;
                    $data = Helpers::get_inventory_table($inventory, "merchant_pickup");
                }
            }else{
                $arr_is_status["is_delivery"] = true;
                $data = Helpers::get_inventory_table($inventory, "member_pikcup");                
            }

            if ($isExist){                    
                $id = Helpers::insertInvetory($data, $req);           
                Helpers::insertInvetoryHistory($id, $req, $this->data["user"], $arr_is_status);
                $inventory = DB::table("inventory")->where("order_no", $order_no)->first(); 
            }
        }else{
            if ($inventory->tbl_name=="locker_activities_return"){
                $arr_is_status["is_return"] = true;        
            }else{
                $arr_is_status["is_delivery"] = true;        
            }            
            Helpers::insertInvetoryHistory($inventory->id, $req, $this->data["user"], $arr_is_status);
        }
        if ($isExist){                        
            $history = DB::table("inventory_history")->where("order_no", $order_no)->first();
            $resp = array("response" =>array("code" => "200", "message" => "ok","is_return" =>  $arr_is_status["is_return"]), 
                "data" => array("inventory" => $inventory, "history" => $history));
        }else{
            $resp = array("response" =>array("code" => "301", "message" => "failed"), 
                "data" => array());
        }
        return response()->json($resp);
    }

    public function readInventory(Request $req){
        $order_no = $req->input("orderNo");
        $req->session()->put("order_no", $order_no);
        $inventory = DB::table("inventory")
                ->where("order_no", $order_no)                            
                ->first();        
        if (isset($inventory)){
            $history = DB::table("inventory_history")
                    ->where("order_no", $order_no)
                    ->orderBy('id_history', 'asc')
                    ->where("deleted_at", "is not null")
                    ->get();            
            $resp = array("response" =>array("code" => "200", "message" => "ok"),   
                "data" => array("inventory" => $inventory, "history" => $history,  
                    "code" => $order_no));
        }else{
            $resp = array("response" =>array("code" => "301", "message" => "failed"), 
                "data" => array());
        }
        return response()->json($resp);
    }

    public function readAfterPrint(Request $req){                
        $order_no = $req->session()->get("order_no","");
        if (isset($order_no)){
            $inventory = DB::table("inventory")->where("order_no", $order_no)->first();        
            if (isset($inventory_historyy)){
                $history = DB::table("inventory_history")
                ->where("order_no", $order_no)
                ->where("deleted_at", "is not null")
                ->orderBy('id_history', 'asc')
                ->get();
                $total = DB::table("inventory_history")->where("order_no", $order_no)->count();
                $resp = array("response" =>array("code" => "200", "message" => "ok"), 
                    "data" => array("inventory" => $inventory, "history" => $history,  
                        "code" => $order_no, "total" => $total));
            }else{
                $resp = array("response" =>array("code" => "301", "message" => "failed"), 
                    "data" => array());
            }
            return response()->json($resp);
        }
    }

    public function readHistory($type){
        $whereDate = " DATE(last_update)='".date('Y-m-d')."' AND ";

        if ($type=="in"){
            $whereDate = "DATE(last_update)>'2017-06-08' AND "; //tanggal dimulainya operational tanpa out
        }
        $someUsers = DB::table(DB::raw('inventory_history IH'))
                    ->select(DB::raw("IH.id_history, IH.status, IH.order_no, IH.last_update, IH.logistic_name, inventory_courier.name courier, inventory.merchant_name"))
                    ->join(DB::raw("(SELECT order_no, max(id_history) id_history, last_update FROM `inventory_history` where ".$whereDate." deleted_at is null group by order_no) b"), "IH.id_history","=","b.id_history")
                    ->leftJoin("inventory_courier", "inventory_courier.id", '=', DB::raw("IH.inventory_courier_id"))
                    ->leftJoin("inventory", "inventory.order_no", '=', DB::raw("IH.order_no"))
                    ->where(DB::raw("IH.status"),"=", $type)
                    ->whereNull(DB::raw("inventory.deleted_at"))
                    ->orderBy(DB::raw("IH.id_history"), "DESC");        
        $data["history"] = $someUsers->paginate($this->page);
        if ($type=="in")
            $data["history"]->setPath('/inbound');              
        else
            $data["history"]->setPath('/outbound');  
        $data["status"] = $type;
        $data["count"] = $data["history"]->total();
        return view('history', $data);
    }

    public function courier(Request $req){
        $company_id  = $req->input("company_id");
        $courier = DB::table("inventory_courier")->where("company_id", $company_id)
                ->orderBy("name")
                ->get();
        $resp = array("response" =>array("code" => "200", "message" => "ok"), 
                    "data" => $courier);
        return response()->json($resp);
    }

    public function generaterwb(Request $req){
        $rwbuild = Helpers::get_rw_build();
        $resp = array("response" =>array("code" => "200", "message" => "ok"), 
                    "data" => $rwbuild);
        return response()->json($resp);        
    }

    public function getweight(Request $req){
        $order_no = $req->input("order_no", "");        
        $data = DB::table("inventory")->where("order_no", $order_no)->first();
        if (isset($data)){
            $resp = array("response" =>array("code" => "200", "message" => "ok"), 
                    "data" => $data);
            return response()->json($resp);        
        }else{
            $resp = array("response" =>array("code" => "401", "message" => "failed"));
            return response()->json($resp);       

        }

    }

    public function uploadawb(Request $req){   
        $msg = "";  
        $arrMsg = array();
        if(Input::file('fileupload')!=null){
            $file =Input::file('fileupload');
            $objPHPExcel = PHPExcel_IOFactory::load($file);                        
            $arr = array();            
            foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                $worksheetTitle     = $worksheet->getTitle();
                $highestRow         = $worksheet->getHighestRow(); // e.g. 10
                $highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
                $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
                $nrColumns = ord($highestColumn) - 64;
                // echo "<br>The worksheet ".$worksheetTitle." has ";
                // echo $nrColumns . ' columns (A-' . $highestColumn . ') ';
                // echo ' and ' . $highestRow . ' row.';
                // echo '<br>Data: <table border="1"><tr>';
                for ($row = 2; $row <= $highestRow; ++ $row){                    
                    for ($col = 0; $col < $highestColumnIndex; ++ $col) {
                        $cell = $worksheet->getCellByColumnAndRow($col, $row);
                        $val = $cell->getValue();
                        $new_row = $row-2;
                        $i = 0;
                        if ($col==$i++){
                            $arr[$new_row]["merchant_name"] = $val;
                            if (!isset($val))
                                $arrMsg[] = "Merchant name";
                        }
                        else if ($col==$i++)
                            $arr[$new_row]["rechipient_name"] = $val;

                        else if ($col==$i++)
                            $arr[$new_row]["phone"] = $val;
                        else if ($col==$i++){
                            $arr[$new_row]["origin"] = $val;
                            if (!isset($val))
                                $arrMsg[] = "origin";
                        }
                        else if ($col==$i++){
                            $arr[$new_row]["address_type"] = $val;
                            if (!isset($val))
                                $arrMsg[] = "address_type";
                        }
                        else if ($col==$i++){
                            $arr[$new_row]["dest"] = $val;
                            if (!isset($val))
                                $arrMsg[] = "dest";
                        }
                        else if ($col==$i++)
                            $arr[$new_row]["email"] = $val;
                        else if ($col==$i++){
                            $arr[$new_row]["inventory_courier_id"] = Helpers::find_courier($val);
                            if (!isset($val))
                                $arrMsg[] = "courier";
                        }
                        else if ($col==$i++){
                            $arr[$new_row]["delivery_type"] = $val;
                            if (!isset($val))
                                $arrMsg[] = "delivery_type";
                        }
                        else if ($col==$i++){
                            $arr[$new_row]["weight"] = $val;
                            if (!isset($val))
                                $arrMsg[] = "weight";
                        }
                        else if ($col==$i++)
                            $arr[$new_row]["panjang"] = isset($val) ? $val : 0;
                        else if ($col==$i++)
                            $arr[$new_row]["lebar"] = isset($val) ? $val : 0;
                         else if ($col==$i++)
                            $arr[$new_row]["tinggi"] = isset($val) ? $val : 0;
                        else if ($col==$i++)
                            $arr[$new_row]["resi_no"] = $val;    
                        // $dataType = PHPExcel_Cell_DataType::dataTypeForValue($val);
                        // echo '<td>' . $val . '<br>(Typ ' . $dataType . ')</td>';
                    }
                }                
            }
            if (count($arrMsg) > 0) {
                $arrMsg = array_unique($arrMsg);
                $msg = "<strong>".implode(",",$arrMsg)."</strong> cannot be black, please complete your upload data";
                return redirect('/inbound')->with('message', $msg);
                die();
            }
            if (count($arr)>0){
                foreach ($arr as $key => $value) {
                    $inventory = array(
                            "order_no" => Helpers::get_rw_build(),
                            "weight" => $value["weight"],
                            "panjang" => $value["panjang"],
                            "lebar" => $value["lebar"],
                            "tinggi" => $value["tinggi"],
                            "isrounded" => "0", 
                            "merchant_name" => $value["merchant_name"], 
                            "resi_no" => $value["resi_no"], 
                            "phone" => $value["phone"], 
                            "origin" => $value["origin"],
                            "dest" => $value["dest"],
                            "address_type" => strtoupper($value["address_type"]),
                            "email" =>  $value["email"],
                            "is_generate" => 1,
                            "recipient_name" => $value["rechipient_name"],
                            "tbl_name" => 'tb_merchant_address',
                            "upload_date" => date("Y-m-d h:i:s")
                        );
                    $id = DB::table("inventory")->insertGetId($inventory);
                    if (isset($id)){
                        $inv_history = array(
                            "id_inv" => $id, 
                            "order_no" => $inventory["order_no"],
                            "inventory_courier_id" => $value["inventory_courier_id"],                          
                            "status" => "in",
                            "logistic_name" => $this->data["user"]->first_name." ".$this->data["user"]->last_name,
                            "delivery_type" => $value["delivery_type"],
                            "last_update" =>date("Y_m_d H:i:s")
                        );
                        $id_hist = DB::table("inventory_history")->insertGetId($inv_history);                        
                    }
                }
                $msg = "Data berhasil di upload";
            }        
        }
        return redirect('/inbound')->with('message', $msg);
    }

    public function downloadall(Request $req){  
        $data["type"] = "allorder";
        $today  = date('Y-m-d');        
        $data["from"] = $req->input("from", $today);
        $data["to"] = $req->input("to", $today);        
        $data["orderid"]     = $req->input("orderid","");   
        $data["delivery_type"] = $req->input("delivery_type","");
        $data["merchant"] = $req->input("merchant","");
        $data["courier"] = $req->input("courier",""); 

        $count_in = DB::table(DB::raw('inventory_history IH'))
                    ->join(DB::raw("(SELECT order_no, max(id_history) id_history FROM `inventory_history` where DATE(last_update)>='".$data["from"]."' AND DATE(last_update)<='".$data["to"]."' AND (deleted_at is not null OR deleted_at!='') group by order_no) b"), "IH.id_history","=","b.id_history")
                    ->where("status","in")
                    ->count();     
        $count_out = DB::table(DB::raw('inventory_history IH'))                    
                    ->join(DB::raw("(SELECT order_no, max(id_history) id_history FROM `inventory_history` where DATE(last_update)>='".$data["from"]."' AND DATE(last_update)<='".$data["to"]."' AND (deleted_at is not null OR deleted_at!='') group by order_no) b"), "IH.id_history","=","b.id_history")
                    ->where("status","out")
                    ->count();     

        $dbquery = self::_getAllOrderObject($data);
        $db = $dbquery->get();                
        $xls = new PHPExcel();
        $xls->getActiveSheet()->setCellValue('A1', 'Order ID');
        $xls->getActiveSheet()->setCellValue('B1', 'Merchant');
        $xls->getActiveSheet()->setCellValue('C1', 'Orig. Weight');
        $xls->getActiveSheet()->setCellValue('D1', 'Round. Weight');
        $xls->getActiveSheet()->setCellValue('E1', 'Last In ('.$count_in.')');
        $xls->getActiveSheet()->setCellValue('F1', 'Last Out ('.$count_out.')');
        $xls->getActiveSheet()->setCellValue('G1', 'Remark in');
        $xls->getActiveSheet()->setCellValue('H1', 'Remark Out');
        $xls->getActiveSheet()->setCellValue('I1', 'Delivery Type');
        $xls->getActiveSheet()->setCellValue('J1', 'Courier In');
        $xls->getActiveSheet()->setCellValue('K1', 'Courier Out');
        $xls->getActiveSheet()->setCellValue('L1', 'Courier Last');

        $idx = 2;
        foreach ($db as $key => $value) {
            if ($value->order_no){                

                $in = SiteHelpers::getMaxDayStatus($value->order_no, "in"); 
                $out = SiteHelpers::getMaxDayStatus($value->order_no, "out");                
                $all = SiteHelpers::getMaxDayStatus($value->order_no);
                $days = SiteHelpers::getDistanceDay($value->status, $value->last_update);
                if ($days>0 && $all->status=="in"){
                    self::cellColor($xls, 'A'.$idx.':I'.$idx, 'FF0000');
                }                
                $xls->getActiveSheet()->setCellValue('A'.$idx, $value->order_no);
                $xls->getActiveSheet()->setCellValue('B'.$idx, $value->merchant);
                $xls->getActiveSheet()->setCellValue('C'.$idx, $value->oweight);                   
                $xls->getActiveSheet()->setCellValue('D'.$idx, $value->rweight);                   
                if ($all->status=="in"){
                    self::cellColor($xls, 'E'.$idx, '006600');
                }
                $xls->getActiveSheet()->setCellValue('E'.$idx, isset($in->last_update) ? $in->last_update : "");
                if ($all->status=="out"){
                    self::cellColor($xls, 'F'.$idx, '006600');   
                }                
                $xls->getActiveSheet()->setCellValue('F'.$idx, isset($out->last_update) ? $out->last_update : "");
                $xls->getActiveSheet()->setCellValue('G'.$idx, isset($in->remark) ? $in->remark : "");
                $xls->getActiveSheet()->setCellValue('H'.$idx, isset($out->remark) ? $out->remark : "");
                $xls->getActiveSheet()->setCellValue('I'.$idx, $value->delivery_type);
                $xls->getActiveSheet()->setCellValue('J'.$idx, isset($in->name) ? $in->name : "");                
                $xls->getActiveSheet()->setCellValue('K'.$idx, isset($out->name)? $out->name : "") ;                
                $xls->getActiveSheet()->setCellValue('L'.$idx, $value->courier_name);                
                $idx++;
            }
           
        }
        
        $writer = new \PHPExcel_Writer_Excel5($xls);        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="cop_report_'.date("Y_m_d_H_i_s").'.xls"');
        header('Cache-Control: max-age=0');         
        $writer->save('php://output');      
    }

    public function download(Request $req){        
        $type = $req->input("type");
        $sql = "SELECT IH.*, ic.name courier, iv.* FROM inventory_history IH 
                inner join (SELECT order_no, max(id_history) id_history FROM `inventory_history` where DATE(last_update)='".date('Y-m-d')."' AND deleted_at is null group by order_no) b on IH.id_history=b.id_history 
                left join  inventory_courier ic on ic.id=IH.inventory_courier_id
                left join inventory iv on iv.id=IH.id_inv
                WHERE IH.status='".$type."' AND iv.deleted_at is null order by IH.id_history DESC";        
        $history = DB::select($sql);        
        $xls = new PHPExcel();
        $xls->getActiveSheet()->setCellValue('A1', 'Order ID');
        $xls->getActiveSheet()->setCellValue('B1', 'Merchant');
        $xls->getActiveSheet()->setCellValue('C1', 'Date');        
        $xls->getActiveSheet()->setCellValue('D1', 'Logistic Name');        
        $xls->getActiveSheet()->setCellValue('E1', 'Recipient name');            
        $xls->getActiveSheet()->setCellValue('F1', 'Phone');            
        $xls->getActiveSheet()->setCellValue('G1', 'origin');            
        $xls->getActiveSheet()->setCellValue('H1', 'Dest');
        $xls->getActiveSheet()->setCellValue('I1', 'Orig. Weight');            
        $xls->getActiveSheet()->setCellValue('J1', 'Round. Weight');            
        $xls->getActiveSheet()->setCellValue('K1', 'Panjang');            
        $xls->getActiveSheet()->setCellValue('L1', 'Courier');  
        $xls->getActiveSheet()->setCellValue('M1', 'Tinggi');            
        $xls->getActiveSheet()->setCellValue('N1', 'Lebar');            
        $xls->getActiveSheet()->setCellValue('O1', 'resi no');            

        $idx = 2;
        foreach ($history as $key => $value) {
            $xls->getActiveSheet()->setCellValue('A'.$idx, $value->order_no);
            $xls->getActiveSheet()->setCellValue('B'.$idx, $value->merchant_name);
            $xls->getActiveSheet()->setCellValue('C'.$idx, $value->last_update);
            $xls->getActiveSheet()->setCellValue('D'.$idx, $value->logistic_name);
            $xls->getActiveSheet()->setCellValue('E'.$idx, $value->recipient_name);
            $xls->getActiveSheet()->setCellValue('F'.$idx, $value->phone);
            $xls->getActiveSheet()->setCellValue('G'.$idx, $value->origin);
            $xls->getActiveSheet()->setCellValue('H'.$idx, $value->dest);
            $xls->getActiveSheet()->setCellValue('I'.$idx, $value->oweight);
            $xls->getActiveSheet()->setCellValue('J'.$idx, $value->rweight);
            $xls->getActiveSheet()->setCellValue('K'.$idx, $value->panjang);
            $xls->getActiveSheet()->setCellValue('L'.$idx, $value->courier);
            $xls->getActiveSheet()->setCellValue('M'.$idx, $value->tinggi);
            $xls->getActiveSheet()->setCellValue('N'.$idx, $value->lebar);
            $xls->getActiveSheet()->setCellValue('O'.$idx, $value->resi_no);
            $idx++;
        }
        
        $writer = new \PHPExcel_Writer_Excel5($xls);        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="cop_report_'.date("Y_m_d_H_i_s").'.xls"');
        header('Cache-Control: max-age=0');        
        $writer->save('php://output');                
    }

    public function qrcode(Request $req){
        $code = $req->input("code", ""); 
        $data = array("code" => $code, "qrcode" => "", "is_complete_desc" => true, "is_locker" => true, "is_cod" => false);
        if ($code!=""){
            $inventory = DB::table("inventory")->where("order_no", $code)->first();
            $history = DB::table("inventory_history")->where("order_no", $code)->first();           
            $data["label_dest"] = isset($inventory->address_type) ? $inventory->address_type : "" ;            
            $data["tujuan"] = $inventory->dest;            
            if (mb_strtoupper($data["label_dest"])=="LOKER" || strtoupper($data["label_dest"])=="LOCKER" || strtoupper($data["label_dest"])=="LOCKERS" || strtoupper($data["label_dest"])=="LOKERS"){
                $locker_locations = DB::table("locker_locations")->where("name",  $inventory->dest)->first();   
                if (isset($locker_locations)){                
                    $data["tujuan"] = "PopBox @". $inventory->dest." <br/>".$locker_locations->address."<br/>(".$locker_locations->address_2.")";
                }
            }            
            $data["name"] = isset($inventory->recipient_name) ? $inventory->recipient_name : "";
            $data["phone"] = $inventory->phone;
            $data["weight"] = $inventory->rweight;
            $res = DNS2D::getBarcodePNG($code, "QRCODE", 5,5);
            $data["qrcode"] = $res;
            $resp = array("response" =>array("code" => "200", "message" => "ok"), 
                "data" => $data);
        }else{
            $resp = array("response" =>array("code" => "301", "message" => "failed"), 
                "data" => array());    
        }        
        return response()->json($resp);
    }



    public static function test(Request $req){         
        echo $req->session()->get("courier");
        // $inventory = DB::table("inventory_history")->where("order_no", $order_no)->first();
        // echo "<pre>";
        // print_r($inventory);
        // die();
        return view("test");
    }

    private static function _getInOut($req, $status){
        $data["origin_address"] = DB::select("select REPLACE(REPLACE(origin, '\n', ''), '\r', ' ') origin  from inventory where origin<>'' group by origin order by id desc, origin asc limit 200");
        $data["dest_address"] = DB::select("select REPLACE(REPLACE(dest, '\n', ''), '\r', ' ') dest from inventory where dest<>'' AND address_type='address' or address_type is null group by dest order by id desc, dest asc limit 200");
        $data["merchants"] = DB::table("tb_merchant_pickup")
                ->select(DB::raw("trim(merchant_name) as merchant_name"))
                ->where("merchant_name","<>","")
                ->groupBy(DB::raw("trim(merchant_name)"))
                ->orderBy("merchant_name", "asc")->get();
        $data["courier_company"] = DB::table("inventory_courier_company")->select("id", "company")->orderBy("company")->get();
        $data["courier"] = $req->session()->get("courier", "");        
        $data["company_courier"] = DB::select("Select ICC.id, ICC.company from inventory_courier IC
                                inner join inventory_courier_company ICC on IC.company_id=ICC.id
                                where IC.id='".$data["courier"]."'");
        if (isset($data["company_courier"][0])){
            $data["couriers"] = DB::table("inventory_courier")->where("company_id", $data["company_courier"][0]->id)->get();
        }        
        $data["orderNo"] = $req->session()->get("orderNo", "");
        $data["resi_no"] = $req->input("resi_no", "");
        $data["nama"] = $req->input("nama", "");
        $data["weight"] = $req->input("weight", "");
        $data["oweight"] = $req->session()->get("oweight", "");
        $data["rweight"] = $req->input("rweight", "");
        $data["panjang"] = $req->input("panjang", "");
        $data["lebar"] = $req->input("lebar", "");
        $data["tinggi"] = $req->input("tinggi", "");        
        $data["type"] = $status;
        return $data;
    }

    private static function _getAllOrderObjectNotDeliver($data){
        $someUsers = DB::table(DB::raw('inventory_history IH'))
                    ->select(DB::raw("IH.*,inventory_courier.name courier_name, inventory.merchant_name merchant, inventory.oweight, inventory.rweight, inventory.isrounded"))
                    ->join(DB::raw("(Select order_no, max(id_history) id_history from inventory_history group by order_no) tbsum"), "IH.id_history","=","tbsum.id_history")
                    ->leftJoin("inventory_courier", "inventory_courier.id", '=', DB::raw("IH.inventory_courier_id"))
                    ->join("inventory", "inventory.order_no", "=" , DB::raw("IH.order_no"))
                    ->where(DB::raw("IH.status"),"in")
                    ->where(DB::raw("date(last_update)"), ">=", $data["from"])
                    ->where(DB::raw("date(last_update)"), "<=", $data["to"])
                    ->orderBy(DB::raw("IH.id_history"), "desc");        

        // $sql = 'SELECT IH.*,IC.name courier_name, IV.merchant_name merchant, IV.oweight, IV.rweight, IV.isrounded from inventory_history IH 
        //     inner join (Select order_no, max(id_history) id_history from inventory_history group by order_no) tbsum on IH.id_history=tbsum.id_history 
        //     left join inventory IV on IH.id_inv=IV.id 
        //     left join inventory_courier IC on IC.id=IH.inventory_courier_id
        //     where IH.status="in" AND date(last_update)>="'.$data["from"].'" AND date(last_update)<="'.$data["to"].'"';
        if ($data["delivery_type"]!=""){
            $someUsers =$someUsers->where(DB::raw("IH.delivery_type"), "=" , $data["delivery_type"]);
        }
        if ($data["orderid"]!=""){
            $someUsers =$someUsers->where(DB::raw("IH.order_no"), "like" ,  "%".$data['orderid']."%");
        }

        if ($data["merchant"]!=""){
            $someUsers =$someUsers->where("inventory.merchant_name", "like" ,  "%".trim($data['merchant'])."%");
        }

        if ($data["courier"]!=""){
            $someUsers =$someUsers->where("IH.inventory_courier_id", "=" ,  trim($data['courier']));   
        }
        return $someUsers;
    }

    private static function _getAllOrderObject($data){
         $someUsers = DB::table(DB::raw('inventory_history IH'))
                    ->select(DB::raw("IH.*,inventory_courier.name courier_name, inventory.merchant_name merchant, inventory.oweight, inventory.rweight, inventory.isrounded"))
                    ->join(DB::raw("(SELECT order_no, max(id_history) id_history FROM `inventory_history` where DATE(last_update)>='".$data["from"]."' AND DATE(last_update)<='".$data["to"]."' group by order_no) b" ), "IH.id_history","=","b.id_history")
                    ->leftJoin("inventory_courier", "inventory_courier.id", '=', DB::raw("IH.inventory_courier_id"))
                    ->join("inventory", "inventory.order_no", "=" , DB::raw("IH.order_no"))
                    ->orderBy(DB::raw("IH.id_history"), "desc");        
        if ($data["delivery_type"]!=""){
            $someUsers =$someUsers->where(DB::raw("IH.delivery_type"), "=" , $data["delivery_type"]);
        }
        if ($data["orderid"]!=""){
            $someUsers =$someUsers->where(DB::raw("IH.order_no"), "like" ,  "%".$data['orderid']."%");
        }

        if ($data["merchant"]!=""){
            $someUsers =$someUsers->where("inventory.merchant_name", "like" ,  "%".trim($data['merchant'])."%");
        }

        if ($data["courier"]!=""){
            $someUsers =$someUsers->where("IH.inventory_courier_id", "=" ,  trim($data['courier']));   
        }
        return $someUsers;
    }

    private static function cellColor($objPHPExcel, $cells,$color){
        $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'startcolor' => array(
                 'rgb' => $color
            )
        ));
    }

}
    