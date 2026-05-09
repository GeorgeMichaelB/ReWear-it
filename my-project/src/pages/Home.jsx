import React, { useState, useEffect } from 'react';
import { itemsAPI, categoriesAPI } from '../services/api';
import './Home.css';

const Home = ({ addToSwaps }) => {
  const [items, setItems] = useState([]);
  const [categories, setCategories] = useState([]);
  const [loading, setLoading] = useState(true);
  const [selectedCategory, setSelectedCategory] = useState(null);

  useEffect(() => {
    fetchData();
  }, []);

  const fetchData = async () => {
    try {
      const [itemsRes, catsRes] = await Promise.all([
        itemsAPI.getAll(),
        categoriesAPI.getAll(),
      ]);
      setItems(itemsRes.data.data || itemsRes.data);
      setCategories(catsRes.data.data || catsRes.data);
    } catch (error) {
      console.error('Error fetching data:', error);
    } finally {
      setLoading(false);
    }
  };

  const filteredItems = selectedCategory 
    ? items.filter(item => item.category_id === selectedCategory)
    : items;

  if (loading) return <div className="loading">Loading...</div>;

  return (
    <div className="home-page">
      <section className="hero">
        <h1>Welcome to ReWear-it</h1>
        <p>Sustainable fashion through swapping and recycling</p>
        <a href="/products" className="btn btn-primary">Browse Items</a>
      </section>

      <section className="categories-section">
        <h2>Categories</h2>
        <div className="categories-grid">
          <div 
            key="all" 
            className={`category-card ${!selectedCategory ? 'active' : ''}`}
            onClick={() => setSelectedCategory(null)}
          >
            <h3>All Items</h3>
          </div>
          {categories.map((cat) => (
            <div 
              key={cat.id} 
              className={`category-card ${selectedCategory === cat.id ? 'active' : ''}`}
              onClick={() => setSelectedCategory(cat.id)}
            >
              <h3>{cat.name}</h3>
            </div>
          ))}
        </div>
      </section>

      <section className="featured-items">
        <h2>{selectedCategory ? 'Items in Category' : 'Featured Items'}</h2>
        <div className="items-grid">
          {filteredItems.slice(0, 12).map((item) => (
            <div key={item.id} className="item-card">
<div className="item-image">
  <img src={`https://picsum.photos/seed/${item.id}/400/500`} alt={item.title} />
</div>
              <div className="item-info">
                <h3>{item.title}</h3>
                <p className="price">${item.price}</p>
                {item.carbon_savings > 0 && (
                  <span className="carbon-badge">🌱 {item.carbon_savings}kg CO₂ saved</span>
                )}
                <button
                  className="btn btn-secondary"
                  onClick={() => addToSwaps(item)}
                >
                  Add to Swap
                </button>
              </div>
            </div>
          ))}
        </div>
      </section>
    </div>
  );


  if (loading) return <div className="loading">Loading...</div>;

  return (
    <div className="home-page">
      <section className="hero">
        <h1>Welcome to ReWear-it</h1>
        <p>Sustainable fashion through swapping and recycling</p>
        <a href="/products" className="btn btn-primary">Browse Items</a>
      </section>

      <section className="categories-section">
        <h2>Categories</h2>
        <div className="categories-grid">
          {categories.map((cat) => (
            <div key={cat.id} className="category-card">
              <h3>{cat.name}</h3>
            </div>
          ))}
        </div>
      </section>

      <section className="featured-items">
        <h2>Featured Items</h2>
        <div className="items-grid">
          {items.slice(0, 6).map((item) => (
            <div key={item.id} className="item-card">
<div className="item-image">
  <img src={`https://picsum.photos/seed/${item.id}/400/500`} alt={item.title} />
</div>
              <div className="item-info">
                <h3>{item.title}</h3>
                <p className="price">${item.price}</p>
                {item.carbon_savings > 0 && (
                  <span className="carbon-badge">🌱 {item.carbon_savings}kg CO₂ saved</span>
                )}
<button
  className="btn btn-secondary"
  onClick={() => addToSwaps(item)}
>
  Add to Swap
</button>
              </div>
            </div>
          ))}
        </div>
      </section>
    </div>
  );
};

export default Home;
