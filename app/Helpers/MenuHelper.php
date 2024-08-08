<?php

namespace App\Helpers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use DB;

class MenuHelper
{
    public static function get(){

        $menuData = Collect([]);
        $level = auth()->guard('admin')->user()->level;
        if($level == 'admin'){

        $menuData->push([
            "icon" => "fa fa-home",
            "name" => "Beranda",
            "to" => "admin.dashboard",
        ]);
        
        $menuData->push([
            "icon" => "fa fa-users",
            "name" => "Konsumen",
            "to" => "admin.sale.customer.index",
        ]);

        $menuData->push([
            "icon" => "fa fa-bag-shopping",
            "name" => "Penjualan",
            "to" => "admin.sale.order.index",
        ]);

        $menuData->push([
            "icon" => "fa fa-user",
            "name" => "Supplier",
            "to" => "admin.purchase.supplier.index",
        ]);

        
        // $menuData->push([
        //     "icon" => "si si-action-undo",
        //     "name" => "Retur",
        //     "to" => "admin.sale.return.index",
        // ]);

        $menuData->push([
            "icon" => "fa fa-cart-arrow-down",
            "name" => "Pembelian",
            "to" => "admin.purchase.order.index",
        ]);
        
        $menuData->push([
            "icon" => "fa fa-boxes",
            "name" => "Produk",
            "to" => "admin.inventory.product.index",
        ]);
        $menuData->push([
            "icon" => "fa fa-archive",
            "name" => "Kategori",
            "to" => "admin.inventory.category.index",
        ]);
        $menuData->push([
            "icon" => "fa fa-star",
            "name" => "Merk",
            "to" => "admin.inventory.brand.index",
        ]);
        // $menuData->push([
        //     "name" => 'Inventaris',
        //     "icon" => 'fa fa-boxes-stacked',
        //     "subActivePaths" => 'admin.inventory.*',
        //     "sub" => [
        //         [
        //             "name" => 'Produk',
        //             "to" => 'admin.inventory.product.index',
        //         ],
        //         [
        //             "name" => 'Kategori',
        //             "to" => 'admin.inventory.category.index',
        //         ],
        //         [
        //             "name" => 'Merk',
        //             "to" => 'admin.inventory.brand.index',
        //         ],
        //     ]
        // ]);
        $menuData->push([
            "icon" => "fa fa-user",
            "name" => "Staff",
            "to" => "admin.user.index",
        ]);
        }else if($level == 'Gudang'){
            
            $menuData->push([
                "icon" => "fa fa-home",
                "name" => "Beranda",
                "to" => "admin.dashboard",
            ]);
            
            $menuData->push([
                "icon" => "fa fa-users",
                "name" => "Pelanggan",
                "to" => "admin.sale.customer.index",
            ]);

            $menuData->push([
                "icon" => "fa fa-bag-shopping",
                "name" => "Penjualan",
                "to" => "admin.sale.order.index",
            ]);
        }else{
            $menuData->push([
                "icon" => "fa fa-home",
                "name" => "Beranda",
                "to" => "admin.dashboard",
            ]);

            $menuData->push([
                "icon" => "fa fa-cart-arrow-down",
                "name" => "Pembelian",
                "to" => "admin.purchase.order.index",
            ]);
            
        $menuData->push([
            "icon" => "fa fa-boxes",
            "name" => "Produk",
            "to" => "admin.inventory.product.index",
        ]);
        $menuData->push([
            "icon" => "fa fa-archive",
            "name" => "Kategori",
            "to" => "admin.inventory.category.index",
        ]);
        $menuData->push([
            "icon" => "fa fa-star",
            "name" => "Merk",
            "to" => "admin.inventory.brand.index",
        ]);
            // $menuData->push([
            //     "name" => 'Inventaris',
            //     "icon" => 'fa fa-boxes-stacked',
            //     "subActivePaths" => 'admin.inventory.*',
            //     "sub" => [
            //         [
            //             "name" => 'Produk',
            //             "to" => 'admin.inventory.product.index',
            //         ],
            //         [
            //             "name" => 'Kategori',
            //             "to" => 'admin.inventory.category.index',
            //         ],
            //         [
            //             "name" => 'Satuan',
            //             "to" => 'admin.inventory.unit.index',
            //         ],
            //         [
            //             "name" => 'Merk',
            //             "to" => 'admin.inventory.brand.index',
            //         ],
            //     ]
            // ]);
        }

        
        return $menuData->all();
    }

    public static function permission()
    {
        $data = auth()->guard('admin')->user()->getAllPermissions()->toArray();
        $permission = array();
        foreach ($data as $element) {
            $permission[$element['module']][] = $element['name'];
        }

        return $permission;
    }
}
