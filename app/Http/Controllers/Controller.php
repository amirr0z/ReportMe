<?php

namespace App\Http\Controllers;

use App\Traits\Searchable;
use Illuminate\Http\Request;

abstract class Controller
{
    //
    use Searchable;

    protected $searchableColumns = [];

    protected function applyIndexQuery($query, Request $request)
    {
        // Apply search filter if search term is provided in the request
        // if ($request->has('search')) {
        // $searchTerm = $request->input('search');
        // $this->search($query, $searchTerm, $this->searchableColumns);
        // }
        $queries = $request->all();
        foreach ($queries as $key => $value) {
            $query->orWhere($key, 'like', '%' . $value . '%');
        }

        // Add more query logic here based on your requirements

        return $query;
    }
}
