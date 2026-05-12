import React, { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import './Layout.css';

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
    ],
    "Account": [
      { name: "My Account", path: "/account" },
      { name: "Analytics", path: "/analytics" },
      { name: "Seller Stats", path: "/seller-analytics", roles: ['seller', 'admin'] },
      { name: "Mentorship", path: "/mentorship" },
    ],
    "Support": [
      { name: "Dispute Center", path: "/dispute-center" },
      { name: "Escrow Vault", path: "/escrow-vault" },
      { name: "Newsletter", path: "/newsletter" },
    ],
    "Administration": [
      { name: "Admin Dashboard", path: "/admin", roles: ['admin', 'super_admin', 'moderator'] },
      { name: "User Reports", path: "/reports", roles: ['admin', 'super_admin', 'moderator'] },
      { name: "DB Cleanup", path: "/cleanup", roles: ['super_admin'] },
    ]
  };

  return (
    <div className="app-layout">
      <header className="app-header">
        <div className="header-content">
          <Link to="/" className="logo">
            <h1>ReWear it</h1>
          </Link>

          <button 
            className="mobile-menu-toggle"
            onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
            aria-label="Toggle menu"
          >
            <span className={`hamburger ${mobileMenuOpen ? 'open' : ''}`}>
              <span></span>
              <span></span>
              <span></span>
            </span>
          </button>

            <nav className={`main-nav ${mobileMenuOpen ? 'mobile-open' : ''}`}>
              {user && <Link to="/" onClick={() => setMobileMenuOpen(false)}>Home</Link>}
              
              {Object.entries(navGroups).map(([groupName, links]) => {
                const visibleLinks = links.filter(link => !link.roles || (user && link.roles.includes(user.role)));
                if (visibleLinks.length === 0) return null;

                return (
                  <div className="nav-dropdown" key={groupName}>
                    <span className="dropdown-trigger">{groupName}</span>
                    <div className="dropdown-menu">
                      {visibleLinks.map(link => {
                        const isExplore = groupName === "Explore";
                        return (
                          <Link 
                            key={link.path} 
                            to={(user || isExplore) ? link.path : '/login'} 
                            onClick={() => setMobileMenuOpen(false)}
                          >
                            {link.name}
                          </Link>
                        );
                      })}
                    </div>
                  </div>
                );
              })}
            </nav>

          <div className="header-right">
            <div className="swap-basket">
              <Link to="/basket">
                <span>🔄 Swap Basket ({swaps.length})</span>
              </Link>
            </div>

            {user ? (
              <div className="user-menu">
                <span className="user-name">{user.name}</span>
                <Link to="/account">Account</Link>
                <button onClick={handleLogout}>Logout</button>
              </div>
            ) : (
              <div className="auth-links">
                <Link to="/login">Login</Link>
                <Link to="/register">Register</Link>
              </div>
            )}
          </div>
        </div>
      </header>

      <main className="main-content">
        {children}
      </main>

      <footer className="app-footer">
        <p>&copy; 2026 ReWear-it - Sustainable Fashion Marketplace</p>
      </footer>
    </div>
  );
};

export default Layout;


