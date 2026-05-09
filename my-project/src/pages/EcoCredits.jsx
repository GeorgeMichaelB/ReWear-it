import React, { useState, useEffect } from 'react';
import { authAPI } from '../services/api';
import { useAuth } from '../context/AuthContext';
import './EcoCredits.css';

const EcoCredits = () => {
  const { user } = useAuth();
  const [credits, setCredits] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchCredits();
  }, []);

  const fetchCredits = async () => {
    try {
      const response = await authAPI.getEcoCreditsHistory();
      setCredits(response.data);
    } catch (error) {
      console.error('Error fetching credits:', error);
    } finally {
      setLoading(false);
    }
  };

  if (loading) return <div className="loading">Loading...</div>;

  return (
    <div className="eco-credits-page">
      <h2>🌱 Eco Credits</h2>
      
      <div className="credits-hero">
        <div className="current-credits">
          <span className="label">Your Current Balance</span>
          <span className="value">{credits?.current || 0}</span>
          <span className="unit">credits</span>
        </div>
      </div>

      <div className="credits-history">
        <h3>Recent Activity</h3>
        {credits?.history?.map((item, index) => (
          <div key={index} className={`history-item ${item.type}`}>
            <div className="history-info">
              <span className="reason">{item.reason}</span>
              <span className="date">{item.date}</span>
            </div>
            <span className={`amount ${item.type}`}>
              {item.type === 'earned' ? '+' : ''}{item.amount}
            </span>
          </div>
        ))}
      </div>

      <div className="credits-info">
        <h3>How to Earn More</h3>
        <ul>
          <li>Complete swaps: +50 credits</li>
          <li>List sustainable items: +25 credits</li>
          <li>Get positive reviews: +15 credits</li>
          <li>Refer friends: +100 credits</li>
        </ul>
      </div>
    </div>
  );
};

export default EcoCredits;