<?php

namespace App\Http\Controllers;

use App\Models\BookedMaterials;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{

    protected $product;

    protected $bookedMaterials;

    protected $warehouse;

    public function __construct(Product $product, BookedMaterials $bookedMaterials, Warehouse $warehouse)
    {
        $this->product = $product;
        $this->bookedMaterials = $bookedMaterials;
        $this->warehouse = $warehouse;
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function makeProduct(Request $request)
    {
        foreach ($request->products as $item) {
            $product = $this->product->with('productMaterials.rawMaterial')
                ->where('id', $item['product_id'])
                ->first();

            $object = new \stdClass();
            $object->product_name = $product->name;
            $object->product_qty = $item['quantity'];
            $object->product_materials = $this->assembleMaterials($product, $item['quantity']);

            $result[] = $object;
        }

        // This line makes all materials available after product made (remove to avoid)
        $this->bookedMaterials->truncate();

        return response()->json([
            "result" => $result
        ]);
    }

    /**
     * @param object $product
     * @param int $product_quantity
     * @return array
     *
     * This method assembles all available materials which are needed to make particular product
     */

    public function assembleMaterials(object $product, int $product_quantity)
    {
        foreach ($product->productMaterials as $material) {

            $required_quantity = $material->quantity * $product_quantity; // amount of required material

            $warehouses = $this->warehouse::where('material_id', $material->material_id)->get();

            foreach ($warehouses as $warehouse) {

                $available_quantity = $warehouse->availableQuantity(); // picking unreserved amount of material only

                if ($required_quantity != 0 && $available_quantity != 0) {

                    if ($available_quantity > $required_quantity) {

                        //This will remove used materials from available materials list
                        $warehouse->updateBookedAmount($required_quantity);

                        $quantity = $required_quantity;

                        $required_quantity = 0; // Done! We don't need to check other parties.

                    } else { //it works when we can't get all our materials in one party

                        $required_quantity = $required_quantity - $available_quantity;

                        $quantity = $available_quantity;

                        $warehouse->updateBookedAmount($available_quantity);
                    }

                    $object = new \stdClass();
                    $object->warehouse_id = $warehouse->id;
                    $object->material_name = $material->rawMaterial->name;
                    $object->qty = $quantity;
                    $object->price = $warehouse->price;

                    $assembled_materials[] = $object;
                }

                // This works when we can't assemble our materials in full amount from warehouse
                if ($warehouses->last() == $warehouse && $required_quantity != 0) {
                    $object = new \stdClass();
                    $object->warehouse_id = null;
                    $object->material_name = $material->rawMaterial->name;
                    $object->qty = $required_quantity; // amount of material which is not left in warehouse
                    $object->price = null;

                    $assembled_materials[] = $object;
                }
            }
        }
        return $assembled_materials;
    }
}
