import React, { useState } from 'react';
import { ordersAPI } from '../services/api';
import './ShippingCalculator.css';

const ShippingCalculator = () => {
  const [formData, setFormData] = useState({
    from_postal_code: '',
    to_postal_code: '',
    weight_kg: 1,
  });
  const [result, setResult] = useState(null);
  const [loading, setLoading] = useState(false);

  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const calculateShipping = async (e) => {
    e.preventDefault();
    setLoading(true);
    try {
      const response = await ordersAPI.calculateShipping(formData);
      setResult(response.data);
    } catch (error) {
      alert('Error calculating shipping');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="shipping-calculator-page">
      <h2>🚚 Shipping Calculator</h2>
      
      <form onSubmit={calculateShipping} className="shipping-form">
         <div className="form-group-base">
          <label>From Postal Code</label>
          <input
            type="text"
            name="from_postal_code"
            value={formData.from_postal_code}
            onChange={handleChange}
            required
            placeholder="e.g., 12345"
          />
        </div>
        
         <div className="form-group-base">
          <label>To Postal Code</label>
          <input
            type="text"
            name="to_postal_code"
            value={formData.to_postal_code}
            onChange={handleChange}
            required
            placeholder="e.g., 67890"
          />
        </div>
        
         <div className="form-group-base">
          <label>Package Weight (kg)</label>
          <input
            type="number"
            name="weight_kg"
            value={formData.weight_kg}
            onChange={handleChange}
            required
            min="0.1"
            max="30"
            step="0.1"
          />
        </div>
        
<button type="submit" className="btn btn-primary" disabled={loading}>
  {loading ? 'Calculating...' : 'Calculate Shipping'}
</button>
      </form>

      {result && (
        <div className="shipping-result">
          <h3>Shipping Estimate</h3>
          <div className="result-details">
            <div className="result-item">
              <span className="label">Estimated Cost</span>
              <span className="value">${result.estimated_cost}</span>
            </div>
            <div className="result-item">
              <span className="label">Delivery Time</span>
              <span className="value">{result.estimated_days} days</span>
            </div>
            <div className="result-item">
              <span className="label">Carrier</span>
              <span className="value">{result.carrier}</span>
            </div>
            {result.carbon_neutral && (
              <div className="eco-badge">
                🌱 Carbon Neutral Delivery
              </div>
            )}
          </div>
        </div>
      )}
    </div>
  );
};

export default ShippingCalculator;