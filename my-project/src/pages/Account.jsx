import React, { useState, useEffect } from 'react';
import { useAuth } from '../context/AuthContext';
import { authAPI } from '../services/api';
import './Account.css';

const Account = () => {
  const { user, updateUser } = useAuth();
  const [loading, setLoading] = useState(false);
  const [success, setSuccess] = useState('');
  const [error, setError] = useState('');

  const [formData, setFormData] = useState({
    name: '',
    email: '',
    phone: '',
    preferred_currency: 'USD',
  });

  const [passwordData, setPasswordData] = useState({
    current_password: '',
    new_password: '',
    new_password_confirmation: '',
  });

  useEffect(() => {
    if (user) {
      setFormData({
        name: user.name || '',
        email: user.email || '',
        phone: user.phone || '',
        preferred_currency: user.preferred_currency || 'USD',
      });
    }
  }, [user]);

  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handlePasswordChange = (e) => {
    setPasswordData({ ...passwordData, [e.target.name]: e.target.value });
  };

  const handleProfileSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setSuccess('');
    setError('');
    try {
      const response = await authAPI.updateProfile(formData);
      updateUser(response.data.user);
      setSuccess('Profile updated successfully!');
    } catch (err) {
      setError(err.response?.data?.message || 'Failed to update profile');
    } finally {
      setLoading(false);
    }
  };

  const handlePasswordSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setSuccess('');
    setError('');
    try {
      await authAPI.changePassword({
        current_password: passwordData.current_password,
        new_password: passwordData.new_password,
        new_password_confirmation: passwordData.new_password_confirmation,
      });
      setSuccess('Password changed successfully!');
      setPasswordData({ current_password: '', new_password: '', new_password_confirmation: '' });
    } catch (err) {
      setError(err.response?.data?.message || 'Failed to change password');
    } finally {
      setLoading(false);
    }
  };

  if (!user) {
    return (
      <div className="account-page">
        <p>Please <a href="/login">login</a> to view your account.</p>
      </div>
    );
  }

  return (
    <div className="account-page">
      <h2>My Account</h2>

      {success && <div className="success-message">{success}</div>}
      {error && <div className="error-message">{error}</div>}

      <div className="account-section">
        <h3>Profile Information</h3>
        <form onSubmit={handleProfileSubmit}>
          <div className="form-group-base">
            <label>Name</label>
            <input
              type="text"
              name="name"
              value={formData.name}
              onChange={handleChange}
            />
          </div>
          <div className="form-group-base">
            <label>Email</label>
            <input
              type="email"
              name="email"
              value={formData.email}
              onChange={handleChange}
            />
          </div>
          <div className="form-group-base">
            <label>Phone</label>
            <input
              type="text"
              name="phone"
              value={formData.phone || ''}
              onChange={handleChange}
            />
          </div>
          <div className="form-group-base">
            <label>Currency</label>
            <select name="preferred_currency" value={formData.preferred_currency} onChange={handleChange}>
              <option value="USD">USD</option>
              <option value="EUR">EUR</option>
              <option value="GBP">GBP</option>
              <option value="EGP">EGP</option>
            </select>
          </div>
<button type="submit" className="btn btn-primary" disabled={loading}>
  {loading ? 'Saving...' : 'Save Changes'}
</button>
        </form>
      </div>

      <div className="account-section">
        <h3>Change Password</h3>
        <form onSubmit={handlePasswordSubmit}>
          <div className="form-group-base">
            <label>Current Password</label>
            <input
              type="password"
              name="current_password"
              value={passwordData.current_password}
              onChange={handlePasswordChange}
            />
          </div>
          <div className="form-group-base">
            <label>New Password</label>
            <input
              type="password"
              name="new_password"
              value={passwordData.new_password}
              onChange={handlePasswordChange}
            />
          </div>
          <div className="form-group-base">
            <label>Confirm New Password</label>
            <input
              type="password"
              name="new_password_confirmation"
              value={passwordData.new_password_confirmation}
              onChange={handlePasswordChange}
            />
          </div>
<button type="submit" className="btn btn-primary" disabled={loading}>
  {loading ? 'Changing...' : 'Change Password'}
</button>
        </form>
      </div>

      <div className="account-section">
        <h3>Account Stats</h3>
        <div className="stats-grid">
          <div className="stat-box">
            <span className="stat-label">Trust Score</span>
            <span className="stat-value">{user.trust_score || 'N/A'}</span>
          </div>
          <div className="stat-box">
            <span className="stat-label">Eco Credits</span>
            <span className="stat-value">{user.eco_credits || 0}</span>
          </div>
          <div className="stat-box">
            <span className="stat-label">Role</span>
            <span className="stat-value">{user.role || 'User'}</span>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Account;
