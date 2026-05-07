import React, { useState, useEffect } from 'react';
import { useAuth } from '../context/AuthContext';
import { authAPI } from '../services/api';
import { useNavigate } from 'react-router-dom';

const Account = () => {
  const { user, updateUser } = useAuth();
  const navigate = useNavigate();
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
    password: '',
    password_confirmation: '',
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
      await authAPI.changePassword(passwordData);
      setSuccess('Password changed successfully!');
      setPasswordData({ current_password: '', password: '', password_confirmation: '' });
    } catch (err) {
      setError(err.response?.data?.message || 'Failed to change password');
    } finally {
      setLoading(false);
    }
  };

  if (!user) {
    return (
      <div className="container">
        <div className="row margin-bottom-40">
          <div className="col-md-12">
            <div className="alert alert-warning">Please <a href="/login">login</a> to view your account.</div>
          </div>
        </div>
      </div>
    );
  }

  return (
    <div className="container">
      <div className="row margin-bottom-40">
        <div className="col-md-3">
          <div className="sidebar">
            <h3>My Account</h3>
            <ul className="list-group">
              <li className="list-group-item active">Profile Settings</li>
              <li className="list-group-item"><a href="#">My Items</a></li>
              <li className="list-group-item"><a href="#">Swap History</a></li>
              <li className="list-group-item"><a href="#">Saved Items</a></li>
              <li className="list-group-item"><a href="#">Addresses</a></li>
            </ul>
          </div>
        </div>
        
        <div className="col-md-9">
          {success && <div className="alert alert-success">{success}</div>}
          {error && <div className="alert alert-danger">{error}</div>}
          
          <div className="content-form-page">
            <h2>Profile Information</h2>
            <form className="form-horizontal" onSubmit={handleProfileSubmit}>
              <div className="form-group">
                <label className="col-lg-2 control-label" htmlFor="name">Name <span className="require">*</span></label>
                <div className="col-lg-8">
                  <input
                    type="text"
                    id="name"
                    name="name"
                    className="form-control"
                    value={formData.name}
                    onChange={handleChange}
                    required
                  />
                </div>
              </div>
              
              <div className="form-group">
                <label className="col-lg-2 control-label" htmlFor="email">Email <span className="require">*</span></label>
                <div className="col-lg-8">
                  <input
                    type="email"
                    id="email"
                    name="email"
                    className="form-control"
                    value={formData.email}
                    onChange={handleChange}
                    required
                  />
                </div>
              </div>
              
              <div className="form-group">
                <label className="col-lg-2 control-label" htmlFor="phone">Phone</label>
                <div className="col-lg-8">
                  <input
                    type="text"
                    id="phone"
                    name="phone"
                    className="form-control"
                    value={formData.phone || ''}
                    onChange={handleChange}
                    placeholder="Enter phone number"
                  />
                </div>
              </div>
              
              <div className="form-group">
                <label className="col-lg-2 control-label" htmlFor="preferred_currency">Preferred Currency</label>
                <div className="col-lg-8">
                  <select
                    id="preferred_currency"
                    name="preferred_currency"
                    className="form-control"
                    value={formData.preferred_currency}
                    onChange={handleChange}
                  >
                    <option value="USD">USD - US Dollar</option>
                    <option value="EUR">EUR - Euro</option>
                    <option value="GBP">GBP - British Pound</option>
                    <option value="EGP">EGP - Egyptian Pound</option>
                  </select>
                </div>
              </div>
              
              <div className="row">
                <div className="col-lg-8 col-lg-offset-2">
                  <button className="btn btn-primary" type="submit" disabled={loading}>
                    {loading ? 'Saving...' : 'Save Changes'}
                  </button>
                </div>
              </div>
            </form>
            
            <hr className="margin-top-40" />
            
            <h2>Change Password</h2>
            <form className="form-horizontal" onSubmit={handlePasswordSubmit}>
              <div className="form-group">
                <label className="col-lg-2 control-label" htmlFor="current_password">Current Password <span className="require">*</span></label>
                <div className="col-lg-8">
                  <input
                    type="password"
                    id="current_password"
                    name="current_password"
                    className="form-control"
                    value={passwordData.current_password}
                    onChange={handlePasswordChange}
                    required
                  />
                </div>
              </div>
              
              <div className="form-group">
                <label className="col-lg-2 control-label" htmlFor="password">New Password <span className="require">*</span></label>
                <div className="col-lg-8">
                  <input
                    type="password"
                    id="password"
                    name="password"
                    className="form-control"
                    value={passwordData.password}
                    onChange={handlePasswordChange}
                    required
                    minLength="6"
                  />
                </div>
              </div>
              
              <div className="form-group">
                <label className="col-lg-2 control-label" htmlFor="password_confirmation">Confirm Password <span className="require">*</span></label>
                <div className="col-lg-8">
                  <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    className="form-control"
                    value={passwordData.password_confirmation}
                    onChange={handlePasswordChange}
                    required
                  />
                </div>
              </div>
              
              <div className="row">
                <div className="col-lg-8 col-lg-offset-2">
                  <button className="btn btn-primary" type="submit" disabled={loading}>
                    {loading ? 'Changing...' : 'Change Password'}
                  </button>
                </div>
              </div>
            </form>
            
            <hr className="margin-top-40" />
            
            <h2>Account Stats</h2>
            <div className="row">
              <div className="col-md-4">
                <div className="stat-box">
                  <h4>Trust Score</h4>
                  <div className="big-number">{user.trust_score || 'N/A'}</div>
                </div>
              </div>
              <div className="col-md-4">
                <div className="stat-box">
                  <h4>Eco Credits</h4>
                  <div className="big-number">{user.eco_credits || 0}</div>
                </div>
              </div>
              <div className="col-md-4">
                <div className="stat-box">
                  <h4>Role</h4>
                  <div className="big-number" style={{textTransform: 'capitalize'}}>{user.role || 'User'}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Account;