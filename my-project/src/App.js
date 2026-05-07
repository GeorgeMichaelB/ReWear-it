import React, { useState } from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import { AuthProvider } from './context/AuthContext';
import Layout from './components/Layout';
import Slider from './components/Slider';
import HomeContent from './components/HomeContent';
import ProductList from './components/ProductList';
import Login from './pages/Login';
import Register from './pages/Register';
import Account from './pages/Account';

function App() {
  const [swaps, setSwaps] = useState([]);

  const addToSwaps = (item) => {
    setSwaps([...swaps, { ...item, id: item.id || Date.now() }]);
    alert(`${item.name} added to your Swap Basket!`);
  };

  const removeFromSwaps = (id) => {
    setSwaps(swaps.filter(item => item.id !== id));
  };

  return (
    <AuthProvider>
      <Router>
        <Layout swaps={swaps} removeFromSwaps={removeFromSwaps}>
          <Routes>
            <Route path="/" element={
              <>
                <Slider addToSwaps={addToSwaps} />
                <div className="main">
                  <div className="container">
                    <HomeContent addToSwaps={addToSwaps} />
                  </div>
                </div>
                <div className="brands">
                  <div className="container">
                    <div className="owl-carousel owl-carousel6-brands">
                      <a href="#"><img src="/assets/pages/img/brands/canon.jpg" alt="canon" title="canon" /></a>
                      <a href="#"><img src="/assets/pages/img/brands/esprit.jpg" alt="esprit" title="esprit" /></a>
                      <a href="#"><img src="/assets/pages/img/brands/gap.jpg" alt="gap" title="gap" /></a>
                      <a href="#"><img src="/assets/pages/img/brands/next.jpg" alt="next" title="next" /></a>
                      <a href="#"><img src="/assets/pages/img/brands/puma.jpg" alt="puma" title="puma" /></a>
                      <a href="#"><img src="/assets/pages/img/brands/zara.jpg" alt="zara" title="zara" /></a>
                    </div>
                  </div>
                </div>
              </>
            } />
            <Route path="/products" element={
              <div className="main">
                <div className="container">
                  <div className="row margin-bottom-40">
                    <ProductList addToSwaps={addToSwaps} />
                  </div>
                </div>
              </div>
            } />
            <Route path="/login" element={<Login />} />
            <Route path="/register" element={<Register />} />
            <Route path="/account" element={<Account />} />
          </Routes>
        </Layout>
      </Router>
    </AuthProvider>
  );
}

export default App;