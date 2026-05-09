import { useState, useEffect } from 'react';
import { marketTrendsAPI } from '../services/api';
import './MarketTrendsPage.css';

function MarketTrendsPage() {
  const [materialTrends, setMaterialTrends] = useState(null);
  const [categoryPerf, setCategoryPerf] = useState(null);
  const [seasonalTrends, setSeasonalTrends] = useState(null);
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    fetchAllTrends();
  }, []);

  const fetchAllTrends = async () => {
    setLoading(true);
    try {
      const [materialsRes, categoryRes, seasonalRes] = await Promise.all([
        marketTrendsAPI.getMaterialTrends('30_days'),
        marketTrendsAPI.getCategoryPerformance(),
        marketTrendsAPI.getSeasonalTrends('spring'),
      ]);
      setMaterialTrends(materialsRes.data);
      setCategoryPerf(categoryRes.data);
      setSeasonalTrends(seasonalRes.data);
    } catch (error) {
      console.error('Error fetching trends:', error);
    }
    setLoading(false);
  };

  const getPricingRecommendation = async () => {
    try {
      const response = await marketTrendsAPI.getPriceRecommendations({
        category: 'tops',
        condition: 'good',
        material: 'cotton',
      });
      alert(`Recommended price: $${response.data.recommended_price}\nRange: $${response.data.price_range.min} - $${response.data.price_range.max}`);
    } catch (error) {
      console.error('Error getting pricing:', error);
    }
  };

  return (
    <div className="page-container">
      <h1 style={{ textAlign: 'center' }}>📈 Market Trends</h1>
      <p className="page-description">
        Highly swappable and sellable materials, category performance, and pricing insights
      </p>

      {loading && <p style={{ textAlign: 'center' }}>Loading trends...</p>}

      {materialTrends && (
        <div className="card">
          <h3>Most Swappable Materials</h3>
          <table className="data-table">
            <thead>
              <tr>
                <th>Material</th>
                <th>Swap Rate</th>
                <th>Avg Days to Swap</th>
                <th>Demand Score</th>
              </tr>
            </thead>
            <tbody>
              {materialTrends.trends.most_swappable.map((item, i) => (
                <tr key={i}>
                  <td>{item.material}</td>
                  <td>{item.swap_rate}</td>
                  <td>{item.avg_days_to_swap}</td>
                  <td><span className="demand-badge">{item.demand_score}</span></td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      )}

      {materialTrends && (
        <div className="card">
          <h3>Most Sellable Items</h3>
          <div className="item-grid">
            {materialTrends.trends.most_sellable.map((item, i) => (
              <div key={i} className="item-card highlight">
                <h4>{item.material}</h4>
                <p>Sell Rate: {item.sell_rate}</p>
                <p>Avg Price: ${item.avg_price}</p>
                <span className="badge success">High Demand</span>
              </div>
            ))}
          </div>
        </div>
      )}

      {materialTrends && (
        <div className="card">
          <h3>Rising Trends</h3>
          <div className="trends-list">
            {materialTrends.trends.rising_trends.map((trend, i) => (
              <div key={i} className="trend-item positive">
                <span className="trend-name">{trend.trend}</span>
                <span className="trend-growth">↑ {trend.growth}</span>
                <span className="trend-category">{trend.category}</span>
              </div>
            ))}
          </div>
        </div>
      )}

      {categoryPerf && (
        <div className="card">
          <h3>Category Performance</h3>
          <table className="data-table">
            <thead>
              <tr>
                <th>Category</th>
                <th>Listings</th>
                <th>Sold</th>
                <th>Sell Rate</th>
                <th>Avg Price</th>
              </tr>
            </thead>
            <tbody>
              {categoryPerf.categories.map((cat, i) => (
                <tr key={i}>
                  <td>{cat.name}</td>
                  <td>{cat.listings}</td>
                  <td>{cat.sold}</td>
                  <td>{cat.sell_rate}</td>
                  <td>${cat.avg_price}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      )}

      {seasonalTrends && (
        <div className="card">
          <h3>Seasonal Trends - {seasonalTrends.season}</h3>
          <div className="seasonal-grid">
            <div>
              <h4>Trending Now</h4>
              <ul>
                {seasonalTrends.trending_now.map((item, i) => (
                  <li key={i}>{item}</li>
                ))}
              </ul>
            </div>
            <div>
              <h4>Price Tips</h4>
              <ul>
                {seasonalTrends.price_tips.map((tip, i) => (
                  <li key={i}>{tip}</li>
                ))}
              </ul>
            </div>
          </div>
        </div>
      )}

      <div className="card">
        <h3>Price Recommendation Tool</h3>
        <p>Get AI-powered pricing suggestions based on market data</p>
        <button onClick={getPricingRecommendation} className="btn btn-primary">
          Get Pricing Recommendation
        </button>
      </div>

      {materialTrends && (
        <div className="card">
          <h3>💡 Market Insights</h3>
          <ul className="insights-list">
            {materialTrends.insights.map((insight, i) => (
              <li key={i}>{insight}</li>
            ))}
          </ul>
        </div>
      )}
    </div>
  );
}

export default MarketTrendsPage;
