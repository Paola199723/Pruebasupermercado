<?php

namespace App\Http\Controllers;

use App\Http\Middleware\JWTmiddleware;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use League\CommonMark\Node\Query\OrExpr;
use phpDocumentor\Reflection\Types\This;
use PhpParser\Node\Expr\New_;
use Symfony\Component\Console\Input\Input;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
class ProductController extends Controller
{

    /**
     * Almacena un producto
     *
     * @param  {Request}  $request
     * @return $product
     */
    public function create(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'name_products' => 'required',
            'token' => 'required',
            'quantity' => 'required',
            'price' => 'required',
            'total' => 'required'
        ]);

        if ($validation->fails()) return response()->json([ 'message'=> 'Form Invalid', 'errors' => $validation->errors() ], 400);
        $product = new Product($request->all());
        if(!$this->ValidateToken($request)){
            return response()-> json([ 'message'=> 'Invalid Token'], 400);
        }

        $product->idUser =auth()->user()->id;
        $product->name_products = $request->input('name_products');
        $product->quantity = $request->input('quantity');
        $product->price = $request->input('price');
        $product->total = $request->input('total');
        $product->save();
        return response()->json($product);
    }
/**
     * busca un producto por su nombre
     *
     * @param  {Request} $request
     * @return $date
     */
    public function read(Request $request){

        //return response()-> json([ 'entro' ]);
        $validation = Validator::make($request->all(), [
            'id' => 'required',
            'token' => 'required',
        ]);

        if ($validation->fails()){
           return response()-> json([ 'message'=> 'Form Invalid',
            'errors' => $validation->errors() ], 400);

        }else{
            if(!$this->ValidateToken($request)){
                return response()-> json([ 'message'=> 'Invalid Token'], 400);
            }
            $date = DB::table('products')->where('id',$request->id)->get();
        }

            return response()-> json([$date]);
    }

        /**
     * Borra a un producto de la lista utilizando el nombre.
     *
     * @param  {Request} $request
     * @return {json}
     */

    public function delete(Request $request){

        $validation = Validator::make($request->all(), [
            'id' => 'required',
            'token'=>'required'
        ]);
        if ($validation->fails()){
            return response()-> json([ 'message'=> 'Form Invalid',
            'errors' => $validation->errors() ], 400);
        }
        if(!$this->ValidateToken($request)){
            return response()-> json([ 'message'=> 'Invalid Token'], 400);
        }
        $product = new Product($request->all());

        if (DB::table('products')->where('id',$request ->id)->exists()) {

            $delete = DB::table('products')->where('id',$product ->id)->delete();
            return response()-> json([ 'message'=>'dato borrado con exito',
                'delected'=>$delete ]);
        }else{
                return response()-> json([ 'message'=> 'no existe' ], 400);
            }
    }
     /**
     * edita un producto con el id ingresado.
     *
     * @param  {Request} $request
     * @return {json}
     */
    public function update(Request $request){
        $validation = Validator::make($request->all(), [
            'id' => 'required',
            'name_products' => 'required',
            'token'=>'required',
            'quantity' => 'required',
            'price' => 'required',
            'total' => 'required'
        ]);
        if ($validation->fails()){
             return response()-> json([ 'message'=> 'Form Invalid',
            'errors' => $validation->errors() ], 400);
        }
        if(!$this->ValidateToken($request)){
            return response()-> json([ 'message'=> 'Invalid Token'], 400);
        }
        $exist =  DB::table('products')->where('id',$request ->id)->exists();
        if ( $exist) {
            DB::table('products')
            ->where('id',$request ->id)
             ->update(['name_products' => $request->name_products,
            'quantity' =>$request->quantity,
            'price' =>$request->price,
            'total' => $request->total

            ]);

            return response()-> json([ 'message'=>'editado con exito' ]);
        }else{
                return response()-> json([ 'message'=> 'Form Invalid' ], 400);
            }


                }

                 /**
     * valida el token
     *
     * @param  {Request} $request
     * @return {true}
     */
    public function ValidateToken(Request $request){
        $validation = Validator::make($request->all(), [
            'token' => 'required',
         ]);
        if ($validation->fails()){
            return response()-> json([ 'message'=> 'Form Invalid',
            'errors' => $validation->errors() ], 400);
        }
        $auth = new JWTmiddleware();
        $response = $auth->handle($request);
        if($response){
            return true;
        }

    }
                /**
     *historial de compra del usuario logueado.
     *
     * @param  {Request} $request
     * @return {json}
     */
    public function history(Request $request){
        $validation = Validator::make($request->all(), [
            'token' => 'required',
        ]);
        if ($validation->fails()){
            return response()-> json([ 'message'=> 'Form Invalid',
            'errors' => $validation->errors() ], 400);
        }
        if(!$this->ValidateToken($request)){
            return response()-> json([ 'message'=> 'Invalid Token'], 400);
        }
        $id =auth()->user()->id;
       $maker =  DB::table('products')->where('idUser',$id)->get();
        return response()-> json($maker);
    }

}
