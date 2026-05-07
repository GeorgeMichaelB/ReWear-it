import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { itemsAPI, categoriesAPI } from '../services/api';

const ProductList = ({ addToSwaps }) => {
  const [items, setItems] = useState([]);
  const [categories, setCategories] = useState([]);
  const [loading, setLoading] = useState(true);
  const [selectedCategory, setSelectedCategory] = useState(null);
  const [searchTerm, setSearchTerm] = useState('');

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
    const params = searchTerm ? { search: searchTerm } : {};
    if (selectedCategory) params.category_id = selectedCategory;
    fetchItems(params);
  };

  const handleAddToSwap = (item) => {
    addToSwaps({
      id: item.id,
      name: item.title,
      img: '/assets/pages/img/products/model1.jpg',
      value: `$${item.price}`,
      price: item.price,
      carbonSavings: item.carbon_savings,
    });
  };

  if (loading) {
    return (
      <div className="container">
        <div className="text-center" style={{padding: '50px'}}>
          <i className="fa fa-spinner fa-spin fa-3x"></i>
          <p>Loading items...</p>
        </div>
      </div>
    );
  }

  return (
    <>
      <div className="sidebar col-md-3 col-sm-4">
        <div className="sidebar-products">
          <h3>Categories</h3>
          <ul className="list-group margin-bottom-25">
            <li className={`list-group-item ${selectedCategory === null ? 'active' : ''}`}>
              <Link to="#" onClick={() => handleCategoryClick(null)}>
                <i className="fa fa-angle-right"></i> All Items ({items.length})
              </Link>
            </li>
            {categories.map((cat) => (
              <li key={cat.id} className={`list-group-item ${selectedCategory === cat.id ? 'active' : ''}`}>
                <Link to="#" onClick={() => handleCategoryClick(cat.id)}>
                  <i className="fa fa-angle-right"></i> {cat.name}
                </Link>
              </li>
            ))}
          </ul>
        </div>
        
        <div className="sidebar-products margin-bottom-20">
          <h3>Search</h3>
          <form onSubmit={handleSearch}>
            <div className="input-group">
              <input 
                type="text" 
                className="form-control" 
                placeholder="Search items..."
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
              />
              <span className="input-group-btn">
                <button className="btn btn-primary" type="submit">Go</button>
              </span>
            </div>
          </form>
        </div>
      </div>

      <div className="col-md-9 col-sm-8">
        <div className="row">
          <div className="col-md-12">
            <h2>
              {selectedCategory 
                ? categories.find(c => c.id === selectedCategory)?.name || 'Items' 
                : 'All Available Items'} 
              <span className="pull-right">{items.length} items</span>
            </h2>
          </div>
        </div>
        
        {items.length === 0 ? (
          <div className="alert alert-info">No items found. Try a different category or search term.</div>
        ) : (
          <div className="row product-list">
            {items.map((item) => (
              <div key={item.id} className="col-md-4 col-sm-6 col-xs-12">
                <div className="product-item">
                  <div className="pi-img-wrapper">
                    <img 
                      src="/assets/pages/img/products/model1.jpg" 
                      className="img-responsive" 
                      alt={item.title} 
                    />
                    <div>
                      <Link to={`/products/${item.id}`} className="btn btn-default fancybox-button">View</Link>
                      <a href="#" className="btn btn-default fancybox-fast-view">Quick View</a>
                    </div>
                  </div>
                  <h3>
                    <Link to={`/products/${item.id}`}>{item.title}</Link>
                  </h3>
                  {item.description && (
                    <p className="text-muted">{item.description.substring(0, 60)}...</p>
                  )}
                  <div className="pi-price">${item.price.toFixed(2)}</div>
                  <div className="pi-info">
                    {item.carbon_savings > 0 && (
                      <span className="text-success" style={{marginRight: '10px'}}>
                        <i className="fa fa-leaf"></i> {item.carbon_savings}kg CO₂ saved
                      </span>
                    )}
                    <button
                      className="btn btn-default add2cart"
                      onClick={() => handleAddToSwap(item)}
                    >
                      <i className="fa fa-refresh"></i> Add to Swap
                    </button>
                  </div>
                  <div className="sticker sticker-new"></div>
                </div>
              </div>
            ))}
          </div>
        )}
      </div>
    </>
  );
};

export default ProductList;