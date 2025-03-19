<?php
namespace Betod\Livotec\Models;

use Model;

/**
 * Model
 */
class Product extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string table in the database used by the model.
     */
    protected $table = 'betod_livotec_product';

    /**
     * @var array Các trường có thể cập nhật bằng mass assignment
     */
    protected $fillable = [
        'name',
        'slug',
        'available',
        'sold_out',
        'stock'
    ];

    protected $jsonable = ['thongso'];

    public $belongsTo = [
        'category' => 'Betod\Livotec\Models\Category',
        'post' => 'RainLab\Blog\Models\Post'
    ];

    public $attachOne = [
        'image' => 'System\Models\File'
    ];
    public $attachMany = [
        'gallery' => 'System\Models\File'
    ];

    /**
     * @var array rules for validation.
     */
    public $rules = [];
}
