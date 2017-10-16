<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class Products extends Controller{

   /**
     * Display a list of the products.
     * @return Response
     */
    public function index($id = null) {
        if ($id == null) {
            return Product::orderBy('id', 'asc')->get();
        } else {
            return $this->show($id);
        }
    }
	/**
     * Display a specified product.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        return Product::find($id);
    }
	/**
     * Save a newly created product.     
     * @param  Request  $request
     * @return Response
     */
    public function save(Request $request) {
        $product = new Product;
        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->manufacturer = $request->input('manufacturer');
        $product->num_stock = $request->input('num_stock');
        $product->save();
        return 'Product added to Inventory with id ' . $product->id;
    }
	/**
     * Update the specified product in the database.
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id) {
        $product = Product::find($id);
        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->manufacturer = $request->input('manufacturer');
        $product->num_stock = $request->input('num_stock');
        $product->save();
        return "Sucessfully updated Product #" . $product->id;
    }
	/**
     * Remove the specified product from the database.
     * @param  int  $id
     * @return Response
     */
    public function remove($id) {	
        $product = Product::find($id);
        $product->delete();
        return "Successfully removed Product #" . $id;
    }
}
