import React, { useState, useEffect } from 'react';
import { digitalClosetAPI, categoriesAPI } from '../services/api';
import './DigitalCloset.css';

const DigitalCloset = () => {
  const [closetItems, setClosetItems] = useState([]);
  const [categories, setCategories] = useState([]);
  const [loading, setLoading] = useState(true);
  const [showAddForm, setShowAddForm] = useState(false);
  const [showListForm, setShowListForm] = useState(null);
  const [formData, setFormData] = useState({
    title: '',
    description: '',
    condition: 'good',
    size: '',
    material: '',
    category_id: '',
  });
  const [listData, setListData] = useState({ price: '', category_id: '' });

  useEffect(() => {
    fetchCloset();
    fetchCategories();
  }, []);

  const fetchCloset = async () => {
    try {
      const response = await digitalClosetAPI.getCloset();
      setClosetItems(response.data.closet_items || []);
    } catch (error) {
      console.error('Error fetching closet:', error);
    } finally {
      setLoading(false);
    }
  };

  const fetchCategories = async () => {
    try {
      const response = await categoriesAPI.getAll();
      setCategories(response.data.data || response.data);
    } catch (error) {
      console.error('Error fetching categories:', error);
    }
  };

  const handleAddSubmit = async (e) => {
    e.preventDefault();
    try {
      await digitalClosetAPI.addToCloset(formData);
      setShowAddForm(false);
      setFormData({ title: '', description: '', condition: 'good', size: '', material: '', category_id: '' });
      fetchCloset();
    } catch (error) {
      alert('Error adding to closet');
    }
  };

  const handleListSubmit = async (e, itemId) => {
    e.preventDefault();
    try {
      await digitalClosetAPI.listItem(itemId, { ...listData, item_id: itemId });
      setShowListForm(null);
      setListData({ price: '', category_id: '' });
      fetchCloset();
      alert('Item listed on marketplace!');
    } catch (error) {
      alert('Error listing item');
    }
  };

  const handleRemove = async (id) => {
    if (confirm('Remove this item from your closet?')) {
      try {
        await digitalClosetAPI.removeFromCloset(id);
        fetchCloset();
      } catch (error) {
        alert('Error removing item');
      }
    }
  };

  const handleCreateSwap = async (id) => {
    try {
      const response = await digitalClosetAPI.createSwapInvite(id);
      alert(`Swap invitation created! Share this link: ${response.data.invite_link}`);
    } catch (error) {
      alert('Error creating swap invite');
    }
  };

  if (loading) return <div className="loading">Loading...</div>;

  return (
    <div className="digital-closet-page">
      <h2>👗 Digital Closet</h2>
      <p className="page-desc">Items you own but haven't listed yet</p>

<button className="btn btn-primary" onClick={() => setShowAddForm(true)}>
  + Add to Closet
</button>

      {showAddForm && (
        <div className="modal">
          <div className="modal-content">
            <h3>Add Item to Closet</h3>
            <form onSubmit={handleAddSubmit}>
              <div className="form-group">
                <label>Title *</label>
                <input
                  type="text"
                  value={formData.title}
                  onChange={(e) => setFormData({ ...formData, title: e.target.value })}
                  required
                />
              </div>
              <div className="form-group">
                <label>Description</label>
                <textarea
                  value={formData.description}
                  onChange={(e) => setFormData({ ...formData, description: e.target.value })}
                />
              </div>
              <div className="form-group">
                <label>Condition</label>
                <select value={formData.condition} onChange={(e) => setFormData({ ...formData, condition: e.target.value })}>
                  <option value="new">New</option>
                  <option value="like_new">Like New</option>
                  <option value="good">Good</option>
                  <option value="fair">Fair</option>
                  <option value="worn">Worn</option>
                </select>
              </div>
              <div className="form-group">
                <label>Size</label>
                <input
                  type="text"
                  value={formData.size}
                  onChange={(e) => setFormData({ ...formData, size: e.target.value })}
                  placeholder="e.g., S, M, L, 32"
                />
              </div>
              <div className="form-group">
                <label>Material</label>
                <select value={formData.material} onChange={(e) => setFormData({ ...formData, material: e.target.value })}>
                  <option value="">Select material</option>
                  <option value="cotton">Cotton</option>
                  <option value="polyester">Polyester</option>
                  <option value="wool">Wool</option>
                  <option value="linen">Linen</option>
                  <option value="silk">Silk</option>
                  <option value="denim">Denim</option>
                  <option value="leather">Leather</option>
                </select>
              </div>
<div className="form-actions">
  <button type="submit" className="btn btn-primary">Add to Closet</button>
  <button type="button" className="btn btn-secondary" onClick={() => setShowAddForm(false)}>Cancel</button>
</div>
            </form>
          </div>
        </div>
      )}

      {closetItems.length === 0 ? (
        <p className="empty-closet">Your digital closet is empty. Add some items!</p>
      ) : (
        <div className="closet-grid">
          {closetItems.map((item) => (
            <div key={item.id} className="closet-item">
              <h4>{item.title}</h4>
              <p>{item.condition} {item.size && `| Size: ${item.size}`}</p>
              {item.material && <span className="badge">{item.material}</span>}
              
              <div className="item-actions">
                {showListForm === item.id ? (
                  <form onSubmit={(e) => handleListSubmit(e, item.id)} className="list-form">
                    <input
                      type="number"
                      placeholder="Price ($)"
                      value={listData.price}
                      onChange={(e) => setListData({ ...listData, price: e.target.value })}
                      required
                    />
                    <select
                      value={listData.category_id}
                      onChange={(e) => setListData({ ...listData, category_id: e.target.value })}
                      required
                    >
                      <option value="">Category</option>
                      {categories.map((cat) => (
                        <option key={cat.id} value={cat.id}>{cat.name}</option>
                      ))}
                    </select>
                    <button type="submit" className="btn-primary">List</button>
                    <button type="button" onClick={() => setShowListForm(null)} className="btn-secondary">Cancel</button>
                  </form>
                ) : (
                  <>
                    <button onClick={() => setShowListForm(item.id)}>List on Market</button>
                    <button onClick={() => handleCreateSwap(item.id)}>Create Swap Invite</button>
                    <button onClick={() => handleRemove(item.id)} className="btn-danger">Remove</button>
                  </>
                )}
              </div>
            </div>
          ))}
        </div>
      )}
    </div>
  );
};

export default DigitalCloset;