<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use PHPExcel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Helpers\Helpers;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use \URL;
use \PHPExcel_IOFactory, \PHPExcel_Style_Fill, \PHPExcel_Cell, \PHPExcel_Cell_DataType, \SiteHelpers;

class CourierController extends Controller {
    
    var $data;
    var $company_id;
    public function __construct(Request $req){
    	$this->data["type"]= "Courier"; 
        $this->data["req"]= $req; 
        $this->company_id = \Auth::user()->company_id;
    }

	public function getList(){   
        $req = $this->data["req"];     
        $input= $req->input();         
        $custDB = $this->_get_index_filter($input);        
        $custDB = $this->_get_index_sort($req, $custDB, $input);           
        $custDB = $custDB->get();            
        $this->data["input"] = $input;
        $this->data["courier"] = $custDB;
        return view('courier.index', $this->data);
    }

    public function postCreate(){        
        $req = $this->data["req"];
        $validator = Validator::make($req->all(), [            
            'name' => 'required'          
        ]);

        if ($validator->fails()) {            
            return Redirect::to(URL::previous())->withInput(Input::all())->withErrors($validator);            
        }
        $arrInsert = $req->input();
        $arrInsert["created_at"] = date("Y-m-d h:i:s");
        $arrInsert["company_id"] = $this->company_id;
        unset($arrInsert["_token"]);        
        DB::table("courier")->insert($arrInsert);        
        return redirect('/courier/list')->with('message', "Successfull create");
    }

    public function getEdit($id){
        $req = $this->data["req"];   
        $customer = DB::table("courier")->where("id", $id)->first();        
        $this->data["courier"] = $customer;
        return view('courier.edit', $this->data);        
    }

    public function getDelete(Request $req, $id){
        DB::table("courier")->where("id", $id)->delete();                
        return redirect('/courier/list')->with('message', "Successfull delete");
    }

    public function getNew(){
        return view('courier.new', $this->data);
    }

    public function postUpdate($id){
        $req = $this->data["req"];   
        $validator = Validator::make($req->all(), [            
            'name' => 'required'          
        ]);

        if ($validator->fails()) {            
            return Redirect::to(URL::previous())->withInput(Input::all())->withErrors($validator);            
        }
        $arrInsert = $req->input();        
        unset($arrInsert["_token"]);        
        DB::table("courier")->where("id", $id)->update($arrInsert);        
        return redirect('/courier/list')->with('message', "Successfull update");
    }

    
    public function getCourierbycompany($company){
        $req = $this->data["req"];        
        $courier = DB::table("courier")->where("company", $company)->where("company_id", $this->company_id)
                ->orderBy("name")
                ->get();
        $resp = array("response" =>array("code" => "200", "message" => "ok"), 
                    "data" => $courier);
        return response()->json($resp);
    }


    private function _get_index_filter($filter){
        $dbcust = DB::table("courier")->where("company_id", $this->company_id);
        if (isset($filter["name"])){
            $dbcust = $dbcust->where("name", "like", "%".$filter["name"]."%");
        }
       
        return $dbcust;
    }

    private function _get_index_sort($req, $custDB, $input){                        
        if (isset($input["sort"])){
            if (empty($input["order_by"])){
                $order_by = "asc";       
            }else{
                $order_by = $input["order_by"];
            }
            $this->data["order_by"] = $order_by; 
            $this->data["sort"] = $input["sort"];

            if ($input["sort"]=="nama"){
                if ($order_by == "asc"){
                    $this->data["arrow_nama"] = '<span class="glyphicon glyphicon-menu-down"></span>';
                }elseif ($order_by == "desc"){
                    $this->data["arrow_nama"] = '<span class="glyphicon glyphicon-menu-up"></span>';
                }      
                $custDB = $custDB->orderBy("name", $order_by);                                
            }            
            else if ($input["sort"]=="created"){
                if ($order_by == "asc"){
                    $this->data["arrow_created"] = '<span class="glyphicon glyphicon-menu-down"></span>';
                }elseif ($order_by == "desc"){
                    $this->data["arrow_created"] = '<span class="glyphicon glyphicon-menu-up"></span>';
                }      
                $custDB = $custDB->orderBy("created_at", $order_by);                                
            }
        }else{
            $custDB = $custDB->orderBy("id", "desc");
        }        
                           
        return $custDB;
    }

}
    