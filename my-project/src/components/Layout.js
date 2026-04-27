import React, { useEffect } from 'react';
import Header from './Header';
import Footer from './Footer';

const Layout = ({ children, swaps, removeFromSwaps }) => {
  useEffect(() => {
    // Initialize theme layout after component mounts
    if (window.Layout) {
      window.Layout.init();
      window.Layout.initOWL();
      window.Layout.initImageZoom();
      window.Layout.initTouchspin();
      window.Layout.initTwitter();
    }
  }, []);

  return (
    <>
      <Header swaps={swaps} removeFromSwaps={removeFromSwaps} />
      {children}
      <Footer />
    </>
  );
};

export default Layout;
