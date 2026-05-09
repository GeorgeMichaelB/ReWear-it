import React, { useState, useEffect } from 'react';
import { itemsAPI, categoriesAPI } from '../services/api';
import { useAuth } from '../context/AuthContext';
import './Products.css';

const Products = ({ addToSwaps }) => {
  const [items, setItems] = useState([]);
  const [categories, setCategories] = useState([]);
  const [loading, setLoading] = useState(true);
  const [selectedCategory, setSelectedCategory] = useState(null);
  const [searchTerm, setSearchTerm] = useState('');
  const { user } = useAuth();

  useEffect(() => {
    fetchCategories();
    fetchItems();
  }, []);

  const fetchCategories = async () => {
    try {
      const response = await categoriesAPI.getAll();
      setCategories(response.data.data || response.data);
    } catch (error) {
      console.error('Error fetching categories:', error);
    }
  };

  const fetchItems = async (params = {}) => {
    try {
      setLoading(true);
      const response = await itemsAPI.getAll(params);
      setItems(response.data.data || response.data);
    } catch (error) {
      console.error('Error fetching items:', error);
    } finally {
      setLoading(false);
    }
  };

  const handleCategoryClick = (categoryId) => {
    setSelectedCategory(categoryId);
    const params = categoryId ? { category_id: categoryId } : {};
    fetchItems(params);
  };

  const handleSearch = (e) => {
    e.preventDefault();
    const params = {};
    if (searchTerm) params.search = searchTerm;
    if (selectedCategory) params.category_id = selectedCategory;
    fetchItems(params);
  };

  const handleAddToSwap = (item) => {
    addToSwaps({
      id: item.id,
      title: item.title,
      price: item.price,
      carbon_savings: item.carbon_savings,
    });
  };

  if (loading) return <div className="loading">Loading...</div>;

  return (
    <div className="products-page">
      <div className="products-sidebar">
        <h3>Categories</h3>
        <ul className="category-list">
          <li
            className={selectedCategory === null ? 'active' : ''}
            onClick={() => handleCategoryClick(null)}
          >
            All Items
          </li>
          {categories.map((cat) => (
            <li
              key={cat.id}
              className={selectedCategory === cat.id ? 'active' : ''}
              onClick={() => handleCategoryClick(cat.id)}
            >
              {cat.name}
            </li>
          ))}
        </ul>

        <div className="search-box">
          <form className="search-box" onSubmit={handleSearch}>
            <input
              type="text"
              placeholder="Search items..."
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
            />
            <button type="submit">Search</button>
          </form>
        </div>
      </div>

      <div className="products-main">
        <h2>
          {selectedCategory
            ? categories.find((c) => c.id === selectedCategory)?.name
            : 'All Items'}
          <span className="item-count">({items.length} items)</span>
        </h2>

        {items.length === 0 ? (
          <p className="no-items">No items found.</p>
        ) : (
          <div className="items-grid">
            {items.map((item) => (
              <div key={item.id} className="item-card">
                <div className="item-image">
                  <img src={`https://picsum.photos/seed/${item.id}/400/500`} alt={item.title} />
                </div>
                <div className="item-info">
                  <h3>{item.title}</h3>
                  <p className="description">{item.description?.substring(0, 80)}...</p>
                  <p className="price">${item.price}</p>
                  <div className="item-meta">
                    <span className="condition">{item.condition}</span>
                    {item.carbon_savings > 0 && (
                      <span className="carbon-badge">🌱 {item.carbon_savings}kg</span>
                    )}
                  </div>
<button
  className="btn btn-secondary"
  onClick={() => handleAddToSwap(item)}
>
  Add to Swap
</button>
                </div>
              </div>
            ))}
          </div>
        )}
      </div>
    </div>
  );
};

export default Products;
