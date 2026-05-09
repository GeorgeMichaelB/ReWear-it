import React, { useState, useEffect } from 'react';
import { authAPI } from '../services/api';
import { useAuth } from '../context/AuthContext';
import './TrustScore.css';

const TrustScore = () => {
  const { user } = useAuth();
  const [details, setDetails] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchDetails();
  }, []);

  const fetchDetails = async () => {
    try {
      const response = await authAPI.getTrustScoreDetails();
      setDetails(response.data);
    } catch (error) {
      console.error('Error fetching trust score:', error);
    } finally {
      setLoading(false);
    }
  };

  if (loading) return <div className="loading">Loading...</div>;

  const getScoreColor = (score) => {
    if (score >= 80) return 'excellent';
    if (score >= 60) return 'good';
    if (score >= 40) return 'fair';
    return 'poor';
  };

  return (
    <div className="trust-score-page">
      <h2>⭐ Trust Score</h2>
      
      <div className="score-hero">
        <div className={`score-circle ${getScoreColor(details?.score)}`}>
          <span className="score-value">{details?.score || 0}</span>
          <span className="score-max">/100</span>
        </div>
        <p className="score-label">Your Trust Score</p>
      </div>

      <div className="score-breakdown">
        <h3>Score Breakdown</h3>
        {details?.breakdown && Object.entries(details.breakdown).map(([key, value]) => (
          <div key={key} className="breakdown-item">
            <span className="breakdown-label">{key.replace('_', ' ')}</span>
            <div className="breakdown-bar">
              <div className="bar-fill" style={{ width: `${value}%` }}></div>
            </div>
            <span className="breakdown-value">{value}</span>
          </div>
        ))}
      </div>

      <div className="factors-section">
        <h3>Key Factors</h3>
        {details?.factors && Object.entries(details.factors).map(([key, value]) => (
          <div key={key} className="factor-item">
            <span className="factor-label">{key.replace(/_/g, ' ')}</span>
            <span className="factor-value">{value}</span>
          </div>
        ))}
      </div>

      <div className="tips-section">
        <h3>Tips to Improve</h3>
        <ul>
          <li>Complete more successful swaps</li>
          <li>Respond to inquiries quickly</li>
          <li>List high-quality items</li>
          <li>Get verified with ID verification</li>
        </ul>
      </div>
    </div>
  );
};

export default TrustScore;