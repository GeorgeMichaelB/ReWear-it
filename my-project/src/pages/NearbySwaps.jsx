import { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { geospatialAPI } from '../services/api';
import './NearbySwaps.css';

function NearbySwaps() {
  const navigate = useNavigate();
  const [nearbyUsers, setNearbyUsers] = useState([]);
  const [nearbyItems, setNearbyItems] = useState([]);
  const [radius, setRadius] = useState(10);
  const [loading, setLoading] = useState(false);
  const [emissionsSaved, setEmissionsSaved] = useState(null);
  const [userLocation, setUserLocation] = useState({ latitude: 40.7128, longitude: -74.006 });
  const [locationSet, setLocationSet] = useState(false);

  const handleSetLocation = () => {
    navigator.geolocation.getCurrentPosition(
      (position) => {
        setUserLocation({
          latitude: position.coords.latitude,
          longitude: position.coords.longitude,
        });
        setLocationSet(true);
      },
      (error) => alert('Could not get location. Using default.')
    );
  };

  const searchNearby = async () => {
    setLoading(true);
    try {
      const response = await geospatialAPI.findNearbyUsers(
        userLocation.latitude,
        userLocation.longitude,
        radius
      );
      setNearbyUsers(response.data.nearby_users || []);
      setEmissionsSaved(response.data.emissions_saved_kg);
    } catch (error) {
      console.error('Error finding nearby users:', error);
    }
    setLoading(false);
  };

  const searchNearbyItems = async () => {
    setLoading(true);
    try {
      const response = await geospatialAPI.findNearbyItems(
        userLocation.latitude,
        userLocation.longitude,
        radius
      );
      setNearbyItems(response.data.items || []);
    } catch (error) {
      console.error('Error finding nearby items:', error);
    }
    setLoading(false);
  };

  useEffect(() => {
    handleSetLocation();
  }, []);

  return (
    <div className="page-container">
      <h1>Nearby Swaps - In-Person Exchange</h1>
      <p className="page-description">
        Find users and items near you to eliminate shipping emissions!
      </p>

      <div className="card">
        <h3>Your Location</h3>
        <div className="form-group">
          <label>Latitude: </label>
          <input
            type="number"
            step="0.0001"
            value={userLocation.latitude}
            onChange={(e) => setUserLocation({ ...userLocation, latitude: parseFloat(e.target.value) })}
          />
        </div>
        <div className="form-group">
          <label>Longitude: </label>
          <input
            type="number"
            step="0.0001"
            value={userLocation.longitude}
            onChange={(e) => setUserLocation({ ...userLocation, longitude: parseFloat(e.target.value) })}
          />
        </div>
        <div className="form-group">
          <label>Search Radius (km): </label>
          <input
            type="number"
            min="1"
            max="50"
            value={radius}
            onChange={(e) => setRadius(parseInt(e.target.value))}
          />
        </div>
        <button onClick={searchNearby} disabled={loading} className="btn btn-primary">
          {loading ? 'Searching...' : 'Find Nearby Swappers'}
        </button>
      </div>

      {emissionsSaved !== null && (
        <div className="card highlight-card">
          <h3>Environmental Impact</h3>
          <p className="impact-text">
            CO₂ Emissions Saved: {emissionsSaved} kg
          </p>
          <p>Choosing in-person swaps significantly reduces your carbon footprint!</p>
        </div>
      )}

      {nearbyUsers.length > 0 && (
        <div className="card">
          <h3>Nearby Users ({nearbyUsers.length})</h3>
          <div className="item-grid">
            {nearbyUsers.map((user, index) => (
              <div key={index} className="item-card">
                <h4>{user.user.name}</h4>
                <p>Distance: {user.distance_km} km</p>
                <p>Trust Score: {user.user.trust_score}</p>
                <p>Available Items: {user.available_items}</p>
                <button className="btn btn-secondary">View Profile</button>
              </div>
            ))}
          </div>
        </div>
      )}

      <div className="card">
        <button onClick={searchNearbyItems} disabled={loading} className="btn btn-secondary">
          Find Nearby Items
        </button>
      </div>

      {nearbyItems.length > 0 && (
        <div className="card">
          <h3>Nearby Items ({nearbyItems.length})</h3>
          <div className="item-grid">
            {nearbyItems.map((itemWrapper, index) => (
              <div key={index} className="item-card">
                <h4>{itemWrapper.item.name}</h4>
                <p>Price: ${itemWrapper.item.price}</p>
                <p>Distance: {itemWrapper.distance_km} km</p>
                <button 
  className="btn btn-primary" 
  onClick={() => navigate(`/products/${itemWrapper.item.id}`)}
>
  View Item
</button>
              </div>
            ))}
          </div>
        </div>
      )}
    </div>
  );
}

export default NearbySwaps;