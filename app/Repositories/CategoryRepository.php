<?php 

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository 
{
    public function all($per_page, $filter = "", $criteria = "")
    {
        if($filter==""){
            $categories = Category::orderBy("id","desc")->paginate($per_page);
        }else{
            $categories = Category::where($criteria,'like','%'. $filter. '%')->orderBy("id","desc")->paginate($per_page);
        }

        return $categories;
    }

    public function store($data)
    {
        $category = new Category();
        $category->name = $data->name;
        $category->description = $data->description;
        $category->condition = '1';
        return $category->save();
    }

    public function update($data, $id)
    {
        $category = Category::findOrFail($id);
        $category->name = $data->name;
        $category->description = $data->description;
        $category->condition = '1';
        return $category->save();
    }

    public function setCondition($id,$condition)
    {
        $categoria = Category::findOrFail($id);
        $categoria->condition = $condition;
        $categoria->save();
    }
}