<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CategoryAdvancedController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderAdvancedController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ItemAdvancedController;
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
use App\Http\Controllers\SellerBadgeController;
use App\Http\Controllers\DigitalClosetController;
use App\Http\Controllers\TransformationController;
use App\Http\Controllers\ItemLockController;
use App\Http\Controllers\SwapBundleController;
use App\Http\Controllers\CareInstructionController;
use App\Http\Controllers\BulkListingController;
use App\Http\Controllers\GeospatialController;
use App\Http\Controllers\EscrowController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\MentorshipController;
use App\Http\Controllers\MarketTrendsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\DatabaseCleanupController;

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

// Market Trends (UC-36) - Public
Route::get('/trends/materials', [MarketTrendsController::class, 'getMaterialTrends']);
Route::get('/trends/categories', [MarketTrendsController::class, 'getCategoryPerformance']);
Route::get('/trends/pricing', [MarketTrendsController::class, 'getPriceRecommendations']);
Route::get('/trends/seasonal', [MarketTrendsController::class, 'getSeasonalTrends']);

// Newsletter (UC-41) - Public
Route::get('/newsletter/generate', [NewsletterController::class, 'generateWeeklyNewsletter']);
Route::get('/newsletter/past', [NewsletterController::class, 'getPastNewsletters']);
Route::get('/newsletter/subscribers', [NewsletterController::class, 'getSubscribers']);
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe']);

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

// === ADVANCED FUNCTIONS ===

// Category Advanced (15-20)
Route::get('/categories/tree', [CategoryAdvancedController::class, 'getCategoryTree']);
Route::get('/categories/stats', [CategoryAdvancedController::class, 'getCategoryStats']);
Route::get('/categories/popular', [CategoryAdvancedController::class, 'getPopularCategories']);
Route::get('/categories/slug/{slug}', [CategoryAdvancedController::class, 'getBySlug']);
Route::get('/categories/{id}/breadcrumb', [CategoryAdvancedController::class, 'getBreadcrumb']);
Route::get('/categories/{id}/subcategories', [CategoryAdvancedController::class, 'getSubcategories']);

// Item Advanced (6-14)
Route::get('/items/{id}/carbon-footprint', [ItemAdvancedController::class, 'calculateCarbonFootprint']);
Route::get('/items/{id}/similar', [ItemAdvancedController::class, 'getSimilarItems']);
Route::get('/items/{id}/statistics', [ItemAdvancedController::class, 'getItemStatistics']);
Route::get('/items/{id}/depreciation', [ItemAdvancedController::class, 'calculateDepreciation']);
Route::get('/items/{id}/share', [ItemAdvancedController::class, 'shareItem']);

// Order Advanced (21-30)
Route::post('/orders/shipping-estimate', [OrderAdvancedController::class, 'calculateShipping']);
Route::get('/orders/{id}/timeline', [OrderAdvancedController::class, 'getOrderTimeline']);
Route::get('/orders/{id}/track', [OrderAdvancedController::class, 'trackShipment']);
Route::post('/orders/bundle-discount', [OrderAdvancedController::class, 'calculateBundleDiscount']);
Route::get('/orders/estimate-value', [OrderAdvancedController::class, 'estimateOrderValue']);
Route::get('/analytics/transactions', [OrderAdvancedController::class, 'getTransactionAnalytics']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::get('/auth/user', [AuthController::class, 'user']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::put('/auth/profile', [AuthController::class, 'updateProfile']);
    Route::put('/auth/password', [AuthController::class, 'changePassword']);

    // Auth Advanced Functions (1-5)
    Route::get('/auth/eco-credits', [AuthController::class, 'getEcoCreditsHistory']);
    Route::get('/auth/trust-score', [AuthController::class, 'getTrustScoreDetails']);
    Route::put('/auth/avatar', [AuthController::class, 'updateAvatar']);
    Route::get('/auth/activity', [AuthController::class, 'getActivityLog']);
    Route::get('/auth/referral', [AuthController::class, 'getReferralCode']);

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

    // Item Advanced Protected Functions (6-14)
    Route::post('/items/{id}/mark-swap-ready', [ItemAdvancedController::class, 'markSwapReady']);
    Route::post('/items/{id}/style-board', [ItemAdvancedController::class, 'addToStyleBoard']);
    Route::post('/items/{id}/report', [ItemAdvancedController::class, 'reportItem']);
    Route::post('/items/bulk-status', [ItemAdvancedController::class, 'bulkUpdateStatus']);

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

    // Swap Agreement Advanced (22-24)
    Route::post('/swaps/agreement', [OrderAdvancedController::class, 'createSwapAgreement']);
    Route::post('/swaps/{id}/cancel', [OrderAdvancedController::class, 'cancelSwapRequest']);
    Route::post('/swaps/{id}/rate', [OrderAdvancedController::class, 'rateSwap']);
    Route::get('/swaps/history', [OrderAdvancedController::class, 'getSwapHistory']);

    // Creators
    Route::get('/creators/me', [CreatorController::class, 'me']);
    Route::post('/creators', [CreatorController::class, 'store']);
    Route::post('/creators/transformation', [CreatorController::class, 'logTransformation']);
    Route::post('/creators/bulk-listing', [CreatorController::class, 'bulkListing']);
    Route::put('/creators/{creator}/badge', [CreatorController::class, 'updateBadge']);

    // UC-1: Seller Badges (Pro-Upcycler, Eco-Verified)
    Route::get('/seller/badges', [SellerBadgeController::class, 'getBadges']);
    Route::post('/seller/badges', [SellerBadgeController::class, 'updateBadges']);
    Route::post('/seller/verify-eco', [SellerBadgeController::class, 'verifyEco']);

    // UC-6: Trust Score
    Route::get('/seller/trust-score', [SellerBadgeController::class, 'getTrustScore']);
    Route::post('/seller/trust-score', [SellerBadgeController::class, 'updateTrustScore']);

    // UC-7: Digital Closet
    Route::get('/closet', [DigitalClosetController::class, 'getCloset']);
    Route::post('/closet', [DigitalClosetController::class, 'addToCloset']);
    Route::post('/closet/{id}/list', [DigitalClosetController::class, 'listItem']);
    Route::delete('/closet/{id}', [DigitalClosetController::class, 'removeFromCloset']);
    Route::post('/closet/{id}/swap-invite', [DigitalClosetController::class, 'createSwapInvite']);

    // UC-8: Transformation (Before/After)
    Route::get('/items/{id}/transformation', [TransformationController::class, 'getTransformation']);
    Route::post('/items/{id}/transformation', [TransformationController::class, 'saveTransformation']);

    // UC-2: Impact Calculation (CO2/Water)
    Route::post('/impact/calculate', [TransformationController::class, 'calculateImpact']);

    // UC-11: Item Locking (sale/swap)
    Route::post('/items/{id}/lock', [ItemLockController::class, 'lockItem']);
    Route::post('/items/{id}/unlock', [ItemLockController::class, 'unlockItem']);
    Route::post('/transactions/auto-cancel', [ItemLockController::class, 'autoCancelExpiredTransactions']);

    // UC-12: Prohibited item validation
    Route::post('/items/validate', [ItemLockController::class, 'validateItem']);

    // UC-19: Lock item after agreement signed
    Route::post('/transactions/{id}/lock-item', [ItemLockController::class, 'lockItemForAgreement']);

    // UC-15, UC-16, UC-17: Swap Bundle Management
    Route::post('/swap/proposal', [SwapBundleController::class, 'createProposal']);
    Route::post('/swap/calculate-topup', [SwapBundleController::class, 'calculateTopUp']);
    Route::post('/swap/bundle/update', [SwapBundleController::class, 'updateBundle']);
    Route::post('/swap/bargaining/thresholds', [SwapBundleController::class, 'setBargainingThresholds']);
    Route::post('/swap/bargaining/check', [SwapBundleController::class, 'checkOfferAgainstThresholds']);

    // UC-14: Care Instructions
    Route::post('/care-instructions/generate', [CareInstructionController::class, 'generateCareInstructions']);

    // UC-13: Bulk Listings
    Route::get('/bulk/items', [BulkListingController::class, 'getMyItemsForBulk']);
    Route::post('/bulk/attributes', [BulkListingController::class, 'applyBulkAttributes']);
    Route::post('/bulk/create-similar', [BulkListingController::class, 'bulkCreateSimilar']);

    // UC-21: Geospatial - Find nearby users for in-person swaps
    Route::post('/geospatial/nearby-users', [GeospatialController::class, 'findNearbyUsers']);
    Route::post('/geospatial/nearby-items', [GeospatialController::class, 'findNearbyItems']);
    Route::get('/geospatial/settings', [GeospatialController::class, 'getLocationSettings']);
    Route::put('/geospatial/settings', [GeospatialController::class, 'setLocation']);

    // UC-22: Escrow Payment - Virtual Vault
    Route::post('/escrow/create', [EscrowController::class, 'createEscrow']);
    Route::post('/escrow/{escrowId}/release', [EscrowController::class, 'verifyAndRelease']);
    Route::post('/escrow/{escrowId}/dispute-resolution', [EscrowController::class, 'releaseAfterDispute']);
    Route::post('/escrow/{escrowId}/payout', [EscrowController::class, 'schedulePayout']);
    Route::get('/escrow/vault-balance', [EscrowController::class, 'getVaultBalance'] ?? function() { return response()->json(['balance' => 0]); });

    // UC-23: Dynamic Platform Fees
    Route::get('/fees/calculate', [EscrowController::class, 'calculatePlatformFee']);

    // UC-24: Shipping Labels and Tracking
    Route::post('/shipping/generate-label', [ShippingController::class, 'generateTracking']);
    Route::get('/shipping/track/{trackingNumber}', [ShippingController::class, 'getTrackingStatus']);

    // UC-25: Reverse Logistics - Returns
    Route::post('/returns/initiate', [ShippingController::class, 'initiateReturn']);
    Route::post('/returns/{returnId}/process', [ShippingController::class, 'processReturn']);

    // UC-26: Bundle Discounts
    Route::post('/discounts/bundle', [ShippingController::class, 'calculateBundleDiscount']);

    // UC-28: Currency Conversion
    Route::post('/currency/convert', [EscrowController::class, 'convertCurrency']);

    // UC-29: Dispute Mediation Hub (Admin)
    Route::get('/admin/disputes', [DisputeController::class, 'getAllDisputes']);
    Route::get('/admin/disputes/{disputeId}', [DisputeController::class, 'getDisputeDetails']);
    Route::post('/admin/disputes/{disputeId}/resolve', [DisputeController::class, 'resolveDispute']);
    Route::post('/admin/disputes/{disputeId}/attach-logs', [DisputeController::class, 'attachChatLogs']);

    // UC-30: Style Boards - Collaborative Curation
    Route::get('/style-boards/public', [StyleBoardController::class, 'getPublicBoards']);
    Route::get('/style-boards/{boardId}/details', [StyleBoardController::class, 'getBoardDetails']);
    Route::post('/style-boards/{boardId}/follow', [StyleBoardController::class, 'followBoard']);
    Route::post('/style-boards/{boardId}/collaborators', [StyleBoardController::class, 'addCollaborator']);
    Route::get('/style-boards/followed', [StyleBoardController::class, 'getFollowedBoards']);
    Route::get('/style-boards/user/{userId}', [StyleBoardController::class, 'getUserBoards']);
    Route::put('/style-boards/{boardId}', [StyleBoardController::class, 'updateBoard']);
    Route::delete('/style-boards/{boardId}', [StyleBoardController::class, 'deleteBoard']);

    // UC-31: Live Drop Notifications
    Route::post('/drops/create', [NotificationController::class, 'createDrop']);
    Route::get('/drops/active', [NotificationController::class, 'getActiveDrops']);
    Route::post('/sellers/follow', [NotificationController::class, 'followSeller']);
    Route::get('/drops/subscriptions', [NotificationController::class, 'getDropSubscriptions']);
    Route::get('/notifications', [NotificationController::class, 'getNotifications']);
    Route::post('/notifications/read', [NotificationController::class, 'markNotificationRead']);

    // UC-32: Seller Performance Analytics
    Route::get('/analytics/seller', [AnalyticsController::class, 'getSellerAnalytics']);
    Route::get('/analytics/charts', [AnalyticsController::class, 'getChartData']);

    // UC-33: Nested Comment Threads
    Route::get('/items/{itemId}/comments', [CommentController::class, 'getComments']);
    Route::post('/comments', [CommentController::class, 'addComment']);
    Route::post('/comments/{commentId}/reply', [CommentController::class, 'replyToComment']);
    Route::post('/comments/{commentId}/like', [CommentController::class, 'likeComment']);
    Route::post('/comments/{commentId}/report', [CommentController::class, 'reportComment']);
    Route::delete('/comments/{commentId}', [CommentController::class, 'deleteComment']);

    // UC-34: Multi-stage Reporting with Shadow-bans
    Route::post('/reports', [ReportController::class, 'createReport']);
    Route::get('/admin/reports', [ReportController::class, 'getAllReports']);
    Route::get('/admin/reports/{reportId}', [ReportController::class, 'getReportDetails']);
    Route::post('/admin/reports/{reportId}/escalate', [ReportController::class, 'escalateReport']);
    Route::post('/admin/shadow-ban', [ReportController::class, 'applyShadowBan']);
    Route::delete('/admin/shadow-ban/{userId}', [ReportController::class, 'removeShadowBan']);

    // UC-35: Mentorship Program
    Route::post('/mentorship/request', [MentorshipController::class, 'requestMentor']);
    Route::post('/mentorship/apply', [MentorshipController::class, 'applyAsMentor']);
    Route::get('/mentorship/mentors', [MentorshipController::class, 'getMentorRecommendations']);
    Route::post('/mentorship/match', [MentorshipController::class, 'requestMatch']);
    Route::post('/mentorship/match/{matchId}/respond', [MentorshipController::class, 'respondToMatch']);
    Route::get('/mentorship/active', [MentorshipController::class, 'getActiveMentorships']);
    Route::post('/mentorship/session', [MentorshipController::class, 'scheduleSession']);

    // UC-37: Dynamic Commission
    Route::post('/admin/commission/set', [AdminController::class, 'setCommissionModifier']);
    Route::get('/admin/commission/modifiers', [AdminController::class, 'getCommissionModifiers']);
    Route::get('/fees/effective', [AdminController::class, 'calculateEffectiveFee']);

    // UC-38: Sustainability Audit
    Route::get('/admin/audit/sustainability', [AdminController::class, 'getSustainabilityAudit']);
    Route::get('/admin/audit/export', [AdminController::class, 'exportAuditReport']);

    // UC-39: RBAC
    Route::get('/admin/roles', [AdminController::class, 'getRoles']);
    Route::post('/admin/roles/assign', [AdminController::class, 'assignRole']);
    Route::post('/admin/roles/check/{userId}', [AdminController::class, 'checkPermissions']);
    Route::post('/admin/roles/create', [AdminController::class, 'createRole']);

    // UC-40: System Health Monitoring
    Route::get('/admin/health', [AnalyticsController::class, 'getSystemHealth']);
    Route::get('/admin/health/failures', [AnalyticsController::class, 'getTransactionFailures']);
    Route::get('/admin/health/latency', [AnalyticsController::class, 'getListingLatency']);

    // UC-41: Newsletter Curation
    Route::post('/newsletter/send', [NewsletterController::class, 'sendNewsletter']);

    // UC-42: Database Cleanup & Archiving
    Route::get('/cleanup/status', [DatabaseCleanupController::class, 'getCleanupStatus']);
    Route::post('/cleanup/run', [DatabaseCleanupController::class, 'runCleanup']);
    Route::post('/cleanup/archive', [DatabaseCleanupController::class, 'archiveTransactions']);
    Route::get('/cleanup/archived', [DatabaseCleanupController::class, 'getArchivedTransactions']);
    Route::post('/cleanup/archived/{transactionId}/restore', [DatabaseCleanupController::class, 'restoreTransaction']);
    Route::post('/cleanup/orphaned', [DatabaseCleanupController::class, 'cleanupOrphaned']);
    Route::get('/cleanup/health', [DatabaseCleanupController::class, 'getDatabaseHealth']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});