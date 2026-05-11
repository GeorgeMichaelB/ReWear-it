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

  if (loading) return <div className="loading" style={{ padding: 'var(--space-xl)', textAlign: 'center' }}>Reviving quality fashion...</div>;

  return (
    <div className="home-page">
      {/* Hero Section - Modernized from Slider.js */}
      <section className="hero-modern card-premium" style={{ 
        background: 'linear-gradient(rgba(27, 67, 50, 0.8), rgba(27, 67, 50, 0.8)), url("/assets/pages/img/layers/slider-1.jpg")',
        backgroundSize: 'cover',
        backgroundPosition: 'center',
        padding: 'var(--space-xl) var(--space-md)',
        color: 'white',
        borderRadius: 'var(--radius-lg)',
        marginBottom: 'var(--space-xl)',
        textAlign: 'center'
      }}>
        <h1 style={{ color: 'white', fontSize: '3.5rem', marginBottom: 'var(--space-sm)' }}>Revive. Swap. ReWear.</h1>
        <p style={{ fontSize: '1.25rem', color: 'var(--color-accent)', marginBottom: 'var(--space-lg)', maxWidth: '800px', margin: '0 auto var(--space-lg)' }}>
          Join the circular fashion movement. Swap high-quality pre-loved pieces and reduce your environmental impact.
        </p>
        <div style={{ display: 'flex', gap: 'var(--space-md)', justifyContent: 'center' }}>
          <a href="/products" className="btn-premium">Explore Collection</a>
          <a href="/register" className="btn-premium" style={{ backgroundColor: 'transparent', border: '1px solid white' }}>Start Swapping</a>
        </div>
      </section>

      {/* Categories Horizontal Scroll */}
      <section className="categories-section" style={{ marginBottom: 'var(--space-xl)' }}>
        <h2 style={{ marginBottom: 'var(--space-md)' }}>Curated Categories</h2>
        <div className="categories-grid" style={{ 
          display: 'flex', 
          gap: 'var(--space-sm)', 
          overflowX: 'auto', 
          paddingBottom: 'var(--space-sm)',
          scrollbarWidth: 'none'
        }}>
          <div 
            className={`category-pill ${!selectedCategory ? 'active' : ''}`}
            onClick={() => setSelectedCategory(null)}
            style={{ 
              padding: '0.5rem 1.5rem', 
              borderRadius: 'var(--radius-full)', 
              background: !selectedCategory ? 'var(--color-primary)' : 'var(--color-white)',
              color: !selectedCategory ? 'white' : 'var(--color-text-main)',
              border: '1px solid var(--color-border)',
              cursor: 'pointer',
              whiteSpace: 'nowrap'
            }}
          >
            All Pieces
          </div>
          {categories.map((cat) => (
            <div 
              key={cat.id} 
              className={`category-pill ${selectedCategory === cat.id ? 'active' : ''}`}
              onClick={() => setSelectedCategory(cat.id)}
              style={{ 
                padding: '0.5rem 1.5rem', 
                borderRadius: 'var(--radius-full)', 
                background: selectedCategory === cat.id ? 'var(--color-primary)' : 'var(--color-white)',
                color: selectedCategory === cat.id ? 'white' : 'var(--color-text-main)',
                border: '1px solid var(--color-border)',
                cursor: 'pointer',
                whiteSpace: 'nowrap'
              }}
            >
              {cat.name}
            </div>
          ))}
        </div>
      </section>

      {/* Featured Items Grid - Modernized from HomeContent.js */}
      <section className="featured-items">
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-end', marginBottom: 'var(--space-lg)' }}>
          <div>
            <h2 style={{ fontSize: '2rem' }}>{selectedCategory ? 'DISCOVER SWAPS' : 'NEW ARRIVALS'}</h2>
            <p style={{ color: 'var(--color-text-muted)' }}>Sustainable fashion ready for a new chapter.</p>
          </div>
          <a href="/products" style={{ fontWeight: 500, color: 'var(--color-secondary)', borderBottom: '2px solid var(--color-secondary)' }}>View All</a>
        </div>

        <div className="items-grid" style={{ 
          display: 'grid', 
          gridTemplateColumns: 'repeat(auto-fill, minmax(280px, 1fr))', 
          gap: 'var(--space-lg)' 
        }}>
          {filteredItems.slice(0, 12).map((item) => (
            <div key={item.id} className="card-premium" style={{ position: 'relative' }}>
              <div style={{ 
                height: '350px', 
                overflow: 'hidden', 
                borderRadius: 'var(--radius-sm)',
                marginBottom: 'var(--space-sm)',
                backgroundColor: '#f0f0f0'
              }}>
                <img 
                  src={`https://picsum.photos/seed/${item.id}/400/500`} 
                  alt={item.title} 
                  style={{ width: '100%', height: '100%', objectFit: 'cover' }}
                />
              </div>
              
              <div style={{ padding: 'var(--space-xs)' }}>
                <h3 style={{ fontSize: '1.1rem', marginBottom: '4px' }}>{item.title}</h3>
                {item.carbon_savings > 0 && (
                  <span style={{ fontSize: '0.75rem', color: 'var(--color-secondary)', fontWeight: 600, display: 'block', marginBottom: 'var(--space-xs)' }}>
                    🌱 Saved {item.carbon_savings}kg CO₂
                  </span>
                )}
                
                <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginTop: 'var(--space-sm)' }}>
                  <div>
                    <span style={{ fontSize: '0.75rem', textTransform: 'uppercase', color: 'var(--color-text-muted)', display: 'block' }}>Swap Value</span>
                    <span style={{ color: 'var(--color-primary)', fontWeight: 700 }}>${item.price}</span>
                  </div>
                  <button 
                    className="btn-premium" 
                    style={{ padding: '0.5rem 1rem', fontSize: '0.85rem' }}
                    onClick={() => addToSwaps(item)}
                  >
                    Add to Swap
                  </button>
                </div>
              </div>
            </div>
          ))}
        </div>
      </section>

      {/* Trust & Impact Stats */}
      <section style={{ 
        marginTop: 'var(--space-xl)', 
        padding: 'var(--space-xl)', 
        backgroundColor: 'var(--color-primary)', 
        color: 'white',
        borderRadius: 'var(--radius-lg)',
        textAlign: 'center'
      }}>
        <h2 style={{ color: 'white', marginBottom: 'var(--space-md)' }}>Why ReWear-it?</h2>
        <div style={{ display: 'flex', justifyContent: 'center', gap: 'var(--space-xl)', flexWrap: 'wrap' }}>
          <div>
            <div style={{ fontSize: '2.5rem', fontWeight: 700 }}>100%</div>
            <p style={{ color: 'var(--color-accent)' }}>Circular</p>
          </div>
          <div>
            <div style={{ fontSize: '2.5rem', fontWeight: 700 }}>500+</div>
            <p style={{ color: 'var(--color-accent)' }}>Curated Items</p>
          </div>
          <div>
            <div style={{ fontSize: '2.5rem', fontWeight: 700 }}>12k</div>
            <p style={{ color: 'var(--color-accent)' }}>CO₂ Saved (kg)</p>
          </div>
        </div>
      </section>
    </div>
  );
};

export default Home;
