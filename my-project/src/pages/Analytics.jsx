import React, { useState, useEffect } from 'react';
import { ordersAPI, itemsAPI, categoriesAPI } from '../services/api';
import './Analytics.css';

const Analytics = () => {
  const [analytics, setAnalytics] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchAnalytics();
  }, []);

  const fetchAnalytics = async () => {
    try {
      const response = await ordersAPI.getTransactionAnalytics();
      setAnalytics(response.data);
    } catch (error) {
      console.error('Error fetching analytics:', error);
    } finally {
      setLoading(false);
    }
  };

  if (loading) return <div className="loading">Loading...</div>;

  return (
    <div className="analytics-page">
      <h2>📊 Transaction Analytics</h2>
      
      <div className="stats-overview">
        <div className="stat-card">
          <span className="stat-icon">🔄</span>
          <span className="stat-value">{analytics?.total_swaps}</span>
          <span className="stat-label">Total Swaps</span>
        </div>
        <div className="stat-card">
          <span className="stat-icon">💰</span>
          <span className="stat-value">${analytics?.total_value}</span>
          <span className="stat-label">Total Value</span>
        </div>
        <div className="stat-card">
          <span className="stat-icon">🌱</span>
          <span className="stat-value">{analytics?.carbon_saved}kg</span>
          <span className="stat-label">Carbon Saved</span>
        </div>
      </div>

      <div className="monthly-stats">
        <h3>Monthly Performance</h3>
        <div className="stats-row">
          <div className="stat-item">
            <span className="label">This Month</span>
            <span className="value">{analytics?.monthly_stats?.this_month} swaps</span>
          </div>
          <div className="stat-item">
            <span className="label">Last Month</span>
            <span className="value">{analytics?.monthly_stats?.last_month} swaps</span>
          </div>
          <div className="stat-item">
            <span className="label">Trend</span>
            <span className={`value trend ${analytics?.monthly_stats?.trend}`}>
              {analytics?.monthly_stats?.trend === 'up' ? '📈 Up' : '📉 Down'}
            </span>
          </div>
        </div>
      </div>

      <div className="top-categories">
        <h3>Top Categories</h3>
        <ul>
          {analytics?.top_categories?.map((cat, index) => (
            <li key={index}>
              <span className="rank">#{index + 1}</span>
              <span className="name">{cat}</span>
            </li>
          ))}
        </ul>
      </div>
    </div>
  );
};

export default Analytics;