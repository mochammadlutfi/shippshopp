<?php

namespace App\Repositories;

interface SaksiInterface
{
    public function all($req, $caleg_id, $relawan_id = null, $paging);
    public function rekrutan($searchType, $search, $sort, $sortDir, $limit, $caleg_id, $uid = null);
    public function create(array  $data);
    public function update(array $data, $id);
    public function delete($id);
    public function find($id, $type, $uid = NULL);
    public function edit($id);
    public function wilayah($type, $relawan_id = null, $searchType, $search, $sort, $sortDir, $limit);
}