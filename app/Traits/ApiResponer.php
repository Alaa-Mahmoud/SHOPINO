<?php
namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

trait ApiResponer
{
    private function successRespone($data,$code)
    {
        return response()->json($data,$code);
    }

    protected function errorResponse($message,$code)
    {
        return response()->json(['error'=>$message , 'code'=>$code],$code);
    }

    protected function showAll(Collection $collection , $code=200)
    {
        if($collection->isEmpty())
        {
            return $this->successRespone(['data'=>$collection],$code);
        }
        $transformer = $collection->first()->transformer;
        $collection = $this->filterData($collection,$transformer);
        $collection = $this->sortData($collection,$transformer);
        $collection = $this->paginate($collection);
        $collection = $this->transformDate($collection , $transformer);
        $collection = $this->cacheResponse($collection);
        return $this->successRespone($collection,$code);
    }

    protected  function showOne(Model $model , $code=200)
    {
        $transformer = $model->transformer;
        $model = $this->transformDate($model , $transformer);
        return $this->successRespone($model,$code);
    }

    protected function showMessage($message , $code=200)
    {
        return $this->successRespone(['data'=>$message],$code);
    }

    protected function transformDate($data , $transformer)
    {
        $transformation = fractal($data , new $transformer);
        return $transformation->toArray();
    }

    protected function filterData(Collection $collection , $transformer)
    {
        foreach (request()->query() as $query => $value)
        {
            $attribute = $transformer::originalAttributes($query);
            if(isset($attribute,$value))
            {
                $collection = $collection->where($attribute,$value);
            }
        }
            return $collection;
    }

    protected function sortData(Collection $collection , $transformer)
    {
        if(request()->has('sort_by'))
        {
            $attribute = $transformer::originalAttributes(request()->sort_by);
            $collection = $collection->sortBy->{$attribute};
        }

        return $collection;
    }

    protected function paginate(Collection $collection)
    {
        $rules = [
            'per_page' => 'integer|min:2|max:40'
        ];

        Validator::validate(request()->all(),$rules);

      $page = LengthAwarePaginator::resolveCurrentPage();
      $perPage = 15;
      if(request()->has('per_page'))
      {
          $perPage = (int)request()->per_page;
      }
      $result = $collection->slice(($page-1)*$perPage , $perPage)->values();
      $paginated = new LengthAwarePaginator($result,$collection->count(),$perPage,$page,[
          'path'=> LengthAwarePaginator::resolveCurrentPath()
      ]);
      $paginated->appends(request()->all());
      return $paginated;
    }

    protected function cacheResponse($data)
    {
      $url = request()->url();
      $queryPrams = request()->query();
      ksort($queryPrams);
      $queryString = http_build_query($queryPrams);
      $fullUrl = "{$url}?{$queryString}";
      return Cache::remember($fullUrl , 1 , function () use($data){
          return $data;
      });
    }
}