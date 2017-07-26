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

class ProductController extends Controller {
    
    var $data;
    var $company_id;
    public function __construct(Request $req){
    	$this->data["type"]= "merchant"; 
        $this->data["req"]= $req; 
        $this->company_id = \Auth::user()->company_id;
    }

	public function getList(){   
        $req = $this->data["req"];     
        $input= $req->input();         
        $products = $this->_get_index_filter($input);        
        $products = $this->_get_index_sort($req, $products, $input);           
        $products = $products->get();                  
        $this->data["input"] = $input;
        $this->data["products"] = $products;
        return view('products.index', $this->data);
    }

    public function postCreate(){        
        $req = $this->data["req"];
        $validator = Validator::make($req->all(), [            
            'customer_name' => 'required',
            'merchant_id' => 'required',
            'customer_phone' => 'required',
            'customer_email' => 'required',
            'price' => 'required',
            'weight' => 'required',
        ]);

        if ($validator->fails()) {            
            return Redirect::to(URL::previous())->withInput(Input::all())->withErrors($validator);            
        }
        $arrInsert = $req->input();
        $arrInsert["order_no"] = Helpers::get_rw_build($this->company_id, "product");
        $arrInsert["created_at"] = date("Y-m-d h:i:s");
        $arrInsert["company_id"] = $this->company_id;
        unset($arrInsert["_token"]);        
        DB::table("product")->insert($arrInsert);        
        return redirect('/product/list')->with('message', "Successfull create");
    }

    public function getEdit($id){
        $req = $this->data["req"];   
        $product = DB::table("product")->where("id", $id)->first();        
        $merchant = DB::table("merchant")->where("company_id", $this->company_id)->get();
        $this->data["merchant"] = $merchant;
        $this->data["product"] = $product;
        return view('products.edit', $this->data);        
    }

    public function getDelete(Request $req, $id){
        DB::table("product")->where("id", $id)->delete();                
        return redirect('/product/list')->with('message', "Successfull delete");
    }

    public function getNew(){
        $merchant = DB::table("merchant")->where("company_id", $this->company_id)->get();
        $this->data["merchant"] = $merchant;
        return view('products.new', $this->data);
    }

    public function postUpdate($id){
        $req = $this->data["req"];   
        $validator = Validator::make($req->all(), [            
             'customer_name' => 'required',
            'merchant_id' => 'required',
            'customer_phone' => 'required',
            'customer_email' => 'required',
            'price' => 'required',
            'weight' => 'required',      
        ]);

        if ($validator->fails()) {            
            return Redirect::to(URL::previous())->withInput(Input::all())->withErrors($validator);            
        }
        $arrInsert = $req->input();        
        unset($arrInsert["_token"]);        
        DB::table("product")->where("id", $id)->update($arrInsert);        
        return redirect('/product/list')->with('message', "Successfull update");
    }

    private function _get_index_filter($filter){
        $dbcust = DB::table("product")->where("product.company_id", $this->company_id)
            ->select("product.company_id","product.id", "product.order_no", "merchant.name", "product.customer_name","product.customer_phone", "product.destination", "product.weight", "product.price", "product.created_at")
            ->join("merchant", "merchant.id","=", "product.merchant_id", "left");
        if (isset($filter["order_no"])){
            $dbcust = $dbcust->where("product.order_no", "like", "%".$filter["order_no"]."%");
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
    