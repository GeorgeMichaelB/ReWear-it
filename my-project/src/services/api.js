import axios from 'axios';

const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api';

const api = axios.create({
  baseURL: API_URL,
  headers: {
    'Content-Type': 'application/json',
  },
});

api.interceptors.request.use((config) => {
  const token = localStorage.getItem('token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      localStorage.removeItem('token');
      localStorage.removeItem('user');
      window.location.href = '/login';
    }
    return Promise.reject(error);
  }
);

// Auth API
export const authAPI = {
  register: (data) => api.post('/auth/register', data),
  login: (data) => api.post('/auth/login', data),
  logout: () => api.post('/auth/logout'),
  getUser: () => api.get('/auth/user'),
  updateProfile: (data) => api.put('/auth/profile', data),
  changePassword: (data) => api.put('/auth/password', data),
  // Advanced Auth Functions (1-5)
  getEcoCreditsHistory: () => api.get('/auth/eco-credits'),
  getTrustScoreDetails: () => api.get('/auth/trust-score'),
  updateAvatar: (data) => api.put('/auth/avatar', data),
  getActivityLog: () => api.get('/auth/activity'),
  getReferralCode: () => api.get('/auth/referral'),
};

// Categories API
export const categoriesAPI = {
  getAll: () => api.get('/categories'),
  getOne: (id) => api.get(`/categories/${id}`),
  create: (data) => api.post('/categories', data),
  update: (id, data) => api.put(`/categories/${id}`, data),
  delete: (id) => api.delete(`/categories/${id}`),
  // Advanced Category Functions (15-20)
  getCategoryTree: () => api.get('/categories/tree'),
  getCategoryStats: () => api.get('/categories/stats'),
  getPopularCategories: () => api.get('/categories/popular'),
  getBySlug: (slug) => api.get(`/categories/slug/${slug}`),
  getBreadcrumb: (id) => api.get(`/categories/${id}/breadcrumb`),
  getSubcategories: (id) => api.get(`/categories/${id}/subcategories`),
};

// Items API
export const itemsAPI = {
  getAll: (params) => api.get('/items', { params }),
  getOne: (id) => api.get(`/items/${id}`),
  create: (data) => api.post('/items', data),
  update: (id, data) => api.put(`/items/${id}`, data),
  delete: (id) => api.delete(`/items/${id}`),
  getMyItems: () => api.get('/items/my'),
  carbonSavings: (id) => api.get(`/items/${id}/carbon-savings`),
  // Advanced Item Functions (6-14)
  getCarbonFootprint: (id) => api.get(`/items/${id}/carbon-footprint`),
  getSimilarItems: (id) => api.get(`/items/${id}/similar`),
  getItemStatistics: (id) => api.get(`/items/${id}/statistics`),
  getDepreciation: (id) => api.get(`/items/${id}/depreciation`),
  shareItem: (id) => api.get(`/items/${id}/share`),
  markSwapReady: (id) => api.post(`/items/${id}/mark-swap-ready`),
  addToStyleBoard: (itemId, styleBoardId) => api.post(`/items/${itemId}/style-board`, { style_board_id: styleBoardId }),
  reportItem: (id, data) => api.post(`/items/${id}/report`, data),
  bulkUpdateStatus: (data) => api.post('/items/bulk-status', data),
};

// Orders API
export const ordersAPI = {
  getAll: () => api.get('/orders'),
  getSellerOrders: () => api.get('/orders/seller'),
  getOne: (id) => api.get(`/orders/${id}`),
  create: (data) => api.post('/orders', data),
  updateStatus: (id, status) => api.put(`/orders/${id}/status`, { status }),
  // Advanced Order Functions (21-30)
  calculateShipping: (data) => api.post('/orders/shipping-estimate', data),
  getOrderTimeline: (id) => api.get(`/orders/${id}/timeline`),
  trackShipment: (id) => api.get(`/orders/${id}/track`),
  calculateBundleDiscount: (itemCount) => api.post('/orders/bundle-discount', { item_count: itemCount }),
  estimateOrderValue: (itemIds) => api.get('/orders/estimate-value', { params: { item_ids: itemIds } }),
  getTransactionAnalytics: () => api.get('/analytics/transactions'),
  // Swap Agreement Functions (22-24)
  createSwapAgreement: (data) => api.post('/swaps/agreement', data),
  cancelSwapRequest: (id) => api.post(`/swaps/${id}/cancel`),
  rateSwap: (id, data) => api.post(`/swaps/${id}/rate`, data),
  getSwapHistory: () => api.get('/swaps/history'),
};

// Favorites API
export const favoritesAPI = {
  getAll: () => api.get('/favorites'),
  add: (productId) => api.post('/favorites', { product_id: productId }),
  remove: (productId) => api.delete(`/favorites/${productId}`),
  check: (productId) => api.get(`/favorites/check/${productId}`),
};

// Addresses API
export const addressesAPI = {
  getAll: () => api.get('/addresses'),
  getOne: (id) => api.get(`/addresses/${id}`),
  create: (data) => api.post('/addresses', data),
  update: (id, data) => api.put(`/addresses/${id}`, data),
  delete: (id) => api.delete(`/addresses/${id}`),
};

// Style Boards API
export const styleBoardsAPI = {
  getAll: () => api.get('/style-boards'),
  getMy: () => api.get('/style-boards/my'),
  create: (data) => api.post('/style-boards', data),
  update: (id, data) => api.put(`/style-boards/${id}`, data),
  delete: (id) => api.delete(`/style-boards/${id}`),
  addItem: (id, itemId) => api.post(`/style-boards/${id}/items`, { item_id: itemId }),
  removeItem: (id, itemId) => api.delete(`/style-boards/${id}/items/${itemId}`),
};

// Transactions API
export const transactionsAPI = {
  getAll: () => api.get('/transactions'),
  getOne: (id) => api.get(`/transactions/${id}`),
  create: (data) => api.post('/transactions', data),
  cancel: (id) => api.post(`/transactions/${id}/cancel`),
  complete: (id) => api.post(`/transactions/${id}/complete`),
};

// Reviews API
export const reviewsAPI = {
  getAll: () => api.get('/reviews'),
  create: (data) => api.post('/reviews', data),
};

// Material Categories API
export const materialCategoriesAPI = {
  getAll: () => api.get('/material-categories'),
};

// Seller Badges API (UC-1)
export const sellerBadgesAPI = {
  getBadges: () => api.get('/seller/badges'),
  updateBadges: (badges) => api.post('/seller/badges', { badges }),
  verifyEco: (code) => api.post('/seller/verify-eco', { verification_code: code }),
  getTrustScore: () => api.get('/seller/trust-score'),
  updateTrustScore: (data) => api.post('/seller/trust-score', data),
};

// Digital Closet API (UC-7)
export const digitalClosetAPI = {
  getCloset: () => api.get('/closet'),
  addToCloset: (data) => api.post('/closet', data),
  listItem: (itemId, data) => api.post(`/closet/${itemId}/list`, data),
  removeFromCloset: (id) => api.delete(`/closet/${id}`),
  createSwapInvite: (id) => api.post(`/closet/${id}/swap-invite`),
};

// Transformation API (UC-8)
export const transformationAPI = {
  getTransformation: (id) => api.get(`/items/${id}/transformation`),
  saveTransformation: (id, data) => api.post(`/items/${id}/transformation`, data),
};

// Impact Calculation API (UC-2)
export const impactAPI = {
  calculate: (data) => api.post('/impact/calculate', data),
};

// Item Locking API (UC-11, UC-19)
export const itemLockAPI = {
  lockItem: (id, type) => api.post(`/items/${id}/lock`, { type }),
  unlockItem: (id) => api.post(`/items/${id}/unlock`),
  validateItem: (data) => api.post('/items/validate', data),
};

// Swap Bundle API (UC-15, UC-16, UC-17, UC-18)
export const swapBundleAPI = {
  createProposal: (data) => api.post('/swap/proposal', data),
  calculateTopUp: (data) => api.post('/swap/calculate-topup', data),
  updateBundle: (data) => api.post('/swap/bundle/update', data),
  setThresholds: (data) => api.post('/swap/bargaining/thresholds', data),
  checkOffer: (data) => api.post('/swap/bargaining/check', data),
};

// Care Instructions API (UC-14)
export const careInstructionsAPI = {
  generate: (materials, condition) => api.post('/care-instructions/generate', { materials, condition }),
};

// Bulk Listing API (UC-13)
export const bulkListingAPI = {
  getMyItems: () => api.get('/bulk/items'),
  applyAttributes: (itemIds, attributes) => api.post('/bulk/attributes', { item_ids: itemIds, attributes }),
  bulkCreateSimilar: (data) => api.post('/bulk/create-similar', data),
};

// Geospatial API (UC-21: Nearby users for in-person swaps)
export const geospatialAPI = {
  findNearbyUsers: (latitude, longitude, radiusKm) =>
    api.post('/geospatial/nearby-users', { latitude, longitude, radius_km: radiusKm }),
  findNearbyItems: (latitude, longitude, radiusKm) =>
    api.post('/geospatial/nearby-items', { latitude, longitude, radius_km: radiusKm }),
  getLocationSettings: () => api.get('/geospatial/settings'),
  setLocation: (latitude, longitude, city) =>
    api.put('/geospatial/settings', { latitude, longitude, city }),
};

// Escrow API (UC-22, UC-23, UC-27, UC-28)
export const escrowAPI = {
  createEscrow: (data) => api.post('/escrow/create', data),
  releaseFunds: (escrowId, verified) =>
    api.post(`/escrow/${escrowId}/release`, { verified }),
  resolveDispute: (escrowId, resolution) =>
    api.post(`/escrow/${escrowId}/dispute-resolution`, { resolution }),
  schedulePayout: (escrowId, payoutSchedule) =>
    api.post(`/escrow/${escrowId}/payout`, { payout_schedule: payoutSchedule }),
  calculateFee: (amount, itemType) =>
    api.get('/fees/calculate', { params: { amount, item_type: itemType } }),
  convertCurrency: (amount, fromCurrency, toCurrency) =>
    api.post('/currency/convert', { amount, from_currency: fromCurrency, to_currency: toCurrency }),
};

// Shipping API (UC-24, UC-25, UC-26)
export const shippingAPI = {
  generateLabel: (data) => api.post('/shipping/generate-label', data),
  trackShipment: (trackingNumber) => api.get(`/shipping/track/${trackingNumber}`),
  initiateReturn: (data) => api.post('/returns/initiate', data),
  processReturn: (returnId, data) => api.post(`/returns/${returnId}/process`, data),
  calculateBundleDiscount: (itemPrices, sellerId) =>
    api.post('/discounts/bundle', { item_prices: itemPrices, seller_id: sellerId }),
};

// Dispute API (UC-29)
export const disputeAPI = {
  createDispute: (data) => api.post('/disputes', data),
  getAllDisputes: (status) => api.get('/admin/disputes', { params: { status } }),
  getDisputeDetails: (disputeId) => api.get(`/admin/disputes/${disputeId}`),
  resolveDispute: (disputeId, data) => api.post(`/admin/disputes/${disputeId}/resolve`, data),
  attachChatLogs: (disputeId, chatLogs) =>
    api.post(`/admin/disputes/${disputeId}/attach-logs`, { chat_logs: chatLogs }),
};

// Style Board API (UC-30)
export const styleBoardAPI = {
  getPublicBoards: () => api.get('/style-boards/public'),
  getBoardDetails: (boardId) => api.get(`/style-boards/${boardId}/details`),
  createBoard: (data) => api.post('/style-boards', data),
  updateBoard: (boardId, data) => api.put(`/style-boards/${boardId}`, data),
  deleteBoard: (boardId) => api.delete(`/style-boards/${boardId}`),
  addItem: (boardId, itemId) => api.post(`/style-boards/${boardId}/items`, { item_id: itemId }),
  removeItem: (boardId, itemId) => api.delete(`/style-boards/${boardId}/items/${itemId}`),
  followBoard: (boardId) => api.post(`/style-boards/${boardId}/follow`),
  addCollaborator: (boardId, userId) =>
    api.post(`/style-boards/${boardId}/collaborators`, { user_id: userId }),
  getFollowedBoards: () => api.get('/style-boards/followed'),
  getUserBoards: (userId) => api.get(`/style-boards/user/${userId}`),
};

// Notification API (UC-31: Live drops)
export const notificationsAPI = {
  createDrop: (data) => api.post('/drops/create', data),
  getActiveDrops: () => api.get('/drops/active'),
  followSeller: (data) => api.post('/sellers/follow', data),
  getDropSubscriptions: () => api.get('/drops/subscriptions'),
  getNotifications: () => api.get('/notifications'),
  markAsRead: (data) => api.post('/notifications/read', data),
};

// Analytics API (UC-32, UC-40)
export const analyticsAPI = {
  getSellerAnalytics: (params) => api.get('/analytics/seller', { params }),
  getChartData: (type) => api.get('/analytics/charts', { params: { type } }),
  getSystemHealth: () => api.get('/admin/health'),
  getTransactionFailures: () => api.get('/admin/health/failures'),
  getListingLatency: () => api.get('/admin/health/latency'),
};

// Comments API (UC-33)
export const commentsAPI = {
  getComments: (itemId) => api.get(`/items/${itemId}/comments`),
  addComment: (data) => api.post('/comments', data),
  replyToComment: (commentId, content) => api.post(`/comments/${commentId}/reply`, { content }),
  likeComment: (commentId) => api.post(`/comments/${commentId}/like`),
  reportComment: (commentId, reason) => api.post(`/comments/${commentId}/report`, { reason }),
  deleteComment: (commentId) => api.delete(`/comments/${commentId}`),
};

// Reports API (UC-34)
export const reportsAPI = {
  createReport: (data) => api.post('/reports', data),
  getAllReports: () => api.get('/admin/reports'),
  getReportDetails: (reportId) => api.get(`/admin/reports/${reportId}`),
  escalateReport: (reportId) => api.post(`/admin/reports/${reportId}/escalate`),
  applyShadowBan: (data) => api.post('/admin/shadow-ban', data),
  removeShadowBan: (userId) => api.delete(`/admin/shadow-ban/${userId}`),
};

// Mentorship API (UC-35)
export const mentorshipAPI = {
  requestMentor: (data) => api.post('/mentorship/request', data),
  applyAsMentor: (data) => api.post('/mentorship/apply', data),
  getMentorRecommendations: (params) => api.get('/mentorship/mentors', { params }),
  requestMatch: (data) => api.post('/mentorship/match', data),
  respondToMatch: (matchId, action) => api.post(`/mentorship/match/${matchId}/respond`, { action }),
  getActiveMentorships: () => api.get('/mentorship/active'),
  scheduleSession: (data) => api.post('/mentorship/session', data),
};

// Market Trends API (UC-36)
export const marketTrendsAPI = {
  getMaterialTrends: (period) => api.get('/trends/materials', { params: { period } }),
  getCategoryPerformance: () => api.get('/trends/categories'),
  getPriceRecommendations: (data) => api.get('/trends/pricing', { params: data }),
  getSeasonalTrends: (season) => api.get('/trends/seasonal', { params: { season } }),
};

// Admin API (UC-37, UC-38, UC-39)
export const adminAPI = {
  setCommissionModifier: (data) => api.post('/admin/commission/set', data),
  getCommissionModifiers: () => api.get('/admin/commission/modifiers'),
  calculateEffectiveFee: (data) => api.get('/fees/effective', { params: data }),
  getSustainabilityAudit: (period) => api.get('/admin/audit/sustainability', { params: { period } }),
  exportAuditReport: (format) => api.get('/admin/audit/export', { params: { format } }),
  getRoles: () => api.get('/admin/roles'),
  assignRole: (data) => api.post('/admin/roles/assign', data),
  checkPermissions: (userId) => api.post(`/admin/roles/check/${userId}`),
  createRole: (data) => api.post('/admin/roles/create', data),
};

// Health API (UC-40)
export const healthAPI = {
  getSystemHealth: () => api.get('/admin/health'),
};

// Newsletter API (UC-41)
export const newsletterAPI = {
  generateWeekly: () => api.get('/newsletter/generate'),
  sendNewsletter: (data) => api.post('/newsletter/send', data),
  getSubscribers: () => api.get('/newsletter/subscribers'),
  subscribe: (data) => api.post('/newsletter/subscribe', data),
  getPast: () => api.get('/newsletter/past'),
};

// Database Cleanup API (UC-42)
export const cleanupAPI = {
  getStatus: () => api.get('/cleanup/status'),
  runCleanup: (data) => api.post('/cleanup/run', data),
  archiveTransactions: (data) => api.post('/cleanup/archive', data),
  getArchived: () => api.get('/cleanup/archived'),
  restoreTransaction: (transactionId) => api.post(`/cleanup/archived/${transactionId}/restore`),
  cleanupOrphaned: () => api.post('/cleanup/orphaned'),
  getHealth: () => api.get('/cleanup/health'),
};

export default api;
