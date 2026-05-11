import React, { useState, useEffect } from 'react';
import { itemsAPI, categoriesAPI } from '../services/api';
import { useAuth } from '../context/AuthContext';
import './MyItems.css';

const MyItems = () => {
  const { user } = useAuth();
  const [items, setItems] = useState([]);
  const [statusFilter, setStatusFilter] = useState('all');
  const [categories, setCategories] = useState([]);
  const [loading, setLoading] = useState(true);
  const [showForm, setShowForm] = useState(false);
  const [editingItem, setEditingItem] = useState(null);
  const [formData, setFormData] = useState({
    title: '',
    description: '',
    price: '',
    category_id: '',
    condition: 'good',
    status: 'available',
    carbon_savings: '',
  });

  useEffect(() => {
    if (user) {
      fetchData();
    }
  }, [user]);

  const defaultCategories = [
    { id: 1, name: 'Tops' },
    { id: 2, name: 'Bottoms' },
    { id: 3, name: 'Dresses' },
    { id: 4, name: 'Outerwear' },
    { id: 5, name: 'Footwear' },
    { id: 6, name: 'Accessories' },
  ];

  const fetchData = async (isSilent = false) => {
    if (!isSilent) setLoading(true);
    try {
      const itemsRes = await itemsAPI.getAll();
      const catsRes = await categoriesAPI.getAll();
      
      let allItems = [];
      let catsData = [];
      
      if (Array.isArray(itemsRes.data)) {
        allItems = itemsRes.data;
      } else if (itemsRes.data?.data) {
        allItems = itemsRes.data.data;
      }
      
      const userId = user?.id;
      const myItems = userId ? allItems.filter(item => item.seller_id === userId) : [];
      
      if (Array.isArray(catsRes.data)) {
        catsData = catsRes.data;
      } else if (catsRes.data?.data) {
        catsData = catsRes.data.data;
      }
      
      if (catsData.length === 0) {
        catsData = defaultCategories;
      }
      
      setItems(myItems);
      setCategories(catsData);
    } catch (error) {
      console.error('Error fetching data:', error);
      setCategories(defaultCategories);
    } finally {
      if (!isSilent) setLoading(false);
    }
  };

  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const data = {
        ...formData,
        price: parseFloat(formData.price),
        category_id: formData.category_id ? parseInt(formData.category_id) : null,
        carbon_savings: formData.carbon_savings ? parseFloat(formData.carbon_savings) : 0,
      };
      if (editingItem) {
        await itemsAPI.update(editingItem.id, data);
      } else {
        await itemsAPI.create(data);
      }
      setShowForm(false);
      setEditingItem(null);
      setFormData({
        title: '',
        description: '',
        price: '',
        category_id: '',
        condition: 'good',
        status: 'available',
        carbon_savings: '',
      });
      fetchData();
    } catch (error) {
      const msg = error.response?.data?.message || error.response?.data?.error || 'Error saving item';
      alert(msg);
    }
  };

  const handleEdit = (item) => {
    setEditingItem(item);
    setFormData({
      title: item.title,
      description: item.description || '',
      price: item.price,
      category_id: item.category_id || '',
      condition: item.condition || 'good',
      status: item.status || 'available',
      carbon_savings: item.carbon_savings || '',
    });
    setShowForm(true);
  };

  const handleDelete = async (id) => {
    if (confirm('Are you sure you want to delete this item?')) {
      try {
        await itemsAPI.delete(id);
        fetchData();
      } catch (error) {
        alert('Error deleting item');
      }
    }
  };

  const handleStatusChange = async (id, status) => {
    setItems(prev => prev.map(item => 
      item.id === id ? { ...item, status } : item
    ));

    try {
      await itemsAPI.update(id, { status });
      fetchData(true);
    } catch (error) {
      console.error('Error updating status:', error);
      alert('Error updating status');
      fetchData(true);
    }
  };

  const countByStatus = (status) => items.filter(i => i.status === status).length;

  const displayedItems = statusFilter === 'all' 
    ? items 
    : items.filter(i => i.status === statusFilter);

  if (loading) return <div className="loading">Loading...</div>;

  return (
    <div className="my-items-page">
      <div className="page-header">
        <h2>My Items</h2>
        <button className="btn btn-primary" onClick={() => setShowForm(true)}>
          Add New Item
        </button>
      </div>

      <div className="status-tabs">
        <button className={statusFilter === 'all' ? 'active' : ''} onClick={() => setStatusFilter('all')}>
          All ({items.length})
        </button>
        <button className={statusFilter === 'available' ? 'active' : ''} onClick={() => setStatusFilter('available')}>
          Available ({countByStatus('available')})
        </button>
        <button className={statusFilter === 'pending' ? 'active' : ''} onClick={() => setStatusFilter('pending')}>
          Pending ({countByStatus('pending')})
        </button>
        <button className={statusFilter === 'sold' ? 'active' : ''} onClick={() => setStatusFilter('sold')}>
          Sold ({countByStatus('sold')})
        </button>
      </div>

      {showForm && (
        <div className="modal">
          <div className="modal-content">
            <h3>{editingItem ? 'Edit Item' : 'Add New Item'}</h3>
            <form onSubmit={handleSubmit}>
               <div className="form-group-base">
                <label>Title</label>
                <input type="text" name="title" value={formData.title} onChange={handleChange} required />
              </div>
               <div className="form-group-base">
                <label>Description</label>
                <textarea name="description" value={formData.description} onChange={handleChange} />
              </div>
               <div className="form-group-base">
                <label>Price ($)</label>
                <input type="number" name="price" value={formData.price} onChange={handleChange} required />
              </div>
               <div className="form-group-base">
                <label>Category</label>
                <select name="category_id" value={formData.category_id} onChange={handleChange}>
                  <option value="">Select category</option>
                  {categories.map((cat) => (
                    <option key={cat.id} value={cat.id}>{cat.name}</option>
                  ))}
                </select>
              </div>
               <div className="form-group-base">
                <label>Condition</label>
                <select name="condition" value={formData.condition} onChange={handleChange}>
                  <option value="new">New</option>
                  <option value="like_new">Like New</option>
                  <option value="good">Good</option>
                  <option value="fair">Fair</option>
                </select>
              </div>
               <div className="form-group-base">
                <label>Carbon Savings (kg)</label>
                <input type="number" name="carbon_savings" value={formData.carbon_savings} onChange={handleChange} step="0.1" />
              </div>
              <div className="form-actions">
                <button type="submit" className="btn btn-primary">
                  {editingItem ? 'Update' : 'Create'}
                </button>
                <button type="button" className="btn btn-secondary" onClick={() => { setShowForm(false); setEditingItem(null); }}>
                  Cancel
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      {displayedItems.length === 0 ? (
        <p className="no-items">No items in this category.</p>
      ) : (
        <div className="items-list">
          {displayedItems.map((item) => (
            <div key={item.id} className="item-row">
              <div className="item-details">
                <h4>{item.title}</h4>
                <p>${item.price} | {item.condition} | {item.status}</p>
                {item.carbon_savings > 0 && (
                  <span className="carbon-badge">🌱 {item.carbon_savings}kg CO₂</span>
                )}
              </div>
              <div className="item-actions">
                <select value={item.status} onChange={(e) => handleStatusChange(item.id, e.target.value)}>
                  <option value="available">Available</option>
                  <option value="pending">Pending</option>
                  <option value="sold">Sold</option>
                </select>
                <button onClick={() => handleEdit(item)}>Edit</button>
                <button onClick={() => handleDelete(item.id)} className="btn-danger">Delete</button>
              </div>
            </div>
          ))}
        </div>
      )}
    </div>
  );
};

export default MyItems;
