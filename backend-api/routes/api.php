<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\SaleOrderController;
use App\Http\Controllers\DisputeController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\StyleBoardController;
use App\Http\Controllers\TransformationLogController;
use App\Http\Controllers\MaterialCategoryController;
use App\Http\Controllers\EscrowServiceController;
use App\Http\Controllers\SwapAgreementController;
use App\Http\Controllers\CreatorController;

Route::get('/message', function () {
    return response()->json(['text' => 'Hello from Laravel!']);
});

// Public routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

// Public read-only routes
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/all', [CategoryController::class, 'all']);
Route::get('/categories/{category}', [CategoryController::class, 'show']);

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);

Route::get('/items', [ItemController::class, 'index']);
Route::get('/items/{item}', [ItemController::class, 'show']);
Route::get('/items/{item}/carbon-savings', [ItemController::class, 'carbonSavings']);

Route::get('/material-categories', [MaterialCategoryController::class, 'index']);
Route::get('/material-categories/{materialCategory}', [MaterialCategoryController::class, 'show']);

Route::get('/style-boards', [StyleBoardController::class, 'index']);
Route::get('/style-boards/{styleBoard}', [StyleBoardController::class, 'show']);

Route::get('/transactions', [TransactionController::class, 'index']);
Route::get('/transactions/{transaction}', [TransactionController::class, 'show']);

Route::get('/sale-orders', [SaleOrderController::class, 'index']);
Route::get('/sale-orders/{saleOrder}', [SaleOrderController::class, 'show']);
Route::get('/sale-orders/{saleOrder}/fee', [SaleOrderController::class, 'calculateFee']);
Route::get('/sale-orders/{saleOrder}/discount', [SaleOrderController::class, 'bundleDiscount']);

Route::get('/disputes', [DisputeController::class, 'index']);
Route::get('/disputes/{dispute}', [DisputeController::class, 'show']);

Route::get('/reviews', [ReviewController::class, 'index']);
Route::get('/reviews/{review}', [ReviewController::class, 'show']);

Route::get('/transformation-logs', [TransformationLogController::class, 'index']);
Route::get('/transformation-logs/{transformationLog}', [TransformationLogController::class, 'show']);
Route::get('/transformation-logs/{transformationLog}/care', [TransformationLogController::class, 'careInstructions']);

Route::get('/escrow-services', [EscrowServiceController::class, 'index']);
Route::get('/escrow-services/{escrowService}', [EscrowServiceController::class, 'show']);

Route::get('/swap-agreements', [SwapAgreementController::class, 'index']);
Route::get('/swap-agreements/{swapAgreement}', [SwapAgreementController::class, 'show']);
Route::get('/swap-agreements/{swapAgreement}/balance', [SwapAgreementController::class, 'valueBalancer']);

Route::get('/creators', [CreatorController::class, 'index']);
Route::get('/creators/{creator}', [CreatorController::class, 'show']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::get('/auth/user', [AuthController::class, 'user']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::put('/auth/profile', [AuthController::class, 'updateProfile']);
    Route::put('/auth/password', [AuthController::class, 'changePassword']);

    // Categories (write)
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{category}', [CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);

    // Products (write)
    Route::get('/products/my', [ProductController::class, 'myProducts']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{product}', [ProductController::class, 'update']);
    Route::delete('/products/{product}', [ProductController::class, 'destroy']);

    // Orders
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/seller', [OrderController::class, 'sellerOrders']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus']);

    // Addresses
    Route::get('/addresses', [AddressController::class, 'index']);
    Route::get('/addresses/{address}', [AddressController::class, 'show']);
    Route::post('/addresses', [AddressController::class, 'store']);
    Route::put('/addresses/{address}', [AddressController::class, 'update']);
    Route::delete('/addresses/{address}', [AddressController::class, 'destroy']);

    // Favorites
    Route::get('/favorites', [FavoriteController::class, 'index']);
    Route::post('/favorites', [FavoriteController::class, 'store']);
    Route::get('/favorites/check/{productId}', [FavoriteController::class, 'check']);
    Route::delete('/favorites/{productId}', [FavoriteController::class, 'destroy']);

    // Items (write)
    Route::get('/items/my', [ItemController::class, 'myProducts']);
    Route::post('/items', [ItemController::class, 'store']);
    Route::put('/items/{item}', [ItemController::class, 'update']);
    Route::delete('/items/{item}', [ItemController::class, 'destroy']);
    Route::put('/items/{item}/status', [ItemController::class, 'updateStatus']);

    // Material Categories (write)
    Route::post('/material-categories', [MaterialCategoryController::class, 'store']);
    Route::put('/material-categories/{materialCategory}', [MaterialCategoryController::class, 'update']);
    Route::delete('/material-categories/{materialCategory}', [MaterialCategoryController::class, 'destroy']);

    // Transactions (write)
    Route::post('/transactions', [TransactionController::class, 'store']);
    Route::post('/transactions/{transaction}/cancel', [TransactionController::class, 'cancel']);
    Route::post('/transactions/{transaction}/complete', [TransactionController::class, 'complete']);

    // Sale Orders (write)
    Route::post('/sale-orders', [SaleOrderController::class, 'store']);
    Route::put('/sale-orders/{saleOrder}/tracking', [SaleOrderController::class, 'updateTracking']);

    // Disputes (write)
    Route::post('/disputes', [DisputeController::class, 'store']);
    Route::post('/disputes/{dispute}/evidence', [DisputeController::class, 'uploadEvidence']);
    Route::post('/disputes/{dispute}/resolve', [DisputeController::class, 'resolve']);

    // Reviews (write)
    Route::post('/reviews', [ReviewController::class, 'store']);

    // Style Boards (write)
    Route::get('/style-boards/my', [StyleBoardController::class, 'myBoards']);
    Route::post('/style-boards', [StyleBoardController::class, 'store']);
    Route::put('/style-boards/{styleBoard}', [StyleBoardController::class, 'update']);
    Route::delete('/style-boards/{styleBoard}', [StyleBoardController::class, 'destroy']);
    Route::post('/style-boards/{styleBoard}/items', [StyleBoardController::class, 'addItem']);
    Route::delete('/style-boards/{styleBoard}/items/{itemId}', [StyleBoardController::class, 'removeItem']);

    // Transformation Logs (write)
    Route::post('/transformation-logs', [TransformationLogController::class, 'store']);

    // Escrow Services (write)
    Route::post('/escrow-services', [EscrowServiceController::class, 'store']);
    Route::post('/escrow-services/{escrowService}/lock', [EscrowServiceController::class, 'lockFunds']);
    Route::post('/escrow-services/{escrowService}/release', [EscrowServiceController::class, 'release']);
    Route::post('/escrow-services/{escrowService}/refund', [EscrowServiceController::class, 'refund']);

    // Swap Agreements (write)
    Route::post('/swap-agreements', [SwapAgreementController::class, 'store']);
    Route::post('/swap-agreements/{swapAgreement}/sign', [SwapAgreementController::class, 'sign']);
    Route::post('/swap-agreements/{swapAgreement}/lock', [SwapAgreementController::class, 'lock']);
    Route::post('/swap-agreements/{swapAgreement}/items', [SwapAgreementController::class, 'addItem']);

    // Creators
    Route::get('/creators/me', [CreatorController::class, 'me']);
    Route::post('/creators', [CreatorController::class, 'store']);
    Route::post('/creators/transformation', [CreatorController::class, 'logTransformation']);
    Route::post('/creators/bulk-listing', [CreatorController::class, 'bulkListing']);
    Route::put('/creators/{creator}/badge', [CreatorController::class, 'updateBadge']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});