<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Warehouse extends Model
{
    use HasFactory;

    public function bookedMaterial(): HasOne
    {
        return $this->hasOne(BookedMaterials::class, 'warehouse_id');
    }

    public function availableQuantity(){

        if($this->bookedMaterial()->exists())
        {
            $available_quantity = $this->remainder - $this->bookedMaterial->quantity;
        } else {
            $available_quantity = $this->remainder;
        }

        return $available_quantity;
    }

    public function updateBookedAmount($qty)
    {
        if ($this->bookedMaterial()->exists()) {
            $this->bookedMaterial->update([
                'quantity' => $qty + $this->bookedMaterial->quantity
            ]);
        } else {
            $this->bookedMaterial()->create([
                'quantity' => $qty
            ]);
        }
    }
}
