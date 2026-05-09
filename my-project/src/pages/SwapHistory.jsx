import React, { useState, useEffect } from 'react';
import { ordersAPI } from '../services/api';
import './SwapHistory.css';

const SwapHistoryPage = () => {
  const [swaps, setSwaps] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchSwaps();
  }, []);

  const fetchSwaps = async () => {
    try {
      const response = await ordersAPI.getSwapHistory();
      setSwaps(response.data.swaps);
    } catch (error) {
      console.error('Error fetching swap history:', error);
    } finally {
      setLoading(false);
    }
  };

  const getStatusClass = (status) => {
    const classes = {
      completed: 'status-completed',
      pending: 'status-pending',
      cancelled: 'status-cancelled',
    };
    return classes[status] || 'status-grey';
  };

  if (loading) return <div className="loading">Loading...</div>;

  return (
    <div className="swap-history-page">
      <h2>🔄 Swap History</h2>
      
      {swaps.length === 0 ? (
        <p className="no-swaps">No swap history yet.</p>
      ) : (
        <div className="swaps-list">
          {swaps.map((swap) => (
            <div key={swap.id} className="swap-card">
              <div className="swap-header">
                <span className="swap-id">Swap #{swap.id}</span>
<span className={`swap-status ${getStatusClass(swap.status)}`}>
  {swap.status}
</span>
              </div>
              <div className="swap-items">
                <span className="label">Items:</span>
                <span className="items">{swap.items.join(', ')}</span>
              </div>
              <div className="swap-date">
                <span className="label">Date:</span>
                <span className="date">{swap.date}</span>
              </div>
              {swap.status === 'completed' && (
                <button className="btn-secondary">Leave Review</button>
              )}
              {swap.status === 'pending' && (
                <button className="btn-danger">Cancel Swap</button>
              )}
            </div>
          ))}
        </div>
      )}
    </div>
  );
};

export default SwapHistoryPage;