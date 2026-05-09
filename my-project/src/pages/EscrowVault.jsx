import { useState } from 'react';
import { escrowAPI } from '../services/api';

function EscrowVault() {
  const [escrowCreated, setEscrowCreated] = useState(null);
  const [escrowId, setEscrowId] = useState('');
  const [amount, setAmount] = useState('');
  const [itemId, setItemId] = useState('');
  const [buyerId, setBuyerId] = useState('');
  const [sellerId, setSellerId] = useState('');
  const [feeResult, setFeeResult] = useState(null);
  const [currencyResult, setCurrencyResult] = useState(null);
  const [loading, setLoading] = useState(false);

  const createEscrow = async () => {
    setLoading(true);
    try {
      const response = await escrowAPI.createEscrow({
        amount: parseFloat(amount),
        item_id: itemId,
        buyer_id: buyerId,
        seller_id: sellerId,
      });
      setEscrowCreated(response.data.escrow);
    } catch (error) {
      console.error('Error creating escrow:', error);
    }
    setLoading(false);
  };

  const verifyAndRelease = async (verified) => {
    if (!escrowId) return;
    try {
      const response = await escrowAPI.releaseFunds(escrowId, verified);
      setEscrowCreated(response.data.escrow);
      alert(verified ? 'Funds released to seller!' : 'Item verification failed');
    } catch (error) {
      console.error('Error releasing funds:', error);
    }
  };

  const calculateFee = async () => {
    try {
      const response = await escrowAPI.calculateFee(parseFloat(amount) || 100, 'standard');
      setFeeResult(response.data);
    } catch (error) {
      console.error('Error calculating fee:', error);
    }
  };

  const convertCurrency = async () => {
    try {
      const response = await escrowAPI.convertCurrency(100, 'USD', 'EUR');
      setCurrencyResult(response.data);
    } catch (error) {
      console.error('Error converting currency:', error);
    }
  };

  return (
    <div className="page-container">
      <h1>Escrow Vault</h1>
      <p className="page-description">
        Secure payment system - funds are held safely until item is verified
      </p>

      <div className="card">
        <h3>Create Escrow (Hold Funds)</h3>
        <div className="form-group">
          <label>Amount ($): </label>
          <input type="number" value={amount} onChange={(e) => setAmount(e.target.value)} placeholder="50" />
        </div>
        <div className="form-group">
          <label>Item ID: </label>
          <input type="text" value={itemId} onChange={(e) => setItemId(e.target.value)} placeholder="1" />
        </div>
        <div className="form-group">
          <label>Buyer ID: </label>
          <input type="number" value={buyerId} onChange={(e) => setBuyerId(e.target.value)} placeholder="2" />
        </div>
        <div className="form-group">
          <label>Seller ID: </label>
          <input type="number" value={sellerId} onChange={(e) => setSellerId(e.target.value)} placeholder="3" />
        </div>
        <button onClick={createEscrow} disabled={loading} className="btn btn-primary">
          {loading ? 'Creating...' : 'Hold Funds in Escrow'}
        </button>
      </div>

      {escrowCreated && (
        <div className="card highlight-card">
          <h3>Escrow Created</h3>
          <p><strong>Escrow ID:</strong> {escrowCreated.id}</p>
          <p><strong>Amount:</strong> ${escrowCreated.amount}</p>
          <p><strong>Status:</strong> {escrowCreated.status}</p>
          <p><strong>Held In:</strong> {escrowCreated.held_in}</p>
          
          <div style={{ marginTop: '1rem' }}>
            <h4>Verify & Release</h4>
            <p>Simulate buyer receiving the item:</p>
            <div style={{ display: 'flex', gap: '10px' }}>
              <button onClick={() => verifyAndRelease(true)} className="btn btn-success">
                Item Received - Release Funds
              </button>
              <button onClick={() => verifyAndRelease(false)} className="btn btn-danger">
                Not Received - Keep on Hold
              </button>
            </div>
          </div>
        </div>
      )}

      <div className="card">
        <h3>Platform Fees (UC-23)</h3>
        <p>Dynamic fees based on item type - lower for 100% upcycled goods</p>
        <button onClick={calculateFee} className="btn btn-secondary">
          Calculate Platform Fee
        </button>
        {feeResult && (
          <div className="result-box">
            <p>Base Fee (Standard Item): 10%</p>
            <p>Upcycled Item Fee: 4%</p>
            <p>Example: ${feeResult.amount || 100} item = ${feeResult.fee || 10} platform fee</p>
          </div>
        )}
      </div>

      <div className="card">
        <h3>Currency Conversion (UC-28)</h3>
        <p>Convert between different currencies for international transactions</p>
        <button onClick={convertCurrency} className="btn btn-secondary">
          Convert $100 USD to EUR
        </button>
        {currencyResult && (
          <div className="result-box">
            <p>Original: {currencyResult.original_amount} {currencyResult.original_currency}</p>
            <p>Converted: {currencyResult.converted_amount} {currencyResult.converted_currency}</p>
            <p>Exchange Rate: {currencyResult.exchange_rate}</p>
          </div>
        )}
      </div>
    </div>
  );
}

export default EscrowVault;