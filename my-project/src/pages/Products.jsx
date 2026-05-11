import React, { useState, useEffect } from 'react';
import { itemsAPI, categoriesAPI } from '../services/api';

const Products = ({ addToSwaps }) => {
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

  if (loading) return <div style={{ padding: 'var(--space-xl)', textAlign: 'center' }}>Curating the collection...</div>;

  return (
    <div className="products-page" style={{ display: 'grid', gridTemplateColumns: '250px 1fr', gap: 'var(--space-xl)' }}>
      {/* Sidebar Filters */}
      <aside>
        <div className="card-premium" style={{ position: 'sticky', top: '100px' }}>
          <h3 style={{ fontSize: '1.2rem', marginBottom: 'var(--space-md)' }}>Categories</h3>
          <ul style={{ listStyle: 'none', display: 'flex', flexDirection: 'column', gap: 'var(--space-xs)' }}>
            <li 
              onClick={() => setSelectedCategory(null)}
              style={{ 
                cursor: 'pointer', 
                padding: '8px 12px', 
                borderRadius: 'var(--radius-sm)',
                background: !selectedCategory ? 'var(--color-primary)' : 'transparent',
                color: !selectedCategory ? 'white' : 'var(--color-text-main)',
                fontWeight: !selectedCategory ? 600 : 400
              }}
            >
              All Items
            </li>
            {categories.map(cat => (
              <li 
                key={cat.id}
                onClick={() => setSelectedCategory(cat.id)}
                style={{ 
                  cursor: 'pointer', 
                  padding: '8px 12px', 
                  borderRadius: 'var(--radius-sm)',
                  background: selectedCategory === cat.id ? 'var(--color-primary)' : 'transparent',
                  color: selectedCategory === cat.id ? 'white' : 'var(--color-text-main)',
                  fontWeight: selectedCategory === cat.id ? 600 : 400
                }}
              >
                {cat.name}
              </li>
            ))}
          </ul>
        </div>
      </aside>

      {/* Product Grid */}
      <main>
        <header style={{ marginBottom: 'var(--space-lg)', display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
          <h2 style={{ fontSize: '1.8rem' }}>EXPLORE ALL PIECES</h2>
          <span style={{ color: 'var(--color-text-muted)', fontSize: '0.9rem' }}>Showing {filteredItems.length} results</span>
        </header>

        <div style={{ 
          display: 'grid', 
          gridTemplateColumns: 'repeat(auto-fill, minmax(260px, 1fr))', 
          gap: 'var(--space-lg)' 
        }}>
          {filteredItems.map((item) => (
            <div key={item.id} className="card-premium">
              <div style={{ height: '320px', overflow: 'hidden', borderRadius: 'var(--radius-sm)', marginBottom: 'var(--space-sm)' }}>
                <img 
                  src={`https://picsum.photos/seed/${item.id}/400/500`} 
                  alt={item.title} 
                  style={{ width: '100%', height: '100%', objectFit: 'cover' }}
                />
              </div>
              <h3 style={{ fontSize: '1.1rem', marginBottom: '4px' }}>{item.title}</h3>
              <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginTop: 'var(--space-sm)' }}>
                <span style={{ color: 'var(--color-primary)', fontWeight: 700 }}>${item.price}</span>
                <button 
                  className="btn-premium" 
                  style={{ padding: '0.4rem 0.8rem', fontSize: '0.8rem' }}
                  onClick={() => addToSwaps(item)}
                >
                  Add to Swap
                </button>
              </div>
            </div>
          ))}
        </div>
      </main>
    </div>
  );
};

export default Products;
