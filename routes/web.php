<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\ConsCategoryController;
use App\Http\Controllers\ConsItemController;
use App\Http\Controllers\ConsSubCategoryController;
use App\Http\Controllers\ConsumerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FundController;
use App\Http\Controllers\LoanItemController;
use App\Http\Controllers\NonConsCategoryController;
use App\Http\Controllers\NonConsConditionController;
use App\Http\Controllers\NonConsItemController;
use App\Http\Controllers\NonConsSubCategoryController;
use App\Http\Controllers\PickupItemController;
use App\Http\Controllers\PlacementItemController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [UserController::class, 'loginView'])->middleware('guest');
Route::post('/login', [UserController::class, 'login'])->middleware('guest')->name('login');
Route::get('/logout', [UserController::class, 'logout'])->middleware('mustBeLogin')->name('logout');
Route::get('/change-password/{user:username}', [UserController::class, 'changePasswordView'])->middleware('mustBeLogin')->name('change-password-view');
Route::put('/change-password/{user:username}', [UserController::class, 'changePassword'])->middleware('mustBeLogin')->name('change-password');

Route::middleware('mustBeLogin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('{unit}')->group(function () {
        Route::get('/consumable-items', [ConsItemController::class, 'index'])->name('consumable-items');
        Route::prefix('/consumable-items')->name('consumable-items.')->group(function () {
            Route::get('/pickup-items', [PickupItemController::class, 'index'])->name('pickup-items');
            Route::get('/pickup-items/create', [PickupItemController::class, 'create'])->name('pickup-items.create');
            Route::post('/pickup-items/create', [PickupItemController::class, 'store'])->name('pickup-items.store');
            Route::get('/pickup-items/{pickup}/edit', [PickupItemController::class, 'edit'])->middleware('can:update,pickup')->name('pickup-items.edit');
            Route::put('/pickup-items/{pickup}', [PickupItemController::class, 'update'])->middleware('can:update,pickup')->name('pickup-items.update');
            Route::delete('/pickup-items/{pickup}', [PickupItemController::class, 'delete'])->middleware('can:delete,pickup')->name('pickup-items.delete');
            Route::get('/pickup-items/report/pdf', [PickupItemController::class, 'reportPdf'])->name('pickup-items.report.pdf');
            Route::post('/pickup-items/report/pdf/{type}', [PickupItemController::class, 'printPdf'])->name('pickup-items.report.pdf.print');
            Route::get('/pickup-items/report/excel', [PickupItemController::class, 'reportExcel'])->name('pickup-items.report.excel');
            Route::post('/pickup-items/report/excel/{type}', [PickupItemController::class, 'printExcel'])->name('pickup-items.report.excel.print');

            Route::get('/categories', [ConsCategoryController::class, 'index'])->name('categories');
            Route::get('/categories/create', [ConsCategoryController::class, 'create'])->name('categories.create');
            Route::post('/categories/create', [ConsCategoryController::class, 'store'])->name('categories.store');
            Route::get('/categories/{category}/edit', [ConsCategoryController::class, 'edit'])->name('categories.edit');
            Route::put('/categories/{category}', [ConsCategoryController::class, 'update'])->name('categories.update');
            Route::delete('/categories/{category}', [ConsCategoryController::class, 'delete'])->name('categories.delete');

            Route::get('/sub-categories', [ConsSubCategoryController::class, 'index'])->name('sub-categories');
            Route::get('/sub-categories/create', [ConsSubCategoryController::class, 'create'])->name('sub-categories.create');
            Route::post('/sub-categories/create', [ConsSubCategoryController::class, 'store'])->name('sub-categories.store');
            Route::get('/sub-categories/{sub_category}', [ConsSubCategoryController::class, 'show'])->name('sub-categories.show');
            Route::get('/sub-categories/{sub_category}/edit', [ConsSubCategoryController::class, 'edit'])->name('sub-categories.edit');
            Route::put('/sub-categories/{sub_category}', [ConsSubCategoryController::class, 'update'])->name('sub-categories.update');
            Route::delete('/sub-categories/{sub_category}', [ConsSubCategoryController::class, 'delete'])->name('sub-categories.delete');

            Route::get('/create', [ConsItemController::class, 'create'])->name('create');
            Route::post('/create', [ConsItemController::class, 'store'])->name('store');
            Route::get('/{item}', [ConsItemController::class, 'show'])->middleware('can:view,item')->name('show');
            Route::get('/{item}/edit', [ConsItemController::class, 'edit'])->middleware('can:update,item')->name('edit');
            Route::put('/{item}', [ConsItemController::class, 'update'])->middleware('can:update,item')->name('update');
            Route::delete('/{item}', [ConsItemController::class, 'delete'])->middleware('can:delete,item')->name('delete');
            Route::get('/report/pdf', [ConsItemController::class, 'reportPdf'])->name('report.pdf');
            Route::post('/report/pdf/{type}', [ConsItemController::class, 'printPdf'])->name('report.pdf.print');
            Route::get('/report/excel', [ConsItemController::class, 'reportExcel'])->name('report.excel');
            Route::post('/report/excel/{type}', [ConsItemController::class, 'printExcel'])->name('report.excel.print');
        });

        Route::get('/non-consumable-items', [NonConsItemController::class, 'index'])->name('non-consumable-items');
        Route::prefix('/non-consumable-items')->name('non-consumable-items.')->group(function () {
            Route::get('/loan-items', [LoanItemController::class, 'index'])->name('loan-items');
            Route::get('/loan-items/create', [LoanItemController::class, 'create'])->name('loan-items.create');
            Route::post('/loan-items/create', [LoanItemController::class, 'store'])->name('loan-items.store');
            Route::get('/loan-items/{loan}/edit', [LoanItemController::class, 'edit'])->middleware('can:update,loan')->name('loan-items.edit');
            Route::put('/loan-items/{loan}', [LoanItemController::class, 'update'])->middleware('can:update,loan')->name('loan-items.update');
            Route::delete('/loan-items/{loan}', [LoanItemController::class, 'delete'])->middleware('can:delete,loan')->name('loan-items.delete');
            Route::get('/loan-items/return/{loan}', [LoanItemController::class, 'createReturnItem'])->middleware('can:update,loan')->name('loan-items.return.create');
            Route::post('/loan-items/return/{loan}', [LoanItemController::class, 'storeReturnItem'])->middleware('can:update,loan')->name('loan-items.return.store');
            Route::get('/loan-items/return/{loan}/edit', [LoanItemController::class, 'editReturnItem'])->middleware('can:update,loan')->name('loan-items.return.edit');
            Route::put('/loan-items/return/{loan}', [LoanItemController::class, 'updateReturnItem'])->middleware('can:update,loan')->name('loan-items.return.update');
            Route::get('/loan-items/report/pdf', [LoanItemController::class, 'reportPdf'])->name('loan-items.report.pdf');
            Route::post('/loan-items/report/pdf/{type}', [LoanItemController::class, 'printPdf'])->name('loan-items.report.pdf.print');
            Route::get('/loan-items/report/excel', [LoanItemController::class, 'reportExcel'])->name('loan-items.report.excel');
            Route::post('/loan-items/report/excel/{type}', [LoanItemController::class, 'printExcel'])->name('loan-items.report.excel.print');

            Route::get('/placement-items', [PlacementItemController::class, 'index'])->name('placement-items');
            Route::get('/placement-items/create', [PlacementItemController::class, 'create'])->name('placement-items.create');
            Route::post('/placement-items/create', [PlacementItemController::class, 'store'])->name('placement-items.store');
            Route::get('/placement-items/{placement}/edit', [PlacementItemController::class, 'edit'])->middleware('can:update,placement')->name('placement-items.edit');
            Route::put('/placement-items/{placement}', [PlacementItemController::class, 'update'])->middleware('can:update,placement')->name('placement-items.update');
            Route::delete('/placement-items/{placement}', [PlacementItemController::class, 'delete'])->middleware('can:delete,placement')->name('placement-items.delete');
            Route::get('/placement-items/return/{placement}', [PlacementItemController::class, 'createReturnItem'])->middleware('can:update,placement')->name('placement-items.return.create');
            Route::post('/placement-items/return/{placement}', [PlacementItemController::class, 'storeReturnItem'])->middleware('can:update,placement')->name('placement-items.return.store');
            Route::get('/placement-items/return/{placement}/edit', [PlacementItemController::class, 'editReturnItem'])->middleware('can:update,placement')->name('placement-items.return.edit');
            Route::put('/placement-items/return/{placement}', [PlacementItemController::class, 'updateReturnItem'])->middleware('can:update,placement')->name('placement-items.return.update');
            Route::get('/placement-items/report/pdf', [PlacementItemController::class, 'reportPdf'])->name('placement-items.report.pdf');
            Route::post('/placement-items/report/pdf/{type}', [PlacementItemController::class, 'printPdf'])->name('placement-items.report.pdf.print');
            Route::get('/placement-items/report/excel', [PlacementItemController::class, 'reportExcel'])->name('placement-items.report.excel');
            Route::post('/placement-items/report/excel/{type}', [PlacementItemController::class, 'printExcel'])->name('placement-items.report.excel.print');

            Route::get('/categories', [NonConsCategoryController::class, 'index'])->name('categories');
            Route::get('/categories/create', [NonConsCategoryController::class, 'create'])->name('categories.create');
            Route::post('/categories/create', [NonConsCategoryController::class, 'store'])->name('categories.store');
            Route::get('/categories/{category}', [NonConsCategoryController::class, 'show'])->name('categories.show');
            Route::get('/categories/{category}/edit', [NonConsCategoryController::class, 'edit'])->name('categories.edit');
            Route::put('/categories/{category}', [NonConsCategoryController::class, 'update'])->name('categories.update');
            Route::delete('/categories/{category}', [NonConsCategoryController::class, 'delete'])->name('categories.delete');

            Route::get('/sub-categories', [NonConsSubCategoryController::class, 'index'])->name('sub-categories');
            Route::get('/sub-categories/create', [NonConsSubCategoryController::class, 'create'])->name('sub-categories.create');
            Route::post('/sub-categories/create', [NonConsSubCategoryController::class, 'store'])->name('sub-categories.store');
            Route::get('/sub-categories/{sub_category}', [NonConsSubCategoryController::class, 'show'])->name('sub-categories.show');
            Route::get('/sub-categories/{sub_category}/edit', [NonConsSubCategoryController::class, 'edit'])->name('sub-categories.edit');
            Route::put('/sub-categories/{sub_category}', [NonConsSubCategoryController::class, 'update'])->name('sub-categories.update');
            Route::delete('/sub-categories/{sub_category}', [NonConsSubCategoryController::class, 'delete'])->name('sub-categories.delete');

            Route::get('/conditions', [NonConsConditionController::class, 'index'])->name('conditions');
            Route::get('/conditions/create', [NonConsConditionController::class, 'create'])->name('conditions.create');
            Route::post('/conditions/create', [NonConsConditionController::class, 'store'])->name('conditions.store');
            Route::get('/conditions/{condition}', [NonConsConditionController::class, 'show'])->name('conditions.show');
            Route::get('/conditions/{condition}/edit', [NonConsConditionController::class, 'edit'])->name('conditions.edit');
            Route::put('/conditions/{condition}', [NonConsConditionController::class, 'update'])->name('conditions.update');
            Route::delete('/conditions/{condition}', [NonConsConditionController::class, 'delete'])->name('conditions.delete');

            Route::get('/create', [NonConsItemController::class, 'create'])->name('create');
            Route::post('/create', [NonConsItemController::class, 'store'])->name('store');
            Route::get('/{item}', [NonConsItemController::class, 'show'])->middleware('can:view,item')->name('show');
            Route::get('/{item}/edit', [NonConsItemController::class, 'edit'])->middleware('can:update,item')->name('edit');
            Route::put('/{item}', [NonConsItemController::class, 'update'])->middleware('can:update,item')->name('update');
            Route::delete('/{item}', [NonConsItemController::class, 'delete'])->middleware('can:delete,item')->name('delete');
            Route::get('/report/pdf', [NonConsItemController::class, 'reportPdf'])->name('report.pdf');
            Route::post('/report/pdf/{type}', [NonConsItemController::class, 'printPdf'])->name('report.pdf.print');
            Route::get('/report/excel', [NonConsItemController::class, 'reportExcel'])->name('report.excel');
            Route::post('/report/excel/{type}', [NonConsItemController::class, 'printExcel'])->name('report.excel.print');
        });

        Route::get('/consumers', [ConsumerController::class, 'index'])->name('consumers');
        Route::get('/consumers/create', [ConsumerController::class, 'create'])->name('consumers.create');
        Route::post('consumers/create', [ConsumerController::class, 'store'])->name('consumers.store');
        Route::get('/consumers/{consumer}', [ConsumerController::class, 'show'])->middleware('can:show,consumer')->name('consumers.show');
        Route::get('/consumers/{consumer}/edit', [ConsumerController::class, 'edit'])->middleware('can:update,consumer')->name('consumers.edit');
        Route::put('/consumers/{consumer}', [ConsumerController::class, 'update'])->middleware('can:update,consumer')->name('consumers.update');
        Route::delete('/consumers/{consumer}', [ConsumerController::class, 'delete'])->middleware('can:delete,consumer')->name('consumers.delete');

        Route::get('/rooms', [RoomController::class, 'index'])->name('rooms');
        Route::get('/rooms/create', [RoomController::class, 'create'])->name('rooms.create');
        Route::post('rooms/create', [RoomController::class, 'store'])->name('rooms.store');
        Route::get('/rooms/{room}', [RoomController::class, 'show'])->middleware('can:show,room')->name('rooms.show');
        Route::get('/rooms/{room}/edit', [RoomController::class, 'edit'])->middleware('can:update,room')->name('rooms.edit');
        Route::put('/rooms/{room}', [RoomController::class, 'update'])->middleware('can:update,room')->name('rooms.update');
        Route::delete('/rooms/{room}', [RoomController::class, 'delete'])->middleware('can:delete,room')->name('rooms.delete');
    });
    Route::get('/consumable-items/checkSlug', [ConsItemController::class, 'checkSlug']);
    Route::get('/consumable-items/categories/checkSlug', [ConsCategoryController::class, 'checkSlug']);
    Route::get('/consumable-items/sub-categories/checkSlug', [ConsSubCategoryController::class, 'checkSlug']);
    Route::get('/non-consumable-items/categories/checkSlug', [NonConsCategoryController::class, 'checkSlug']);
    Route::get('/non-consumable-items/sub-categories/checkSlug', [NonConsSubCategoryController::class, 'checkSlug']);
    Route::get('/non-consumable-items/conditions/checkSlug', [NonConsConditionController::class, 'checkSlug']);
    Route::get('/consumers/checkSlug', [ConsumerController::class, 'checkSlug']);
    Route::get('/rooms/checkSlug', [RoomController::class, 'checkSlug']);

    Route::get('/brands/checkSlug', [BrandController::class, 'checkSlug']);
    Route::get('/brands', [BrandController::class, 'index'])->name('brands');
    Route::get('/brands/create', [BrandController::class, 'create'])->name('brands.create');
    Route::post('brands/create', [BrandController::class, 'store'])->name('brands.store');
    Route::get('/brands/{brand}', [BrandController::class, 'show'])->name('brands.show');
    Route::get('/brands/{brand}/edit', [BrandController::class, 'edit'])->name('brands.edit');
    Route::put('/brands/{brand}', [BrandController::class, 'update'])->name('brands.update');
    Route::delete('/brands/{brand}', [BrandController::class, 'delete'])->name('brands.delete');

    Route::get('/funds/checkSlug', [FundController::class, 'checkSlug']);
    Route::get('/funds', [FundController::class, 'index'])->name('funds');
    Route::get('/funds/create', [FundController::class, 'create'])->name('funds.create');
    Route::post('funds/create', [FundController::class, 'store'])->name('funds.store');
    Route::get('/funds/{fund}', [FundController::class, 'show'])->name('funds.show');
    Route::get('/funds/{fund}/edit', [FundController::class, 'edit'])->name('funds.edit');
    Route::put('/funds/{fund}', [FundController::class, 'update'])->name('funds.update');
    Route::delete('/funds/{fund}', [FundController::class, 'delete'])->name('funds.delete');

    Route::get('/positions/checkSlug', [PositionController::class, 'checkSlug']);
    Route::get('/positions', [PositionController::class, 'index'])->name('positions');
    Route::get('/positions/create', [PositionController::class, 'create'])->name('positions.create');
    Route::post('/positions/create', [PositionController::class, 'store'])->name('positions.store');
    Route::get('/positions/{position}', [PositionController::class, 'show'])->name('positions.show');
    Route::get('/positions/{position}/edit', [PositionController::class, 'edit'])->name('positions.edit');
    Route::put('/positions/{position}', [PositionController::class, 'update'])->name('positions.update');
    Route::delete('/positions/{position}', [PositionController::class, 'delete'])->name('positions.delete');

    Route::get('/shops/checkSlug', [ShopController::class, 'checkSlug']);
    Route::get('/shops', [ShopController::class, 'index'])->name('shops');
    Route::get('/shops/create', [ShopController::class, 'create'])->name('shops.create');
    Route::post('/shops/create', [ShopController::class, 'store'])->name('shops.store');
    Route::get('/shops/{shop}', [ShopController::class, 'show'])->name('shops.show');
    Route::get('/shops/{shop}/edit', [ShopController::class, 'edit'])->name('shops.edit');
    Route::put('/shops/{shop}', [ShopController::class, 'update'])->name('shops.update');
    Route::delete('/shops/{shop}', [ShopController::class, 'delete'])->name('shops.delete');

    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('users/create', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user:username}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'delete'])->name('users.delete');
    Route::get('/users/change-role/{user:username}/{role}', [UserController::class, 'changeRole'])->name('change-role');
});