<?php

namespace App\Repositories;

interface StatistikInterface
{
    
    public function pendukung($caleg_id);
    public function relawan($caleg_id);
    public function age($gender, $caleg_id, $type = NULL);
    public function wilayah($type, $caleg_id, $level = NULL);
    public function dapil($caleg_id);
}