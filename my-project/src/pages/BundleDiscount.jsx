import React, { useState } from 'react';
import { ordersAPI } from '../services/api';

const BundleDiscount = () => {
  const [itemCount, setItemCount] = useState(2);
  const [result, setResult] = useState(null);
  const [loading, setLoading] = useState(false);

  const calculateDiscount = async () => {
    setLoading(true);
    try {
      const response = await ordersAPI.calculateBundleDiscount(itemCount);
      setResult(response.data);
    } catch (error) {
      console.error('Error calculating discount:', error);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="bundle-discount-page">
      <h2>🏷️ Bundle Discount Calculator</h2>
      
      <div className="discount-form">
        <div className="form-group">
          <label>Number of Items</label>
          <input
            type="range"
            min="2"
            max="10"
            value={itemCount}
            onChange={(e) => setItemCount(parseInt(e.target.value))}
          />
          <span className="range-value">{itemCount} items</span>
        </div>
        
        <button className="btn-primary" onClick={calculateDiscount} disabled={loading}>
          {loading ? 'Calculating...' : 'Calculate Discount'}
        </button>
      </div>

      {result && (
        <div className="discount-result">
          <div className="result-message">{result.message}</div>
          <div className="discount-percentage">
            {result.discount_percentage}% OFF
          </div>
          <div className="discount-tier">
            <h4>Tier Benefits:</h4>
            <ul>
              <li>2 items: 5% discount</li>
              <li>3 items: 10% discount</li>
              <li>4 items: 15% discount</li>
              <li>5+ items: 20% discount</li>
            </ul>
          </div>
        </div>
      )}
    </div>
  );
};

export default BundleDiscount;