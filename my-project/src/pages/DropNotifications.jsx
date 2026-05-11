import { useState, useEffect } from 'react';
import { notificationsAPI } from '../services/api';

function DropNotifications() {
  const [drops, setDrops] = useState([]);
  const [notifications, setNotifications] = useState([]);
  const [subscriptions, setSubscriptions] = useState([]);
  const [loading, setLoading] = useState(false);
  const [showCreateDrop, setShowCreateDrop] = useState(false);
  const [newDrop, setNewDrop] = useState({ title: '', items: [] });

  useEffect(() => {
    fetchActiveDrops();
    fetchNotifications();
    fetchSubscriptions();
  }, []);

  const fetchActiveDrops = async () => {
    setLoading(true);
    try {
      const response = await notificationsAPI.getActiveDrops();
      setDrops(response.data.drops || []);
    } catch (error) {
      console.error('Error fetching drops:', error);
    }
    setLoading(false);
  };

  const fetchNotifications = async () => {
    try {
      const response = await notificationsAPI.getNotifications();
      setNotifications(response.data.notifications || []);
    } catch (error) {
      console.error('Error fetching notifications:', error);
    }
  };

  const fetchSubscriptions = async () => {
    try {
      const response = await notificationsAPI.getDropSubscriptions();
      setSubscriptions(response.data.subscriptions || []);
    } catch (error) {
      console.error('Error fetching subscriptions:', error);
    }
  };

  const createDrop = async () => {
    try {
      await notificationsAPI.createDrop({
        seller_id: 1,
        title: newDrop.title,
        items: [{ name: 'New Item 1', price: 50 }, { name: 'New Item 2', price: 35 }],
      });
      alert('Drop created and followers notified!');
      setShowCreateDrop(false);
      setNewDrop({ title: '', items: [] });
      fetchActiveDrops();
    } catch (error) {
      console.error('Error creating drop:', error);
    }
  };

  const followSeller = async (sellerId) => {
    try {
      await notificationsAPI.followSeller({ seller_id: sellerId });
      alert('Now following seller for drop notifications!');
      fetchSubscriptions();
    } catch (error) {
      console.error('Error following seller:', error);
    }
  };

  return (
    <div className="page-container">
      <h1>🔔 Live Drops & Notifications</h1>
      <p className="page-description">
        Get instant notifications when your favorite upcyclers list new items!
      </p>

      <div className="card">
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
          <h3>Your Notifications</h3>
          <span className="badge">{notifications.filter(n => !n.read).length} unread</span>
        </div>
        
        <div className="notification-list">
          {notifications.map((notif, index) => (
            <div key={index} className={`notification-item ${!notif.read ? 'unread' : ''}`}>
              <span className="notification-type">{notif.type}</span>
              <p>{notif.title}</p>
              <span className="timestamp">{notif.created_at}</span>
            </div>
          ))}
        </div>
      </div>

      <div className="card">
        <h3>Seller Subscriptions</h3>
        {subscriptions.length === 0 ? (
          <p>No subscriptions yet. Follow sellers to get drop notifications!</p>
        ) : (
          <div className="item-grid">
            {subscriptions.map((sub, index) => (
              <div key={index} className="item-card">
                <h4>{sub.seller_name}</h4>
                <span className="badge success">Drop notifications ON</span>
              </div>
            ))}
          </div>
        )}
        
        <div style={{ marginTop: '1rem' }}>
          <button onClick={() => followSeller(1)} className="btn btn-primary">
            Follow a Seller
          </button>
        </div>
      </div>

      <div className="card">
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
          <h3>Active Drops</h3>
          <button onClick={() => setShowCreateDrop(!showCreateDrop)} className="btn btn-secondary">
            {showCreateDrop ? 'Cancel' : 'Create Drop'}
          </button>
        </div>

        {showCreateDrop && (
           <div className="form-group-base" style={{ marginTop: '1rem' }}>
            <label>Drop Title: </label>
            <input
              type="text"
              value={newDrop.title}
              onChange={(e) => setNewDrop({ ...newDrop, title: e.target.value })}
              placeholder="Summer Collection Drop"
            />
            <button onClick={createDrop} className="btn btn-primary" style={{ marginTop: '0.5rem' }}>
              Go Live & Notify Followers
            </button>
          </div>
        )}

        {loading && <p>Loading drops...</p>}
        
        {drops.length === 0 ? (
          <p>No active drops currently. Create one to notify your followers!</p>
        ) : (
          <div className="item-grid">
            {drops.map((drop, index) => (
              <div key={index} className="item-card highlight">
                <span className="badge success">LIVE</span>
                <h4>{drop.title}</h4>
                <p>{drop.items?.length || 0} items</p>
                <p>Ends: {drop.ends_at}</p>
                <button className="btn btn-primary">View Drop</button>
              </div>
            ))}
          </div>
        )}
      </div>
    </div>
  );
}

export default DropNotifications;