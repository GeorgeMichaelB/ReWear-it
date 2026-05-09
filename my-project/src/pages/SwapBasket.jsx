import React from 'react';
import { useAuth } from '../context/AuthContext';
import { transactionsAPI } from '../services/api';
import './SwapBasket.css';

const SwapBasket = ({ swaps, removeFromSwaps }) => {
  const { user } = useAuth();

  const totalValue = swaps.reduce((sum, item) => sum + (item.price || 0), 0);
  const totalCarbon = swaps.reduce((sum, item) => sum + (item.carbon_savings || 0), 0);

  const handleSwap = async () => {
    if (!user) {
      alert('Please login to complete the swap');
      return;
    }
    if (swaps.length === 0) {
      alert('Your swap basket is empty');
      return;
    }
    try {
      await transactionsAPI.create({
        items: swaps.map((item) => item.id),
        total_value: totalValue,
        total_carbon_savings: totalCarbon,
      });
      alert('Swap request created successfully!');
    } catch (error) {
      alert('Error creating swap: ' + (error.response?.data?.message || 'Unknown error'));
    }
  };

  return (
    <div className="swap-basket-page">
      <h2>My Swap Basket</h2>

      {swaps.length === 0 ? (
        <p className="empty-basket">Your swap basket is empty.</p>
      ) : (
        <>
          <div className="basket-items">
            {swaps.map((item) => (
              <div key={item.id} className="basket-item">
                <div className="item-info">
                  <h4>{item.title || item.name}</h4>
                  <p>${item.price}</p>
                  {item.carbon_savings > 0 && (
                    <span className="carbon-badge">🌱 {item.carbon_savings}kg</span>
                  )}
                </div>
                <button onClick={() => removeFromSwaps(item.id)} className="btn-danger">
                  Remove
                </button>
              </div>
            ))}
          </div>

          <div className="basket-summary">
            <div className="summary-item">
              <span>Total Items:</span>
              <span>{swaps.length}</span>
            </div>
            <div className="summary-item">
              <span>Total Value:</span>
              <span>${totalValue.toFixed(2)}</span>
            </div>
            <div className="summary-item">
              <span>Carbon Savings:</span>
              <span>{totalCarbon.toFixed(1)} kg CO₂</span>
            </div>
          </div>

          <div className="basket-actions">
<button className="btn btn-primary" onClick={handleSwap}>
  Request Swap
</button>
<button className="btn btn-secondary" onClick={() => swaps.forEach(item => removeFromSwaps(item.id))}>
  Clear Basket
</button>
          </div>
        </>
      )}
    </div>
  );
};

export default SwapBasket;