<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\CategoryController;
use \App\Http\Controllers\BrandController;
use \App\Http\Controllers\AttributeTermController;
use \App\Http\Controllers\AttributeController;
use App\Http\Controllers\TagController;
use \App\Http\Controllers\ProductController;
use App\Models\Attribute;
use Illuminate\Http\Request;
use \App\Http\Controllers\UserController;
use \App\Http\Controllers\HomeController;
use \App\Http\Controllers\ProfileController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;




use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Auth::routes();




Route::middleware('auth')->group(function () {
    Route::view('about', 'about')->name('about');
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/home', [HomeController::class, 'index'])->name('home');


    Route::post('users/bulk_action', [UserController::class, 'bulkAction'])->name('users.bulk_action');
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::resource('users', UserController::class);

    Route::post('roles/update-roles-and-permissions', [RoleController::class, 'updateRoles'])->name('roles.updateRolesAndPermits');
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);


    Route::post('products/create_product_slug', [ProductController::class, 'createProductSlug'])->name('products.create_product_slug');
    Route::post('products/update_product_slug', [ProductController::class, 'updateProductSlug'])->name('products.update_product_slug');
    Route::post('products/bulk_action', [ProductController::class, 'bulkAction'])->name('products.bulk_action');

    Route::resource('products', ProductController::class);


    Route::resource('brands', BrandController::class);
    Route::post('brands/bulk_action', [BrandController::class, 'bulkAction'])->name('brands.bulk_action');

    Route::resource('categories', CategoryController::class);
    Route::post('categories/bulk_action', [CategoryController::class, 'bulkAction'])->name('categories.bulk_action');

    Route::resource('attributes', AttributeController::class);
    Route::post('attributes/bulk_action', [AttributeController::class, 'bulkAction'])->name('attributes.bulk_action');
    Route::get('/get_attribute_terms/{id}', [AttributeController::class, 'getAttributeTerms'])->name('attributes.get_attribute_terms');


    Route::resource('attribute_terms', AttributeTermController::class);
    Route::post('attribute_terms/bulk_action', [AttributeTermController::class, 'bulkAction'])->name('attribute_terms.bulk_action');

    Route::resource('tags', TagController::class);
    Route::post('tags/bulk_action', [TagController::class, 'bulkAction'])->name('tags.bulk_action');

    Route::post('/ajax_get_seleted_variations', function (Request $request) {
        $attribute_ids = $request->attribute_ids;
        $term_ids = $request->term_ids;

        if (!empty($attribute_ids) && !empty($term_ids)) {
            $attributes = Attribute::with(["terms" => function ($q) use ($term_ids) {
                $q->whereIn('id', $term_ids);
            }]);
            $attributes = $attributes->whereIn("id", $attribute_ids)->get();
            $jsonArray = [];
            foreach ($attributes as $k => $attribute) {
                $jsonArray[$k]["attribute_id"] = $attribute->id;
                $jsonArray[$k]["attribute_slug"] = $attribute->slug;
                foreach ($attribute->terms as $k1 => $term) {
                    $jsonArray[$k]["terms"][$k1]["term_slug"] = $term->slug;
                    $jsonArray[$k]["terms"][$k1]["term_id"] = $term->id;
                }
            }
            // dd($jsonArray);
            return response()->json($jsonArray);
        } else {
            return response()->json('empty');
        }
        // return $data;
    });



    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
});
Route::get('/test', [App\Http\Controllers\TestController::class, 'test'])->name('test');
