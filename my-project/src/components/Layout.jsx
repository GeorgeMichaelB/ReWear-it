import React, { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';

const Layout = ({ children, swaps, removeFromSwaps }) => {
  const { user, logout } = useAuth();
  const navigate = useNavigate();
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);

  const handleLogout = () => {
    logout();
    navigate('/');
    setMobileMenuOpen(false);
  };

  const navGroups = {
    "Explore": [
      { name: "Browse Items", path: "/products" },
      { name: "Nearby Swaps", path: "/nearby-swaps" },
      { name: "Market Trends", path: "/trends" },
      { name: "Drops", path: "/drops" },
    ],
    "My Space": [
      { name: "My Items", path: "/my-items" },
      { name: "Digital Closet", path: "/digital-closet" },
      { name: "Orders", path: "/orders" },
      { name: "Swap History", path: "/swap-history" },
    ],
    "Impact & Tools": [
      { name: "Eco Impact", path: "/eco-impact" },
      { name: "Eco Credits", path: "/eco-credits" },
      { name: "Trust Score", path: "/trust-score" },
      { name: "Shipping Calc", path: "/shipping" },
    ]
  };

  return (
    <div style={{ display: 'flex', flexDirection: 'column', minHeight: '100vh' }}>
      {/* Modern Premium Header */}
      <header style={{ 
        backgroundColor: 'rgba(255, 255, 255, 0.95)',
        backdropFilter: 'blur(10px)',
        borderBottom: '1px solid var(--color-border)',
        padding: 'var(--space-sm) 0',
        position: 'sticky',
        top: 0,
        zIndex: 1000
      }}>
        <div className="container-custom" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
          <Link to="/" style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
            <span style={{ fontSize: '1.5rem', fontWeight: 800, color: 'var(--color-primary)', letterSpacing: '-1px' }}>REWIT.</span>
          </Link>

          {/* Desktop Navigation */}
          <nav style={{ display: 'flex', gap: 'var(--space-md)', alignItems: 'center' }}>
            <Link to="/" style={{ fontWeight: 500, fontSize: '0.9rem' }}>Home</Link>
            <Link to="/products" style={{ fontWeight: 500, fontSize: '0.9rem' }}>Shop</Link>
            
            {user && (
              <div style={{ position: 'relative', cursor: 'pointer' }} className="nav-group-trigger">
                <span style={{ fontWeight: 500, fontSize: '0.9rem' }}>Dashboard ▾</span>
                {/* Simplified dropdown for brevity in this modernized layout */}
              </div>
            )}
          </nav>

          <div style={{ display: 'flex', alignItems: 'center', gap: 'var(--space-md)' }}>
            <Link to="/basket" style={{ position: 'relative', padding: '8px' }}>
              <span style={{ fontSize: '1.2rem' }}>🔄</span>
              {swaps.length > 0 && (
                <span style={{ 
                  position: 'absolute', 
                  top: 0, 
                  right: 0, 
                  backgroundColor: 'var(--color-secondary)', 
                  color: 'white', 
                  fontSize: '0.65rem', 
                  padding: '2px 6px', 
                  borderRadius: '10px',
                  fontWeight: 700
                }}>
                  {swaps.length}
                </span>
              )}
            </Link>

            {user ? (
              <div style={{ display: 'flex', alignItems: 'center', gap: 'var(--space-sm)' }}>
                <Link to="/account" style={{ fontSize: '0.9rem', fontWeight: 600 }}>{user.name.split(' ')[0]}</Link>
                <button onClick={handleLogout} style={{ background: 'none', border: 'none', color: 'var(--color-text-muted)', fontSize: '0.85rem', cursor: 'pointer' }}>Logout</button>
              </div>
            ) : (
              <div style={{ display: 'flex', gap: 'var(--space-sm)' }}>
                <Link to="/login" style={{ fontSize: '0.85rem', fontWeight: 600 }}>Login</Link>
                <Link to="/register" className="btn-premium" style={{ padding: '0.5rem 1.2rem', fontSize: '0.85rem' }}>Join</Link>
              </div>
            )}
          </div>
        </div>
      </header>

      {/* Main Content Area */}
      <main style={{ flex: 1, padding: 'var(--space-lg) 0' }}>
        <div className="container-custom">
          {children}
        </div>
      </main>

      {/* Modern Minimal Footer */}
      <footer style={{ 
        backgroundColor: 'var(--color-primary)', 
        color: 'white', 
        padding: 'var(--space-xl) 0 var(--space-lg)',
        marginTop: 'var(--space-xl)'
      }}>
        <div className="container-custom">
          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(200px, 1fr))', gap: 'var(--space-lg)', marginBottom: 'var(--space-xl)' }}>
            <div>
              <h3 style={{ color: 'white', fontSize: '1.5rem', marginBottom: 'var(--space-sm)' }}>REWIT.</h3>
              <p style={{ color: 'var(--color-accent)', fontSize: '0.9rem' }}>The premium destination for circular high-fashion.</p>
            </div>
            <div>
              <h4 style={{ color: 'white', marginBottom: 'var(--space-sm)' }}>Marketplace</h4>
              <ul style={{ listStyle: 'none', display: 'flex', flexDirection: 'column', gap: '8px', fontSize: '0.9rem', opacity: 0.8 }}>
                <li><Link to="/products">All Products</Link></li>
                <li><Link to="/trends">Market Trends</Link></li>
                <li><Link to="/nearby">Nearby Swaps</Link></li>
              </ul>
            </div>
            <div>
              <h4 style={{ color: 'white', marginBottom: 'var(--space-sm)' }}>Company</h4>
              <ul style={{ listStyle: 'none', display: 'flex', flexDirection: 'column', gap: '8px', fontSize: '0.9rem', opacity: 0.8 }}>
                <li><Link to="/about">Our Story</Link></li>
                <li><Link to="/impact">Sustainability</Link></li>
                <li><Link to="/contact">Contact</Link></li>
              </ul>
            </div>
            <div>
              <h4 style={{ color: 'white', marginBottom: 'var(--space-sm)' }}>Newsletter</h4>
              <div style={{ display: 'flex', gap: '8px' }}>
                <input type="email" placeholder="email@example.com" style={{ 
                  padding: '0.5rem 1rem', 
                  borderRadius: 'var(--radius-sm)', 
                  border: 'none', 
                  flex: 1 
                }} />
                <button className="btn-premium" style={{ padding: '0.5rem 1rem' }}>Join</button>
              </div>
            </div>
          </div>
          <div style={{ borderTop: '1px solid rgba(255,255,255,0.1)', paddingTop: 'var(--space-md)', textAlign: 'center', fontSize: '0.8rem', opacity: 0.6 }}>
            &copy; {new Date().getFullYear()} ReWear-it. Built for a better future.
          </div>
        </div>
      </footer>
    </div>
  );
};

export default Layout;
