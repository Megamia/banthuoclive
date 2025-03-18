<?php
namespace Betod\Livotec\Controllers;

use App\Events\ProductUpdated;
use Backend;
use BackendMenu;
use Backend\Classes\Controller;

class Product extends Controller
{
    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\ListController::class
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Betod.Livotec', 'main-menu-item', 'side-menu-item');
    }
    protected static function boot()
    {
        parent::boot();

        // Khi sản phẩm được cập nhật
        static::updated(function ($product) {
            event(new ProductUpdated($product)); // Phát sự kiện khi sản phẩm được cập nhật
        });

        // Khi sản phẩm được tạo mới
        static::created(function ($product) {
            event(new ProductUpdated($product)); // Phát sự kiện khi sản phẩm mới được tạo
        });

        // Khi sản phẩm bị xóa
        static::deleted(function ($product) {
            event(new ProductUpdated($product)); // Phát sự kiện khi sản phẩm bị xóa
        });
    }

}
