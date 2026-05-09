import { useState, useEffect } from 'react';
import { newsletterAPI } from '../services/api';
import './NewsletterPage.css';

function NewsletterPage() {
  const [newsletter, setNewsletter] = useState(null);
  const [pastNewsletters, setPastNewsletters] = useState([]);
  const [subscribers, setSubscribers] = useState(null);
  const [loading, setLoading] = useState(false);
  const [email, setEmail] = useState('');

  useEffect(() => {
    generateNewsletter();
    fetchPastNewsletters();
  }, []);

  const generateNewsletter = async () => {
    setLoading(true);
    try {
      const response = await newsletterAPI.generateWeekly();
      setNewsletter(response.data.newsletter);
    } catch (error) {
      console.error('Error generating newsletter:', error);
    }
    setLoading(false);
  };

  const fetchPastNewsletters = async () => {
    try {
      const response = await newsletterAPI.getPast();
      setPastNewsletters(response.data.newsletters || []);
    } catch (error) {
      console.error('Error fetching past newsletters:', error);
    }
  };

  const sendNewsletter = async (testOnly = false) => {
    if (!newsletter) return;
    try {
      await newsletterAPI.sendNewsletter({
        newsletter_id: newsletter.id,
        test_only: testOnly,
      });
      alert(testOnly ? 'Test email sent!' : 'Newsletter scheduled for delivery!');
    } catch (error) {
      console.error('Error sending newsletter:', error);
    }
  };

  const subscribe = async () => {
    if (!email) return;
    try {
      await newsletterAPI.subscribe({ email, preferences: ['trending', 'weekly'] });
      alert('Successfully subscribed!');
      setEmail('');
    } catch (error) {
      console.error('Error subscribing:', error);
    }
  };

  return (
    <div className="page-container newsletter-page">
      <h1>📧 Newsletter Management</h1>
      <p className="page-description">
        Auto-curated weekly newsletter with top 5 trending upcycled items
      </p>

      <div className="card">
        <h3>Subscribe to Newsletter</h3>
        <div className="subscribe-form">
          <input
            type="email"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            placeholder="Enter your email"
          />
          <button onClick={subscribe} className="btn btn-primary">Subscribe</button>
        </div>
      </div>

      <div className="card">
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', flexWrap: 'wrap', gap: '1rem' }}>
          <h3>This Week's Newsletter</h3>
          <div className="button-group">
            <button onClick={() => sendNewsletter(true)} className="btn btn-secondary">
              Send Test
            </button>
            <button onClick={() => sendNewsletter(false)} className="btn btn-primary">
              Send to Subscribers
            </button>
          </div>
        </div>

        {loading && <p>Generating newsletter...</p>}

        {newsletter && (
          <div className="newsletter-preview">
            <div className="newsletter-header">
              <h4>{newsletter.subject}</h4>
              <p>{newsletter.preheader}</p>
            </div>

            {newsletter.community_stats && (
              <div className="community-stats">
                <span>📊 {newsletter.community_stats.total_swaps_this_week} swaps</span>
                <span>🌱 {newsletter.community_stats.co2_saved_kg}kg CO₂ saved</span>
                <span>👥 {newsletter.community_stats.new_upcyclers} new upcyclers</span>
              </div>
            )}

            <div className="trending-items">
              <h4>🔥 Top 5 Trending Items</h4>
              {newsletter.featured_items?.map((item, index) => (
                <div key={index} className="trending-item">
                  <span className="rank">#{index + 1}</span>
                  <div className="item-info">
                    <h5>{item.name}</h5>
                    <p>by {item.seller} • ${item.price}</p>
                    <p className="item-stats">
                      👁 {item.views} views • 🔄 {item.swaps} swaps • 🌱 {item.sustainability_score}
                    </p>
                  </div>
                </div>
              ))}
            </div>
          </div>
        )}
      </div>

      <div className="card">
        <h3>Past Newsletters</h3>
        {pastNewsletters.map((nl, index) => (
          <div key={index} className="past-newsletter">
            <p><strong>{nl.subject}</strong></p>
            <p>Sent: {nl.sent_at} • Open Rate: {nl.open_rate}</p>
          </div>
        ))}
      </div>
    </div>
  );
}

export default NewsletterPage;