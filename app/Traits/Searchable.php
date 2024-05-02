<?php

namespace App\Traits;

trait Searchable
{
    //
    protected function search($query, $searchTerm, $searchableColumns)
    {
        if (!empty($searchTerm) && !empty($searchableColumns)) {
            $query->where(function ($query) use ($searchTerm, $searchableColumns) {
                foreach ($searchableColumns as $column) {
                    $query->orWhere($column, 'like', '%' . $searchTerm . '%');
                }
            });
        }

        return $query;
    }
}
