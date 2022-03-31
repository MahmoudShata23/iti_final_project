<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



use App\Models\User;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\API\CashController;
use App\Http\Controllers\API\StripeController;
use App\Http\Controllers\API\ReviewController;
use App\Http\Controllers\API\AllUserController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\WishlistController;
use App\Http\Controllers\API\CouponController;
use App\Http\Controllers\API\ReportController;
use App\Http\Controllers\API\SliderController;
use App\Http\Controllers\API\IndexController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\AdminUserController;
use App\Http\Controllers\API\LanguageController;
use App\Http\Controllers\API\SubCategoryController;
use App\Http\Controllers\API\AdminProfileController;
use App\Http\Controllers\API\ContactUsController;
use App\Http\Controllers\API\RateController;
use App\Http\Controllers\API\EmailSubscriptionController;
use App\Http\Controllers\API\FatoorahController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


//_____________________________________________________________________________________________________________

// Multi language all routes

Route::get('/language/arbic', [LanguageController::class, 'arbic'])->name('arbic.language');
Route::get('/language/english', [LanguageController::class, 'English'])->name('english.language');























/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// admin Profile Controller

Route::get('/admin/profile/{id}', [AdminProfileController::class, 'AdminProfile'])->name('admin.profile');

Route::post('/signup', [AdminProfileController::class, 'SignUp'])->name('admin.SignUp');
Route::post("/login",[AdminProfileController::class, 'AdminLogin'])->name('admin.profile');
Route::get('/logout', [AdminController::class, 'logout'])->name('admin.logout');
Route::get('/getAuthdAdmin', [AdminController::class, 'getAuthdAdmin'])->name('admin.getAuthdAdmin');
Route::get('/change/password/{id}', [AdminProfileController::class, 'AdminChangePassword'])->name('admin.change.password');
Route::post('/update/password/{id}', [AdminProfileController::class, 'AdminUpdatePassword'])->name('admin.update.password');


Route::get('edit/admin/{id}', [AdminProfileController::class, 'EditAdminProfile'])->name('edit.admin.profile');
Route::post('admin/update/{id}', [AdminProfileController::class, 'UpdateAdminProfile'])->name('admin.profile.update');
Route::delete('destory/admin/{id}', [AdminProfileController::class, 'destoryAdminProfile'])->name('admin.profile.destory');
Route::get('admin/change/password/{id}', [AdminProfileController::class, 'AdminChangePassword'])->name('admin.change.password');
Route::post('admin/update/password/{id}', [AdminProfileController::class, 'AdminUpdatePassword'])->name('admin.update.password');
// Admin Get All Users
Route::prefix('alluser')->group(function () {
    Route::get('/view', [AdminProfileController::class, 'AllUsers'])->name('all-users');
});
Route::get('/admin/logout', [AdminController::class, 'destroy'])->name('admin.logout');

// Admin Category All routes
Route::prefix('category')->group(function () {

    Route::get('/view', [CategoryController::class, 'CategoryView'])->name('all.category');
    Route::post('/store', [CategoryController::class, 'CategoryStore'])->name('category.store');
    Route::get('/edit/{id}', [CategoryController::class, 'CategoryEdit'])->name('category.edit');
    Route::post('/update/{id}', [CategoryController::class, 'CategoryUpdate'])->name('category.update');
    Route::delete('/delete/{id}', [CategoryController::class, 'CategoryDelete'])->name('category.delete');

    // admin Sub Category All Routes

    Route::get('/sub/view', [SubCategoryController::class, 'SubCategoryView'])->name('all.subcategory');
    Route::get('/catsub/view/{category_id}',[SubCategoryController::class,'Category_Subcategories'])->name('view.sub.bycategory');
    Route::get('/sub/show/{id}', [SubCategoryController::class, 'SubCategoryshow'])->name('show.subcategory');
    Route::post('/sub/store', [SubCategoryController::class, 'SubCategoryStore'])->name('subcategory.store');
    Route::get('/sub/edit/{id}', [SubCategoryController::class, 'SubCategoryEdit'])->name('subcategory.edit');
    Route::post('/sub/update/{id}', [SubCategoryController::class, 'SubCategoryUpdate'])->name('subcategory.update');
    Route::delete('sub/delete/{id}', [SubCategoryController::class, 'SubCategoryDelete'])->name('subcategory.delete');
});

// Admin Manage users
Route::prefix('adminuser')->group(function () {

    Route::get('/all', [AdminUserController::class, 'AllAdminRole'])->name('all-admin-user');
    Route::post('/store', [AdminUserController::class, 'StoreAdminUser'])->name('store-admin-user');
    Route::get('/edit/{id}', [AdminUserController::class, 'EditAdminUser'])->name('edit-admin-user');
    Route::post('/update/{id}', [AdminUserController::class, 'UpdateAdmin'])->name('update-admin-user');
    Route::get('/delete/{id}', [AdminUserController::class, 'DeleteAdmin'])->name('delete-admin-user');
});

//admin add slider
Route::prefix('slider')->group(function () {

    Route::get('/view', [SliderController::class, 'ViewSlider'])->name('view.slider');
    Route::post('/store', [SliderController::class, 'StoreSlider'])->name('store.slider');
    Route::get('/edit/{id}', [SliderController::class, 'EditSlider'])->name('edit.slider');
    Route::post('/update/{id}', [SliderController::class, 'UpdateSlider'])->name('update.slider');
    Route::get('/delete/{id}', [SliderController::class, 'DeleteSlider'])->name('delete.slider');
});

//all users routes
Route::get('/', [IndexController::class, 'index']);
Route::get('/user/logout', [IndexController::class, 'UserLogout'])->name('user.logout');
Route::get('/user/profile/{id}', [IndexController::class, 'UserProfile'])->name('user.profile');
Route::post('/user/profile/update/{id}', [IndexController::class, 'UserProfileUpdate'])->name('user.profile.edit');
Route::get('/user/password/', [IndexController::class, 'UserPassword'])->name('user.password');
Route::post('/user/password/update', [IndexController::class, 'UserPasswordUpdate'])->name('user.password.update');


//all contact us functions
//admin
Route::get('/contactUs', [ContactUsController::class, 'viewAllContactUs'])->name('view.allcontactus');
Route::get('/contactUs/{id}', [ContactUsController::class, 'showSubmission'])->name('view.contactus.submission');
Route::delete('/contactUs/delete/{id}', [ContactUsController::class, 'deleteSubmission'])->name('delete.contactus.submission');

//user
Route::post('/contactUs/add', [ContactUsController::class, 'addContactSubmission'])->name('add.contactus.submission');

// Admin Coupons All Routes
Route::prefix('coupons')->group(function () {

    Route::get('/manage', [CouponController::class, 'ViewCoupon'])->name('manage.coupon');
    Route::post('/store', [CouponController::class, 'StoreCoupon'])->name('store.coupon');
    Route::get('/edit/{id}', [CouponController::class, 'EditCoupon'])->name('edit.coupon');
    Route::post('/update/{id}', [CouponController::class, 'UpdateCoupon'])->name('update.coupon');
    Route::delete('/delete/{id}', [CouponController::class, 'DeleteCoupon'])->name('delete.coupon');
});

// Admin Products All Routes
Route::prefix('product')->group(function () {
    Route::get('/view', [ProductController::class, 'index'])->name('view.product');
    Route::get('/stock', [ProductController::class, 'ProductStock'])->name('stock.product');
    Route::post('/store', [ProductController::class, 'StoreProduct'])->name('store.product');
    Route::get('/show/{id}', [ProductController::class, 'ShowProduct'])->name('show.product');
    Route::get('/edit/{id}', [ProductController::class, 'EditProduct'])->name('product.edit');
    Route::post('/update/{id}', [ProductController::class, 'UpdateProduct'])->name('product.update');
    Route::delete('/delete/{id}', [ProductController::class, 'DeleteProduct'])->name('product.delete');
    Route::post('/image/update', [ProductController::class, 'MultiImageUpdate'])->name('update.product_image');
    Route::get('/multiimage/delete/{id}', [ProductController::class, 'MultiImageDelete'])->name('product.multiimage_delete');
});

//Cart functions
//admin
Route::get('/cart', [CartController::class, 'viewAllCarts']);
//user's cart functions
Route::post('/cart', [CartController::class, 'addProductToCart']);
Route::get('/user/cart', [CartController::class, 'MyCart']);
Route::delete('/cart/{product_id}', [CartController::class, 'RemoveCartProduct']);

//Rate functions

Route::post('/rate/product/{product_id}', [RateController::class, 'AddProductRate'])->name('rate.product');
Route::delete('/rate/delete/{product_id}', [RateController::class, 'DeleteProductRate'])->name('delete.product.rate');
Route::get('/user/rate/{product_id}', [RateController::class, 'GetUserRate'])->name('get.user.rate');


// Admin Manage Review Routes
Route::prefix('review')->group(function () {

    Route::post('/store', [ReviewController::class, 'StoreReview'])->name('store-review');

    Route::get('/pending', [ReviewController::class, 'PendingReview'])->name('pending-review');

    Route::post('/approve/{id}', [ReviewController::class, 'ApproveReview'])->name('approve-review');

    Route::get('/approved', [ReviewController::class, 'AllReviewsApproved'])->name('approved-review');

    Route::delete('/delete/{id}', [ReviewController::class, 'DeleteReview'])->name('delete-review');

    Route::get('/product/{product_id}', [ReviewController::class, 'GetProductReviews'])->name('get-product-reviews');
});
// Admin Order
Route::prefix('orders')->group(function () {
    Route::get('/order/details/{order_id}', [OrderController::class, 'OrdersDetails'])->name('order.details');
    Route::get('/order/ConfirmedOrderDetails/{order_id}', [OrderController::class, 'ConfirmedOrdersDetails'])->name('confirmedOrder.details');
    Route::get('/pending', [OrderController::class, 'PendingOrders'])->name('pending.orders');
    Route::get('/confirmed', [OrderController::class, 'ConfirmedOrders'])->name('confirmed.orders');
    Route::get('/processing', [OrderController::class, 'ProcessingOrders'])->name('processing.orders');
    Route::get('/picked', [OrderController::class, 'PickedOrders'])->name('picked.orders');
    Route::get('/shipped', [OrderController::class, 'ShippedOrders'])->name('shipped.orders');
    Route::get('/delivered', [OrderController::class, 'DeliveredOrders'])->name('delivered.orders');
    Route::get('/canceled', [OrderController::class, 'CanceledOrders'])->name('canceled.orders');


    // Update Status Orders
    Route::get('/pending/confirmed/{order_id}', [OrderController::class, 'PendingToConfirm'])->name('pending-confirmed');
    Route::get('/confirmed/processing/{order_id}', [OrderController::class, 'ConfirmedToProcessing'])->name('confirmed-processed');
    Route::get('/processing/picked/{order_id}', [OrderController::class, 'ProcessingToPicked'])->name('processed-picked');
    Route::get('/picked/shipped/{order_id}', [OrderController::class, 'PickedToShipped'])->name('picked-shipped');
    Route::get('shipped/delivered/{order_id}', [OrderController::class, 'ShippedToDelivered'])->name('shipped-delivered');
    Route::get('delivered/canceled/{order_id}', [OrderController::class, 'DeliveredToCanceled'])->name('delivered-canceled');
});

Route::group(['prefix' => 'user', 'namespace' => 'User'], function () {
    // Wishlist Routes
    Route::get('/wishlist', [WishlistController::class, 'ViewWishlist'])->name('wishlist');
    // Get wishlist Product
    Route::get('/get-wishlist-product', [WishlistController::class, 'GetWishlistProduct']);
    // wishlist Remove
    Route::delete('/wishlist-remove/{product_id}', [WishlistController::class, 'RemoveWishlistProduct']);
    // Add To Wishlist Button
    Route::post('/wishlist/add/{product_id}', [WishlistController::class, 'AddToWishlist']);
    // My Profile View Orders
    Route::get('/orders', [AllUserController::class, 'MyOrders'])->name('user.orders');
    Route::get('/details-order/{order_Id}', [AllUserController::class, 'DetailsOrder']);
    // Route to send the return order reason To database Return Order
    Route::post('/return/order/{order_id}', [AllUserController::class, 'ReturnOrder'])->name('return-order');
    // Return Order List
    Route::get('/return/orders/list', [AllUserController::class, 'ReturnedOrderList'])->name('returned.orders.list');
    // cancelled order list
    Route::get('/cancelled/orders/list', [AllUserController::class, 'CancelledOrderList'])->name('cancelled.orders.list');
    // Order Tracking in Home Page
    Route::post('/order/tracking', [AllUserController::class, 'OrderTracking'])->name('order-tracking');
});

// category Frontend  return all product in this category
Route::get('category/{cat_id}', [IndexController::class, 'CatProduct']);

// Subcategory Frontend  reteun all product in this subcategory
Route::get('subcategory/{subcat_id}', [IndexController::class, 'subCatProduct']);


// FrontEnd Route Product Details
Route::get('/product/details/{id}', [IndexController::class, 'DetailsProduct']);
// Product Search Routes
Route::get('/search/en/{product_name}', [IndexController::class, 'ProductSearchByName'])->name('product-search-name-en');
Route::get('/search/color/en/{product_color}', [IndexController::class, 'ProductSearchByColor'])->name('product-search-color-en');
Route::get('/search/category/en/{product_category}', [IndexController::class, 'ProductSearchByCategory'])->name('product-search-category-en');
Route::get('/search/price/{max_product_price}', [IndexController::class, 'ProductSearchByPrice'])->name('product-search-price');

// FrontEnd Route Product Details
Route::get('/product/details/{id}', [IndexController::class, 'DetailsProduct']);
// Product Search Routes
Route::get('/search/en/{product_name}', [IndexController::class, 'ProductSearchByName'])->name('product-search-name-en');
Route::get('/search/color/en/{product_color}', [IndexController::class, 'ProductSearchByColor'])->name('product-search-color-en');
Route::get('/search/category/en/{product_category}', [IndexController::class, 'ProductSearchByCategory'])->name('product-search-category-en');
Route::get('/search/price/{max_product_price}', [IndexController::class, 'ProductSearchByPrice'])->name('product-search-price');

// Admin Reports Routes
Route::prefix('reports')->group(function () {

    Route::get('/view', [ReportController::class, 'ViewReport'])->name('all-reports');
    Route::post('/search/by/date', [ReportController::class, 'ReportSearchByDate'])->name('search-by-date');
    Route::post('/search/by/month', [ReportController::class, 'ReportSearchByMonth'])->name('search-by-month');
    Route::post('/search/by/year', [ReportController::class, 'ReportSearchByYear'])->name('search-by-year');
    Route::post('/search/by/color', [ReportController::class, 'ReportSearchByColor'])->name('search-by-color');
});

Route::post('/subscribe',[EmailSubscriptionController::class,'SubscribeToUpdates'])->name('subscribe-to-updates');
Route::delete('/unsubscribe/{email}',[EmailSubscriptionController::class,'UnsubscribeToUpdates'])->name('unsubscribe-to-updates');

//myfatoorah
Route::post('pay', [FatoorahController::class,"payOrder"]); //add middle ware
Route::get('call_back', [FatoorahController::class,"paymentCallBack"]);
Route::post('cash/order', [FatoorahController::class, 'CashOrder'])->name('cash.order');
Route::get('user/orders/{email}',[FatoorahController::class,'UserOrder'])->name('user.order');
Route::delete('user/order/{id}',[FatoorahController::class,'DeleteUserOrder'])->name('delete.user.order');