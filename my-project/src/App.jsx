import React, { useState } from 'react';
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import { AuthProvider } from './context/AuthContext';
import Layout from './components/Layout';
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
            <Route path="/account" element={<Account />} />
            <Route path="/my-items" element={<MyItems />} />
            <Route path="/basket" element={<SwapBasket swaps={swaps} removeFromSwaps={removeFromSwaps} />} />
            <Route path="/orders" element={<Orders />} />
            <Route path="/eco-credits" element={<EcoCredits />} />
            <Route path="/trust-score" element={<TrustScore />} />
            <Route path="/activity-log" element={<ActivityLog />} />
            <Route path="/shipping" element={<ShippingCalculator />} />
            <Route path="/analytics" element={<Analytics />} />
            <Route path="/swap-history" element={<SwapHistory />} />
            <Route path="/bundle-discount" element={<BundleDiscount />} />
            <Route path="/digital-closet" element={<DigitalCloset />} />
            <Route path="/eco-impact" element={<EcoImpact />} />
            <Route path="/swap-proposal" element={<SwapProposal />} />
            <Route path="/nearby-swaps" element={<NearbySwaps />} />
            <Route path="/escrow-vault" element={<EscrowVault />} />
            <Route path="/dispute-center" element={<DisputeCenter />} />
            <Route path="/style-boards" element={<StyleBoards />} />
            <Route path="/drops" element={<DropNotifications />} />
            <Route path="/seller-analytics" element={<SellerAnalytics />} />
            <Route path="/comments" element={<CommentsPage />} />
            <Route path="/reports" element={<ReportsPage />} />
            <Route path="/mentorship" element={<MentorshipPage />} />
            <Route path="/trends" element={<MarketTrendsPage />} />
            <Route path="/admin" element={<AdminDashboard />} />
            <Route path="/newsletter" element={<NewsletterPage />} />
            <Route path="/cleanup" element={<DatabaseCleanupPage />} />
          </Routes>
        </Layout>
      </BrowserRouter>
    </AuthProvider>
  );
}

export default App;