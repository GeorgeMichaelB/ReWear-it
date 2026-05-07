import React, { useState } from 'react';
import { useAuth } from '../context/AuthContext';
import { useNavigate } from 'react-router-dom';

const Register = () => {
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
  });
  const [error, setError] = useState('');
  const { register } = useAuth();
  const navigate = useNavigate();

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    try {
      await register(formData);
      navigate('/');
    } catch (err) {
      const msg = err.response?.data?.message || err.response?.data?.errors?.email?.[0] || 'Registration failed';
      setError(msg);
    }
  };

  return (
    <div className="container">
      <div className="row margin-bottom-40">
        <div className="col-md-4 col-md-offset-4">
          <div className="content-form-page">
            <div className="row">
              <div className="col-md-12">
                <form onSubmit={handleSubmit} className="form-horizontal form-without-legend">
                  <h3>Create Account</h3>
                  {error && <div className="alert alert-danger">{error}</div>}
                  <div className="form-group">
                    <label htmlFor="name">Name <span className="require">*</span></label>
                    <input
                      type="text"
                      id="name"
                      className="form-control"
                      value={formData.name}
                      onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                      required
                    />
                  </div>
                  <div className="form-group">
                    <label htmlFor="email">Email <span className="require">*</span></label>
                    <input
                      type="email"
                      id="email"
                      className="form-control"
                      value={formData.email}
                      onChange={(e) => setFormData({ ...formData, email: e.target.value })}
                      required
                    />
                  </div>
                  <div className="form-group">
                    <label htmlFor="password">Password <span className="require">*</span></label>
                    <input
                      type="password"
                      id="password"
                      className="form-control"
                      value={formData.password}
                      onChange={(e) => setFormData({ ...formData, password: e.target.value })}
                      required
                    />
                  </div>
                  <div className="form-group">
                    <label htmlFor="password_confirmation">Confirm Password <span className="require">*</span></label>
                    <input
                      type="password"
                      id="password_confirmation"
                      className="form-control"
                      value={formData.password_confirmation}
                      onChange={(e) => setFormData({ ...formData, password_confirmation: e.target.value })}
                      required
                    />
                  </div>
                  <div className="row">
                    <div className="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-left">
                      <button type="submit" className="btn btn-primary">Sign Up</button>
                    </div>
                  </div>
                  <div className="text-center">
                    <a href="/login">Already have an account? Sign In</a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Register;