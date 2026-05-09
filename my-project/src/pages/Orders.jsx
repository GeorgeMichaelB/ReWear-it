import React, { useState, useEffect } from 'react';
import { ordersAPI } from '../services/api';
import { useAuth } from '../context/AuthContext';
import './Orders.css';

const Orders = () => {
  const { user } = useAuth();
  const [orders, setOrders] = useState([]);
  const [loading, setLoading] = useState(true);
  const [activeTab, setActiveTab] = useState('buyer');

  useEffect(() => {
    fetchOrders();
  }, [activeTab]);

  const fetchOrders = async () => {
    try {
      setLoading(true);
      const response = activeTab === 'seller'
        ? await ordersAPI.getSellerOrders()
        : await ordersAPI.getAll();
      setOrders(response.data.data || response.data);
    } catch (error) {
      console.error('Error fetching orders:', error);
    } finally {
      setLoading(false);
    }
  };

  const handleStatusUpdate = async (orderId, status) => {
    try {
      await ordersAPI.updateStatus(orderId, status);
      fetchOrders();
    } catch (error) {
      alert('Error updating status');
    }
  };

  if (loading) return <div className="loading">Loading...</div>;

  return (
    <div className="orders-page">
      <h2>Order History</h2>

      {user?.role === 'seller' && (
        <div className="tabs">
          <button
            className={activeTab === 'buyer' ? 'active' : ''}
            onClick={() => setActiveTab('buyer')}
          >
            My Purchases
          </button>
          <button
            className={activeTab === 'seller' ? 'active' : ''}
            onClick={() => setActiveTab('seller')}
          >
            My Sales
          </button>
        </div>
      )}

      {orders.length === 0 ? (
        <p className="no-orders">No orders found.</p>
      ) : (
        <div className="orders-list">
          {orders.map((order) => (
            <div key={order.id} className="order-card">
              <div className="order-header">
                <span className="order-id">Order #{order.id}</span>
                <span className={`order-status status-${order.status}`}>
                  {order.status}
                </span>
              </div>
              <div className="order-details">
                <p>Total: ${order.total}</p>
                <p>Items: {order.items?.length || 0}</p>
                {order.created_at && (
                  <p>Date: {new Date(order.created_at).toLocaleDateString()}</p>
                )}
              </div>
              {activeTab === 'seller' && order.status === 'pending' && (
                <div className="order-actions">
                  <button onClick={() => handleStatusUpdate(order.id, 'accepted')}>
                    Accept
                  </button>
                  <button onClick={() => handleStatusUpdate(order.id, 'rejected')}>
                    Reject
                  </button>
                </div>
              )}
            </div>
          ))}
        </div>
      )}
    </div>
  );
};

export default Orders;