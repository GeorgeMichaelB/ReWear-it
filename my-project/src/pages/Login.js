import React, { useState } from 'react';
import { useAuth } from '../context/AuthContext';
import { useNavigate } from 'react-router-dom';

const Login = () => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const { login } = useAuth();
  const navigate = useNavigate();

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    try {
      await login(email, password);
      navigate('/');
    } catch (err) {
      setError(err.response?.data?.message || 'Login failed');
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
                  <h3>Sign In</h3>
                  {error && <div className="alert alert-danger">{error}</div>}
                  <div className="form-group">
                    <label htmlFor="email">Email <span className="require">*</span></label>
                    <input
                      type="email"
                      id="email"
                      className="form-control"
                      value={email}
                      onChange={(e) => setEmail(e.target.value)}
                      required
                    />
                  </div>
                  <div className="form-group">
                    <label htmlFor="password">Password <span className="require">*</span></label>
                    <input
                      type="password"
                      id="password"
                      className="form-control"
                      value={password}
                      onChange={(e) => setPassword(e.target.value)}
                      required
                    />
                  </div>
                  <div className="row">
                    <div className="col-lg-8">
                      <label>
                        <input type="checkbox" /> Remember me
                      </label>
                    </div>
                    <div className="col-lg-4 text-right">
                      <button type="submit" className="btn btn-primary">Sign In</button>
                    </div>
                  </div>
                  <div className="text-center">
                    <a href="/register">Create Account</a>
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

export default Login;