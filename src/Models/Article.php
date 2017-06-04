<?php

namespace Src\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Src\Models\BaseModel;

class Article extends BaseModel
{
    use SoftDeletes;

    const MAXIMIZE_DATA_ROW = 500;
    const AMOUNT = 5;
    const MAXIMIZE_AMOUNT = 50;

    const FILED_RULE = [
        'title'       => ['min' => 3, 'max' => 120],
        'description' => ['min' => 3, 'max' => 255],
        // 'image'       => ['max' => 255],
        'content'     => ['min' => 5, 'max' => 65535],
    ];

    protected $table = 'articles';
    protected $fillable = ['title', 'description', 'image', 'content'];

    public function getList($page = 1, $amount = self::AMOUNT)
    {
        $amount = min($amount, self::MAXIMIZE_AMOUNT);

        $skip = ($page -1 ) * $amount;
        return $this->select(['id', 'title', 'description', 'image', 'created_at', 'updated_at'])
            ->orderBy('id', 'DESC')
            ->skip($skip)
            ->take($amount)
            ->get();
    }

    public function InputValidation(array $inputs): array
    {
        $min = self::FILED_RULE['title']['min'];
        $max = self::FILED_RULE['title']['max'];
        $valid['title'] = preg_match("/^[\w\d]{{$min},{$max}}$/", $inputs['title'] ?? '');

        $min = self::FILED_RULE['description']['min'];
        $max = self::FILED_RULE['description']['max'];
        $valid['description'] = preg_match("/^[\w\d]{{$min},{$max}}$/", $inputs['description'] ?? '');

        $valid['image'] = true;
        if (filter_var($inputs['image'], FILTER_VALIDATE_URL) === false) {
            $valid['image'] = false;
        }

        // 暫不處理 html
        $min = self::FILED_RULE['content']['min'];
        $max = self::FILED_RULE['content']['max'];
        $valid['content'] = preg_match("/^[\w\d]{{$min},{$max}}$/", $inputs['content'] ?? '');

        return array_map('boolval', $valid);
    }

    /**
     * Save a new model and return the instance.
     *
     * @param  array  $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $attributes = [])
    {
        // 避免資料過多
        if ($this->count() >= self::MAXIMIZE_DATA_ROW) {
            $this->deleteOldData();
        }
        return tap($this->newModelInstance($attributes), function ($instance) {
            $instance->save();
        });
    }

    // 刪除舊資料，保留 1/5
    public function deleteOldData()
    {
        $limit = $this->count() * ( 4/5 );
        return $this->orderBy('id')->limit($limit)->forceDelete();
    }
}
