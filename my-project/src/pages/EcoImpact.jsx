import React, { useState } from 'react';
import { impactAPI } from '../services/api';
import './EcoImpact.css';

const EcoImpact = () => {
  const [formData, setFormData] = useState({
    material_type: 'cotton',
    weight_kg: 1,
    original_condition: 'good',
  });
  const [result, setResult] = useState(null);
  const [loading, setLoading] = useState(false);

  const calculateImpact = async (e) => {
    e.preventDefault();
    setLoading(true);
    try {
      const response = await impactAPI.calculate(formData);
      setResult(response.data);
    } catch (error) {
      alert('Error calculating impact');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="eco-impact-page">
      <h2>🌱 Eco Impact Calculator</h2>
      <p className="page-desc">Calculate your environmental savings from swapping vs buying new</p>

      <form onSubmit={calculateImpact} className="impact-form">
        <div className="form-group">
          <label>Material Type</label>
          <select value={formData.material_type} onChange={(e) => setFormData({ ...formData, material_type: e.target.value })}>
            <option value="cotton">Cotton</option>
            <option value="polyester">Polyester</option>
            <option value="wool">Wool</option>
            <option value="linen">Linen</option>
            <option value="silk">Silk</option>
            <option value="denim">Denim</option>
          </select>
        </div>
        
        <div className="form-group">
          <label>Weight (kg)</label>
          <input
            type="number"
            value={formData.weight_kg}
            onChange={(e) => setFormData({ ...formData, weight_kg: parseFloat(e.target.value) })}
            min="0.1"
            step="0.1"
          />
        </div>

        <div className="form-group">
          <label>Original Condition</label>
          <select value={formData.original_condition} onChange={(e) => setFormData({ ...formData, original_condition: e.target.value })}>
            <option value="new">Like New</option>
            <option value="good">Good</option>
            <option value="fair">Fair</option>
            <option value="worn">Worn</option>
          </select>
        </div>

<button type="submit" className="btn btn-primary" disabled={loading}>
  {loading ? 'Calculating...' : 'Calculate Impact'}
</button>
      </form>

      {result && (
        <div className="impact-results">
          <h3>Your Environmental Impact</h3>
          
          <div className="impact-cards">
            <div className="impact-card">
              <span className="impact-icon">☁️</span>
              <span className="impact-value">{result.impact.co2_saved_kg} kg</span>
              <span className="impact-label">CO₂ Saved</span>
            </div>
            <div className="impact-card">
              <span className="impact-icon">💧</span>
              <span className="impact-value">{result.impact.water_saved_liters} L</span>
              <span className="impact-label">Water Saved</span>
            </div>
            <div className="impact-card">
              <span className="impact-icon">📉</span>
              <span className="impact-value">{result.impact.carbon_footprint_reduction_percent}%</span>
              <span className="impact-label">Carbon Reduction</span>
            </div>
          </div>

          <div className="equivalents">
            <h4>What this means:</h4>
            <ul>
              <li>🌳 Equivalent to {result.equivalents.trees_needed_for_same_absorption} trees absorbing CO₂ for a year</li>
              <li>🚗 Equals {result.equivalents.car_miles_not_driven} miles not driven</li>
              <li>📱 Saves {result.equivalents.smartphone_charges_saved} smartphone charges worth of energy</li>
            </ul>
          </div>

          <div className="eco-tip">
            💡 By swapping this item instead of buying new, you're saving {result.impact.co2_saved_kg}kg of CO₂!
          </div>
        </div>
      )}
    </div>
  );
};

export default EcoImpact;