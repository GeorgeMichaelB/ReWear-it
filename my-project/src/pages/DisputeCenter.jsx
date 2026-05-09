import { useState } from 'react';
import { disputeAPI } from '../services/api';

function DisputeCenter() {
  const [disputes, setDisputes] = useState([]);
  const [selectedDispute, setSelectedDispute] = useState(null);
  const [showCreateForm, setShowCreateForm] = useState(false);
  const [newDispute, setNewDispute] = useState({
    order_id: '',
    dispute_type: 'not_received',
    description: '',
  });
  const [loading, setLoading] = useState(false);

  const fetchDisputes = async (status = 'all') => {
    setLoading(true);
    try {
      const response = await disputeAPI.getAllDisputes(status);
      setDisputes(response.data.disputes || []);
    } catch (error) {
      console.error('Error fetching disputes:', error);
    }
    setLoading(false);
  };

  const fetchDisputeDetails = async (disputeId) => {
    try {
      const response = await disputeAPI.getDisputeDetails(disputeId);
      setSelectedDispute(response.data);
    } catch (error) {
      console.error('Error fetching dispute details:', error);
    }
  };

  const createDispute = async () => {
    try {
      const response = await disputeAPI.createDispute(newDispute);
      alert('Dispute created successfully!');
      setShowCreateForm(false);
      setNewDispute({ order_id: '', dispute_type: 'not_received', description: '' });
      fetchDisputes();
    } catch (error) {
      console.error('Error creating dispute:', error);
    }
  };

  const resolveDispute = async (disputeId, resolution, reason) => {
    try {
      await disputeAPI.resolveDispute(disputeId, { resolution, reason });
      alert('Dispute resolved!');
      setSelectedDispute(null);
      fetchDisputes();
    } catch (error) {
      console.error('Error resolving dispute:', error);
    }
  };

  return (
    <div className="page-container">
      <h1>Dispute Mediation Center</h1>
      <p className="page-description">
        Admin hub for resolving conflicts - review chat logs, photos, and evidence
      </p>

      <div className="card">
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
          <h3>All Disputes</h3>
          <div>
            <button onClick={() => fetchDisputes('all')} className="btn btn-secondary">All</button>
            <button onClick={() => fetchDisputes('pending')} className="btn btn-secondary">Pending</button>
            <button onClick={() => fetchDisputes('resolved')} className="btn btn-secondary">Resolved</button>
            <button onClick={() => setShowCreateForm(!showCreateForm)} className="btn btn-primary">
              {showCreateForm ? 'Cancel' : 'New Dispute'}
            </button>
          </div>
        </div>

        {loading && <p>Loading disputes...</p>}

        <div className="dispute-list">
          {disputes.map((dispute, index) => (
            <div key={index} className="dispute-item" onClick={() => fetchDisputeDetails(dispute.id)}>
              <p><strong>ID:</strong> {dispute.id}</p>
              <p><strong>Order:</strong> {dispute.order_id}</p>
              <p><strong>Type:</strong> {dispute.type}</p>
              <span className={`status-badge ${dispute.status}`}>{dispute.status}</span>
            </div>
          ))}
        </div>
      </div>

      {showCreateForm && (
        <div className="card">
          <h3>Create New Dispute</h3>
          <div className="form-group">
            <label>Order ID: </label>
            <input
              type="text"
              value={newDispute.order_id}
              onChange={(e) => setNewDispute({ ...newDispute, order_id: e.target.value })}
            />
          </div>
          <div className="form-group">
            <label>Dispute Type: </label>
            <select
              value={newDispute.dispute_type}
              onChange={(e) => setNewDispute({ ...newDispute, dispute_type: e.target.value })}
            >
              <option value="not_received">Not Received</option>
              <option value="not_as_described">Not As Described</option>
              <option value="damaged">Damaged</option>
              <option value="other">Other</option>
            </select>
          </div>
          <div className="form-group">
            <label>Description: </label>
            <textarea
              value={newDispute.description}
              onChange={(e) => setNewDispute({ ...newDispute, description: e.target.value })}
              rows="4"
            />
          </div>
          <button onClick={createDispute} className="btn btn-primary">Submit Dispute</button>
        </div>
      )}

      {selectedDispute && (
        <div className="card highlight-card">
          <h3>Dispute Details: {selectedDispute.dispute.id}</h3>
          
          <div className="dispute-section">
            <h4>Case Information</h4>
            <p><strong>Type:</strong> {selectedDispute.dispute.type}</p>
            <p><strong>Description:</strong> {selectedDispute.dispute.description}</p>
            <p><strong>Status:</strong> {selectedDispute.dispute.status}</p>
          </div>

          {selectedDispute.chat_logs && (
            <div className="dispute-section">
              <h4>Chat Logs</h4>
              <div className="chat-logs">
                {selectedDispute.chat_logs.map((log, i) => (
                  <div key={i} className={`chat-message ${log.sender}`}>
                    <strong>{log.sender}:</strong> {log.message}
                    <span className="timestamp">{log.timestamp}</span>
                  </div>
                ))}
              </div>
            </div>
          )}

          {selectedDispute.dispute.buyer_evidence && (
            <div className="dispute-section">
              <h4>Buyer Evidence</h4>
              <p>{selectedDispute.dispute.buyer_evidence.description}</p>
              <p>Photos: {selectedDispute.dispute.buyer_evidence.photos?.join(', ')}</p>
            </div>
          )}

          <div className="dispute-section">
            <h4>Resolution Options</h4>
            <div style={{ display: 'flex', gap: '10px', flexWrap: 'wrap' }}>
              <button
                onClick={() => resolveDispute(selectedDispute.dispute.id, 'buyer_wins', 'Full refund - item not as described')}
                className="btn btn-success"
              >
                Buyer Wins (Full Refund)
              </button>
              <button
                onClick={() => resolveDispute(selectedDispute.dispute.id, 'partial_refund', 'Partial refund for minor issue')}
                className="btn btn-warning"
              >
                Partial Refund
              </button>
              <button
                onClick={() => resolveDispute(selectedDispute.dispute.id, 'return_required', 'Return required for inspection')}
                className="btn btn-secondary"
              >
                Return Required
              </button>
              <button
                onClick={() => resolveDispute(selectedDispute.dispute.id, 'seller_wins', 'Item was as described')}
                className="btn btn-danger"
              >
                Seller Wins
              </button>
            </div>
          </div>

          <button onClick={() => setSelectedDispute(null)} className="btn btn-secondary" style={{ marginTop: '1rem' }}>
            Close Details
          </button>
        </div>
      )}
    </div>
  );
}

export default DisputeCenter;