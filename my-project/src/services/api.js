import axios from 'axios';

const API_URL = process.env.REACT_APP_API_URL || 'http://127.0.0.1:8000/api';

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

export const authAPI = {
  register: (data) => api.post('/auth/register', data),
  login: (data) => api.post('/auth/login', data),
  logout: () => api.post('/auth/logout'),
  getUser: () => api.get('/auth/user'),
  updateProfile: (data) => api.put('/auth/profile', data),
  changePassword: (data) => api.put('/auth/password', data),
};

export const categoriesAPI = {
  getAll: () => api.get('/categories'),
  getOne: (id) => api.get(`/categories/${id}`),
  create: (data) => api.post('/categories', data),
  update: (id, data) => api.put(`/categories/${id}`, data),
  delete: (id) => api.delete(`/categories/${id}`),
};

export const itemsAPI = {
  getAll: (params) => api.get('/items', { params }),
  getOne: (id) => api.get(`/items/${id}`),
  create: (data) => api.post('/items', data),
  update: (id, data) => api.put(`/items/${id}`, data),
  delete: (id) => api.delete(`/items/${id}`),
  getMyItems: () => api.get('/items/my'),
  carbonSavings: (id) => api.get(`/items/${id}/carbon-savings`),
};

export const transactionsAPI = {
  getAll: () => api.get('/transactions'),
  create: (data) => api.post('/transactions', data),
  getOne: (id) => api.get(`/transactions/${id}`),
  cancel: (id) => api.post(`/transactions/${id}/cancel`),
  complete: (id) => api.post(`/transactions/${id}/complete`),
};

export const favoritesAPI = {
  getAll: () => api.get('/favorites'),
  add: (productId) => api.post('/favorites', { product_id: productId }),
  remove: (productId) => api.delete(`/favorites/${productId}`),
};

export const materialCategoriesAPI = {
  getAll: () => api.get('/material-categories'),
};

export const styleBoardsAPI = {
  getAll: () => api.get('/style-boards'),
  getMy: () => api.get('/style-boards/my'),
  create: (data) => api.post('/style-boards', data),
  addItem: (id, itemId) => api.post(`/style-boards/${id}/items`, { item_id: itemId }),
  removeItem: (id, itemId) => api.delete(`/style-boards/${id}/items/${itemId}`),
};

export default api;