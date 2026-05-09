import React, { useState, useEffect } from 'react';
import { itemsAPI, swapBundleAPI, categoriesAPI } from '../services/api';
import { useAuth } from '../context/AuthContext';

const SwapProposal = () => {
  const { user } = useAuth();
  const [myItems, setMyItems] = useState([]);
  const [requestItems, setRequestItems] = useState([]);
  const [categories, setCategories] = useState([]);
  const [offerItems, setOfferItems] = useState([]);
  const [requestItemSelect, setRequestItemSelect] = useState([]);
  const [proposal, setProposal] = useState(null);
  const [topUp, setTopUp] = useState(null);
  const [thresholds, setThresholds] = useState({ auto_accept: '', auto_decline: '' });
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchData();
  }, []);

  const fetchData = async () => {
    try {
      const [itemsRes, catsRes] = await Promise.all([
        itemsAPI.getAll(),
        categoriesAPI.getAll(),
      ]);
      const allItems = itemsRes.data.data || itemsRes.data;
      // My items = items belonging to current seller
      const myItemsList = allItems.filter(i => i.seller_id === user?.id && i.status === 'available');
      // Other items to request
      const otherItems = allItems.filter(i => i.seller_id !== user?.id && i.status === 'available');
      setMyItems(myItemsList);
      setRequestItems(otherItems);
      setCategories(catsRes.data.data || catsRes.data);
    } catch (error) {
      console.error('Error fetching data:', error);
    } finally {
      setLoading(false);
    }
  };

  const toggleOfferItem = (item) => {
    const exists = offerItems.find(i => i.id === item.id);
    if (exists) {
      setOfferItems(offerItems.filter(i => i.id !== item.id));
    } else {
      setOfferItems([...offerItems, item]);
    }
  };

  const toggleRequestItem = (item) => {
    const exists = requestItemSelect.find(i => i.id === item.id);
    if (exists) {
      setRequestItemSelect(requestItemSelect.filter(i => i.id !== item.id));
    } else {
      setRequestItemSelect([...requestItemSelect, item]);
    }
  };

  const createProposal = async () => {
    if (offerItems.length === 0 || requestItemSelect.length === 0) {
      alert('Please select items to offer and items to request');
      return;
    }
    try {
      const response = await swapBundleAPI.createProposal({
        offer_items: offerItems.map(i => i.id),
        request_items: requestItemSelect.map(i => i.id),
        recipient_id: requestItemSelect[0].seller_id,
      });
      setProposal(response.data);
      
      // Calculate top-up
      const topUpRes = await swapBundleAPI.calculateTopUp({
        offer_value: offerItems.reduce((sum, i) => sum + (i.price || 0), 0),
        request_value: requestItemSelect.reduce((sum, i) => sum + (i.price || 0), 0),
      });
      setTopUp(topUpRes.data);
    } catch (error) {
      alert('Error creating proposal');
    }
  };

  const saveThresholds = async () => {
    try {
      await swapBundleAPI.setThresholds({
        auto_accept_threshold: parseFloat(thresholds.auto_accept),
        auto_decline_threshold: parseFloat(thresholds.auto_decline),
      });
      alert('Bargaining thresholds saved!');
    } catch (error) {
      alert('Error saving thresholds');
    }
  };

  if (loading) return <div className="loading">Loading...</div>;

  return (
    <div className="swap-proposal-page">
      <h2>🔄 Swap Proposal Builder</h2>
      
      <div className="proposal-section">
        <h3>Your Items to Offer</h3>
        <div className="item-grid">
          {myItems.map(item => (
            <div 
              key={item.id} 
              className={`item-card selectable ${offerItems.find(i => i.id === item.id) ? 'selected' : ''}`}
              onClick={() => toggleOfferItem(item)}
            >
              <h4>{item.title}</h4>
              <p>${item.price}</p>
              <span className="condition">{item.condition}</span>
            </div>
          ))}
        </div>
        {offerItems.length > 0 && (
          <p className="total">Your Offer Value: ${offerItems.reduce((sum, i) => sum + (i.price || 0), 0).toFixed(2)}</p>
        )}
      </div>

      <div className="proposal-section">
        <h3>Items You Want</h3>
        <div className="item-grid">
          {requestItems.map(item => (
            <div 
              key={item.id} 
              className={`item-card selectable ${requestItemSelect.find(i => i.id === item.id) ? 'selected' : ''}`}
              onClick={() => toggleRequestItem(item)}
            >
              <h4>{item.title}</h4>
              <p>${item.price}</p>
              <span className="seller">Seller: {item.seller_id}</span>
            </div>
          ))}
        </div>
        {requestItemSelect.length > 0 && (
          <p className="total">Request Value: ${requestItemSelect.reduce((sum, i) => sum + (i.price || 0), 0).toFixed(2)}</p>
        )}
      </div>

      <button className="btn-primary create-btn" onClick={createProposal}>
        Create Proposal
      </button>

      {proposal && (
        <div className="proposal-result">
          <h3>Proposal Created!</h3>
          <p>Proposal ID: {proposal.proposal_id}</p>
          <p>Status: {proposal.status}</p>
          <p>Expires: {proposal.expires_at}</p>
        </div>
      )}

      {topUp && (
        <div className={`top-up-result ${topUp.is_fair_swap ? 'fair' : 'unfair'}`}>
          <h4>Value Analysis</h4>
          <p>{topUp.message}</p>
          {topUp.suggested_top_up > 0 && (
            <p className="top-up-amount">💰 Suggested Cash Top-up: ${topUp.suggested_top_up}</p>
          )}
        </div>
      )}

      <div className="bargaining-section">
        <h3>🤝 Bargaining Settings</h3>
        <div className="threshold-inputs">
          <div className="form-group">
            <label>Auto-Accept Price ($)</label>
            <input 
              type="number" 
              value={thresholds.auto_accept}
              onChange={(e) => setThresholds({...thresholds, auto_accept: e.target.value})}
              placeholder="e.g., 50"
            />
          </div>
          <div className="form-group">
            <label>Auto-Decline Price ($)</label>
            <input 
              type="number" 
              value={thresholds.auto_decline}
              onChange={(e) => setThresholds({...thresholds, auto_decline: e.target.value})}
              placeholder="e.g., 10"
            />
          </div>
          <button className="btn-primary" onClick={saveThresholds}>Save Thresholds</button>
        </div>
      </div>
    </div>
  );
};

export default SwapProposal;