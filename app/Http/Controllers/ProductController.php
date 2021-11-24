<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
            'quantity' => 'required',
            'price' => 'required',
            'total' => 'required'
        ]);
        if ($validation->fails()) return response()->json([ 'message'=> 'Form Invalid', 'errors' => $validation->errors() ], 400);
        $product = new Product($request->all());
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
            'name_products' => 'required'
        ]);

        if ($validation->fails()){
           return response()-> json([ 'message'=> 'Form Invalid',
            'errors' => $validation->errors() ], 400);

        }else{

            $product = new Product($request->all());
            $date = DB::table('products')->where('name_products',$product ->name_products)->get();
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

            //return response()-> json([ 'entro' ]);
            $validation = Validator::make($request->all(), [
                'id' => 'required'

            ]);
            if ($validation->fails()){
                return response()-> json([ 'message'=> 'Form Invalid',
                'errors' => $validation->errors() ], 400);

             }
            $product = new Product($request->all());

            if (DB::table('products')->where('id',$request ->id)->exists()) {

                $delete = DB::table('products')->where('id',$product ->id)->delete();
                return response()-> json([ 'message'=>'usuario borrado con exito',
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

                //return response()-> json([ 'entro' ]);

                $validation = Validator::make($request->all(), [
                    'id' => 'required',
                    'name_products' => 'required',
                    'quantity' => 'required',
                    'price' => 'required',
                    'total' => 'required'
                ]);
                if ($validation->fails()){
                    return response()-> json([ 'message'=> 'Form Invalid',
                    'errors' => $validation->errors() ], 400);

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

}
