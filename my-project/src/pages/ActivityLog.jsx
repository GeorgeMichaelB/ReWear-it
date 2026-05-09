import React, { useState, useEffect } from 'react';
import { authAPI } from '../services/api';

const ActivityLog = () => {
  const [activities, setActivities] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchActivities();
  }, []);

  const fetchActivities = async () => {
    try {
      const response = await authAPI.getActivityLog();
      setActivities(response.data.activities);
    } catch (error) {
      console.error('Error fetching activities:', error);
    } finally {
      setLoading(false);
    }
  };

  const getActivityIcon = (type) => {
    const icons = {
      swap_completed: '🔄',
      item_listed: '📦',
      style_board_created: '🎨',
      review_received: '⭐',
      carbon_saved: '🌱',
    };
    return icons[type] || '📋';
  };

  if (loading) return <div className="loading">Loading...</div>;

  return (
    <div className="activity-log-page">
      <h2>📋 Activity Log</h2>
      
      <div className="activity-timeline">
        {activities.map((activity, index) => (
          <div key={index} className="activity-item">
            <div className="activity-icon">
              {getActivityIcon(activity.type)}
            </div>
            <div className="activity-content">
              <div className="activity-header">
                <span className="activity-type">
                  {activity.type.replace(/_/g, ' ')}
                </span>
                <span className="activity-date">{activity.date}</span>
              </div>
              <div className="activity-details">
                {activity.item && <span>Item: {activity.item}</span>}
                {activity.name && <span>Board: {activity.name}</span>}
                {activity.rating && <span>Rating: {'⭐'.repeat(activity.rating)}</span>}
                {activity.amount && <span>Carbon saved: {activity.amount}kg</span>}
              </div>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
};

export default ActivityLog;