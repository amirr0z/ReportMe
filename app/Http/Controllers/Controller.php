<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

abstract class Controller
{

    protected function deepSearch(QueryBuilder|EloquentBuilder $baseQuery, string $table, array $searchList = [])
    {
        if ($searchList)
            $baseQuery->where(function ($query) use ($searchList, $table) {

                $foreignColumns = Schema::getForeignKeys($table);
                foreach ($foreignColumns as $foreignColumn) {
                    $query->orWhereIn(
                        $foreignColumn['columns'][0],
                        $this->deepSearch(
                            DB::table($foreignColumn['foreign_table']),
                            $foreignColumn['foreign_table'],
                            $searchList
                        )->get()->pluck('id')->toArray()
                    );
                }
                $columns = Schema::getColumnListing($table);
                foreach ($columns as $column) {
                    foreach ($searchList as $term => $value)
                        if ($column == $term)
                            $query->orWhere("{$column}", 'LIKE', "%{$value}%");
                }
            });

        return $baseQuery;
    }
}
