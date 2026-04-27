import React, { useState } from 'react';
import Layout from './components/Layout';
import Slider from './components/Slider';
import HomeContent from './components/HomeContent';

function App() {
  const [swaps, setSwaps] = useState([]);

  const addToSwaps = (item) => {
    setSwaps([...swaps, { ...item, id: Date.now() }]);
    // Simple alert for feedback
    alert(`${item.name} added to your Swap Basket!`);
  };

  const removeFromSwaps = (id) => {
    setSwaps(swaps.filter(item => item.id !== id));
  };

  return (
    <Layout swaps={swaps} removeFromSwaps={removeFromSwaps}>
      <Slider addToSwaps={addToSwaps} />
      <div className="main">
        <div className="container">
          <HomeContent addToSwaps={addToSwaps} />
        </div>
      </div>
      
      {/* BEGIN BRANDS */}
      <div className="brands">
        <div className="container">
          <div className="owl-carousel owl-carousel6-brands">
            <a href="#"><img src="/assets/pages/img/brands/canon.jpg" alt="canon" title="canon" /></a>
            <a href="#"><img src="/assets/pages/img/brands/esprit.jpg" alt="esprit" title="esprit" /></a>
            <a href="#"><img src="/assets/pages/img/brands/gap.jpg" alt="gap" title="gap" /></a>
            <a href="#"><img src="/assets/pages/img/brands/next.jpg" alt="next" title="next" /></a>
            <a href="#"><img src="/assets/pages/img/brands/puma.jpg" alt="puma" title="puma" /></a>
            <a href="#"><img src="/assets/pages/img/brands/zara.jpg" alt="zara" title="zara" /></a>
            <a href="#"><img src="/assets/pages/img/brands/canon.jpg" alt="canon" title="canon" /></a>
            <a href="#"><img src="/assets/pages/img/brands/esprit.jpg" alt="esprit" title="esprit" /></a>
            <a href="#"><img src="/assets/pages/img/brands/gap.jpg" alt="gap" title="gap" /></a>
            <a href="#"><img src="/assets/pages/img/brands/next.jpg" alt="next" title="next" /></a>
            <a href="#"><img src="/assets/pages/img/brands/puma.jpg" alt="puma" title="puma" /></a>
            <a href="#"><img src="/assets/pages/img/brands/zara.jpg" alt="zara" title="zara" /></a>
          </div>
        </div>
      </div>
      {/* END BRANDS */}
    </Layout>
  );
}

export default App;
