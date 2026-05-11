import React, { useState } from 'react';
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import { AuthProvider } from './context/AuthContext';
import Layout from './components/Layout';
import ProtectedRoute from './components/ProtectedRoute';
import Home from './pages/Home';
import Products from './pages/Products';
import Login from './pages/Login';
import Register from './pages/Register';
import Account from './pages/Account';
import MyItems from './pages/MyItems';
import SwapBasket from './pages/SwapBasket';
import Orders from './pages/Orders';
import EcoCredits from './pages/EcoCredits';
import TrustScore from './pages/TrustScore';
import ActivityLog from './pages/ActivityLog';
import ShippingCalculator from './pages/ShippingCalculator';
import Analytics from './pages/Analytics';
import SwapHistory from './pages/SwapHistory';
import BundleDiscount from './pages/BundleDiscount';
import DigitalCloset from './pages/DigitalCloset';
import EcoImpact from './pages/EcoImpact';
import SwapProposal from './pages/SwapProposal';
import NearbySwaps from './pages/NearbySwaps';
import EscrowVault from './pages/EscrowVault';
import DisputeCenter from './pages/DisputeCenter';
import StyleBoards from './pages/StyleBoards';
import DropNotifications from './pages/DropNotifications';
import SellerAnalytics from './pages/SellerAnalytics';
import CommentsPage from './pages/CommentsPage';
import ReportsPage from './pages/ReportsPage';
import MentorshipPage from './pages/MentorshipPage';
import MarketTrendsPage from './pages/MarketTrendsPage';
import AdminDashboard from './pages/AdminDashboard';
import NewsletterPage from './pages/NewsletterPage';
import DatabaseCleanupPage from './pages/DatabaseCleanupPage';
import './App.css';

function App() {
  const [swaps, setSwaps] = useState([]);

  const addToSwaps = (item) => {
    setSwaps([...swaps, { ...item, id: item.id || Date.now() }]);
    alert(`${item.title || item.name} added to your Swap Basket!`);
  };

  const removeFromSwaps = (id) => {
    setSwaps(swaps.filter((item) => item.id !== id));
  };

  return (
    <AuthProvider>
      <BrowserRouter>
        <Layout swaps={swaps} removeFromSwaps={removeFromSwaps}>
          <Routes>
            <Route path="/" element={<Home addToSwaps={addToSwaps} />} />
            <Route path="/products" element={<Products addToSwaps={addToSwaps} />} />
            <Route path="/login" element={<Login />} />
            <Route path="/register" element={<Register />} />
            <Route path="/newsletter" element={<NewsletterPage />} />
            
            <Route path="/account" element={<ProtectedRoute><Account /></ProtectedRoute>} />
            <Route path="/my-items" element={<ProtectedRoute><MyItems /></ProtectedRoute>} />
            <Route path="/basket" element={<ProtectedRoute><SwapBasket swaps={swaps} removeFromSwaps={removeFromSwaps} /></ProtectedRoute>} />
            <Route path="/orders" element={<ProtectedRoute><Orders /></ProtectedRoute>} />
            <Route path="/eco-credits" element={<ProtectedRoute><EcoCredits /></ProtectedRoute>} />
            <Route path="/trust-score" element={<ProtectedRoute><TrustScore /></ProtectedRoute>} />
            <Route path="/activity-log" element={<ProtectedRoute><ActivityLog /></ProtectedRoute>} />
            <Route path="/shipping" element={<ProtectedRoute><ShippingCalculator /></ProtectedRoute>} />
            <Route path="/analytics" element={<ProtectedRoute><Analytics /></ProtectedRoute>} />
            <Route path="/swap-history" element={<ProtectedRoute><SwapHistory /></ProtectedRoute>} />
            <Route path="/bundle-discount" element={<ProtectedRoute><BundleDiscount /></ProtectedRoute>} />
            <Route path="/digital-closet" element={<ProtectedRoute><DigitalCloset /></ProtectedRoute>} />
            <Route path="/eco-impact" element={<ProtectedRoute><EcoImpact /></ProtectedRoute>} />
            <Route path="/swap-proposal" element={<ProtectedRoute><SwapProposal /></ProtectedRoute>} />
            <Route path="/nearby-swaps" element={<ProtectedRoute><NearbySwaps /></ProtectedRoute>} />
            <Route path="/escrow-vault" element={<ProtectedRoute><EscrowVault /></ProtectedRoute>} />
            <Route path="/dispute-center" element={<ProtectedRoute><DisputeCenter /></ProtectedRoute>} />
            <Route path="/style-boards" element={<ProtectedRoute><StyleBoards /></ProtectedRoute>} />
            <Route path="/drops" element={<ProtectedRoute><DropNotifications /></ProtectedRoute>} />
            <Route path="/comments" element={<ProtectedRoute><CommentsPage /></ProtectedRoute>} />
            <Route path="/mentorship" element={<ProtectedRoute><MentorshipPage /></ProtectedRoute>} />
            <Route path="/trends" element={<ProtectedRoute><MarketTrendsPage /></ProtectedRoute>} />

            <Route path="/seller-analytics" element={<ProtectedRoute allowedRoles={['seller', 'admin']}><SellerAnalytics /></ProtectedRoute>} />
            
            <Route path="/admin" element={<ProtectedRoute allowedRoles={['admin', 'super_admin', 'moderator']}><AdminDashboard /></ProtectedRoute>} />
            <Route path="/reports" element={<ProtectedRoute allowedRoles={['admin', 'super_admin', 'moderator']}><ReportsPage /></ProtectedRoute>} />
            <Route path="/cleanup" element={<ProtectedRoute allowedRoles={['super_admin']}><DatabaseCleanupPage /></ProtectedRoute>} />
          </Routes>
        </Layout>
      </BrowserRouter>
    </AuthProvider>
  );
}

export default App;
