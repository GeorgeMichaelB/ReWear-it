import { useState, useEffect } from 'react';
import { analyticsAPI } from '../services/api';
import './SellerAnalytics.css';

function SellerAnalytics() {
  const [analytics, setAnalytics] = useState(null);
  const [salesChartData, setSalesChartData] = useState(null);
  const [viewsChartData, setViewsChartData] = useState(null);
  const [sustainabilityChartData, setSustainabilityChartData] = useState(null);
  const [activeChart, setActiveChart] = useState('sales');
  const [loading, setLoading] = useState(false);
  const [period, setPeriod] = useState('30_days');

  useEffect(() => {
    fetchAnalytics();
  }, [period]);

  const fetchAnalytics = async () => {
    setLoading(true);
    try {
      const response = await analyticsAPI.getSellerAnalytics({ period });
      setAnalytics(response.data);
      
      const salesRes = await analyticsAPI.getChartData('sales');
      setSalesChartData(salesRes.data);
      
      const viewsRes = await analyticsAPI.getChartData('views');
      setViewsChartData(viewsRes.data);
      
      const susRes = await analyticsAPI.getChartData('sustainability');
      setSustainabilityChartData(susRes.data);
    } catch (error) {
      console.error('Error fetching analytics:', error);
    }
    setLoading(false);
  };

  const renderChart = (chartData, color) => {
    if (!chartData || !chartData.data) return null;
    const maxValue = Math.max(...chartData.data.values);
    return (
      <div className="chart-bars">
        {chartData.data.values.map((value, index) => (
          <div key={index} className="chart-bar-wrapper">
            <div 
              className="chart-bar" 
              style={{ 
                height: `${maxValue > 0 ? (value / maxValue) * 100 : 0}%`,
                backgroundColor: color
              }}
            />
            <span className="chart-label">{chartData.data.labels[index]}</span>
            <span className="chart-value">{value}</span>
          </div>
        ))}
      </div>
    );
  };

  return (
    <div className="page-container">
      <h1>📊 Seller Performance Analytics</h1>
      <p className="page-description">
        Visual performance analytics - sales, views, and sustainability impact
      </p>

      <div className="card">
        <div className="period-selector">
          <h3>Performance Overview</h3>
          <select value={period} onChange={(e) => setPeriod(e.target.value)}>
            <option value="7_days">Last 7 Days</option>
            <option value="30_days">Last 30 Days</option>
            <option value="90_days">Last 90 Days</option>
          </select>
        </div>

        {loading && <p>Loading analytics...</p>}

        {analytics && (
          <div className="analytics-grid">
            <div className="analytics-card">
              <h4>💰 Sales</h4>
              <div className="stat">
                <span className="stat-value">${analytics.sales.total_revenue}</span>
                <span className="stat-label">Total Revenue</span>
              </div>
              <div className="stat">
                <span className="stat-value">{analytics.sales.total_orders}</span>
                <span className="stat-label">Orders</span>
              </div>
              <div className="stat">
                <span className="stat-value">{analytics.sales.conversion_rate}</span>
                <span className="stat-label">Conversion Rate</span>
              </div>
            </div>

            <div className="analytics-card">
              <h4>👁 Views</h4>
              <div className="stat">
                <span className="stat-value">{analytics.views.total_views}</span>
                <span className="stat-label">Total Views</span>
              </div>
              <div className="stat">
                <span className="stat-value">{analytics.views.unique_visitors}</span>
                <span className="stat-label">Unique Visitors</span>
              </div>
              <div className="stat">
                <span className="stat-value">{analytics.views.avg_time_on_listings}</span>
                <span className="stat-label">Avg. Time on Listings</span>
              </div>
            </div>

            <div className="analytics-card highlight">
              <h4>🌱 Sustainability Impact</h4>
              <div className="stat">
                <span className="stat-value">{analytics.sustainability_impact.co2_saved_kg} kg</span>
                <span className="stat-label">CO₂ Saved</span>
              </div>
              <div className="stat">
                <span className="stat-value">{analytics.sustainability_impact.water_saved_liters} L</span>
                <span className="stat-label">Water Saved</span>
              </div>
              <div className="stat">
                <span className="stat-value">{analytics.sustainability_impact.waste_diverted_kg} kg</span>
                <span className="stat-label">Waste Diverted</span>
              </div>
              <div className="stat">
                <span className="stat-value">{analytics.sustainability_impact.upcycled_items_sold}</span>
                <span className="stat-label">Upcycled Items Sold</span>
              </div>
            </div>
          </div>
        )}
      </div>

      <div className="card">
        <div className="chart-tabs">
          <button 
            className={`chart-tab ${activeChart === 'sales' ? 'active' : ''}`}
            onClick={() => setActiveChart('sales')}
          >
            📈 Sales
          </button>
          <button 
            className={`chart-tab ${activeChart === 'views' ? 'active' : ''}`}
            onClick={() => setActiveChart('views')}
          >
            👁 Views
          </button>
          <button 
            className={`chart-tab ${activeChart === 'sustainability' ? 'active' : ''}`}
            onClick={() => setActiveChart('sustainability')}
          >
            🌿 Sustainability
          </button>
        </div>

        <div className="chart-container">
          {activeChart === 'sales' && renderChart(salesChartData, '#4CAF50')}
          {activeChart === 'views' && renderChart(viewsChartData, '#2196F3')}
          {activeChart === 'sustainability' && renderChart(sustainabilityChartData, '#FF9800')}
        </div>
      </div>

      {analytics && (
        <div className="card">
          <h3>Top Performing Items</h3>
          <div className="item-grid">
            {analytics.top_performing_items.map((item, index) => (
              <div key={index} className="item-card">
                <div className="item-rank">#{index + 1}</div>
                <h4>{item.name}</h4>
                <div className="item-stats">
                  <span>👁 {item.views}</span>
                  <span>💰 {item.sales}</span>
                </div>
              </div>
            ))}
          </div>
        </div>
      )}
    </div>
  );
}

export default SellerAnalytics;