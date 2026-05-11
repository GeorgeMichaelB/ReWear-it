import React, { useState, useEffect } from 'react';
import { useAuth } from '../context/AuthContext';
import { authAPI } from '../services/api';

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

  if (!user) return <div style={{ textAlign: 'center', padding: 'var(--space-xl)' }}>Please login to view account settings.</div>;

  return (
    <div style={{ display: 'grid', gridTemplateColumns: '300px 1fr', gap: 'var(--space-xl)' }}>
      <aside>
        <div className="card-premium" style={{ position: 'sticky', top: '100px' }}>
          <h3 style={{ marginBottom: 'var(--space-md)' }}>My Account</h3>
          <ul style={{ listStyle: 'none', display: 'flex', flexDirection: 'column', gap: 'var(--space-sm)' }}>
            <li style={{ color: 'var(--color-primary)', fontWeight: 600 }}>Profile Settings</li>
            <li style={{ color: 'var(--color-text-muted)' }}>Security</li>
            <li style={{ color: 'var(--color-text-muted)' }}>Sustainability Impact</li>
          </ul>
        </div>
      </aside>

      <main>
        <div style={{ marginBottom: 'var(--space-lg)' }}>
          <h1 style={{ fontSize: '2.5rem' }}>Account Settings</h1>
          <p style={{ color: 'var(--color-text-muted)' }}>Manage your personal information and preferences.</p>
        </div>

        {success && <div style={{ backgroundColor: '#dcfce7', color: '#166534', padding: '1rem', borderRadius: 'var(--radius-sm)', marginBottom: 'var(--space-md)' }}>{success}</div>}
        {error && <div style={{ backgroundColor: '#fee2e2', color: '#991b1b', padding: '1rem', borderRadius: 'var(--radius-sm)', marginBottom: 'var(--space-md)' }}>{error}</div>}

        <div className="card-premium">
          <form onSubmit={handleProfileSubmit} style={{ display: 'flex', flexDirection: 'column', gap: 'var(--space-md)' }}>
            <div>
              <label style={{ display: 'block', marginBottom: '4px', fontWeight: 500 }}>Full Name</label>
              <input 
                type="text" 
                name="name" 
                value={formData.name} 
                onChange={handleChange} 
                className="input"
              />
            </div>
            <div>
              <label style={{ display: 'block', marginBottom: '4px', fontWeight: 500 }}>Email Address</label>
              <input 
                type="email" 
                name="email" 
                value={formData.email} 
                onChange={handleChange} 
                className="input"
              />
            </div>
            <button type="submit" className="btn-premium" disabled={loading}>
              {loading ? 'Saving...' : 'Save Changes'}
            </button>
          </form>
        </div>
      </main>
    </div>
  );
};

export default Account;