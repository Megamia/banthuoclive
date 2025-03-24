<?php
namespace Betod\Livotec\Models;

use Illuminate\Support\Facades\Cache;
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
        'description',
        'price',
        'created_at',
        'updated_at',
        'category_id',
        'thongso',
        'available',
        'post_id',
        'sold_out',
        'stock'
    ];

    protected $jsonable = ['thongso'];

    public $belongsTo = [
        'category' => 'Betod\Livotec\Models\Category',
        'post' => 'RainLab\Blog\Models\Post',
    ];
    public $hasMany = [
        'ingredientsAndInstructions' => 'Betod\Livotec\Models\IngredientsAndInstructions'
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
    // protected static function boot()
    // {
    //     parent::boot();

    //     static::saved(function ($product) {
    //         Cache::forget('all_products');
    //         \Log::info("🔥 Cache all_products đã bị xóa do cập nhật sản phẩm: " . $product->id);
    //     });

    //     static::deleted(function ($product) {
    //         Cache::forget('all_products');
    //         \Log::info("🔥 Cache all_products đã bị xóa do xóa sản phẩm: " . $product->id);
    //     });

    // }
}
