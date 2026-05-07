import React from 'react';
import { useAuth } from '../context/AuthContext';
import { Link, useNavigate } from 'react-router-dom';

const Header = ({ swaps, removeFromSwaps }) => {
  const { user, logout } = useAuth();
  const navigate = useNavigate();

  const handleLogout = () => {
    logout();
    navigate('/');
  };

  return (
    <>
      {/* BEGIN TOP BAR */}
      <div className="pre-header">
        <div className="container">
          <div className="row">
            {/* BEGIN TOP BAR LEFT PART */}
            <div className="col-md-6 col-sm-6 additional-shop-info">
              <ul className="list-unstyled list-inline">
                <li><i className="fa fa-phone"></i><span>+20 123 456 7890</span></li>
                {/* BEGIN SWITCH INFO */}
                <li className="shop-currencies">
                  <span className="current">SWAP MODE</span>
                </li>
                {/* END SWITCH INFO */}
                {/* BEGIN LANGS */}
                <li className="langs-block">
                  <a href="#" className="current">English </a>
                  <div className="langs-block-others-wrapper"><div className="langs-block-others">
                    <a href="#">Arabic</a>
                  </div></div>
                </li>
                {/* END LANGS */}
              </ul>
            </div>
            {/* END TOP BAR LEFT PART */}
            {/* BEGIN TOP BAR MENU */}
            <div className="col-md-6 col-sm-6 additional-nav">
              <ul className="list-unstyled list-inline pull-right">
                {user ? (
                  <>
                    <li><span className="text-success">Welcome, {user.name}!</span></li>
                    <li><Link to="/account">My Profile</Link></li>
                    <li><Link to="/products">Saved Items</Link></li>
                    <li><Link to="/products">Confirm Swaps</Link></li>
                    <li><a href="#" onClick={handleLogout}>Log Out</a></li>
                  </>
                ) : (
                  <>
                    <li><Link to="/login">Log In</Link></li>
                    <li><Link to="/register">Sign Up</Link></li>
                  </>
                )}
              </ul>
            </div>
            {/* END TOP BAR MENU */}
          </div>
        </div>
      </div>
      {/* END TOP BAR */}

      {/* BEGIN HEADER */}
      <div className="header">
        <div className="container">
          <a className="site-logo" href="/"><img src="/assets/corporate/img/logos/logo-shop-red.png" alt="ReWear-it Swap" /></a>

          <a href="#" className="mobi-toggler"><i className="fa fa-bars"></i></a>

          {/* BEGIN SWAP BASKET */}
          <div className="top-cart-block">
            <div className="top-cart-info">
              <a href="#" className="top-cart-info-count">{swaps.length} items</a>
              <a href="#" className="top-cart-info-value">To Swap</a>
            </div>
            <i className="fa fa-refresh"></i>

            <div className="top-cart-content-wrapper">
              <div className="top-cart-content">
                <ul className="scroller" style={{ height: '250px' }}>
                  {swaps.length === 0 ? (
                    <li style={{ padding: '20px', textAlign: 'center' }}>Your basket is empty</li>
                  ) : (
                    swaps.map((item) => (
                      <li key={item.id}>
                        <a href="#"><img src={item.img} alt={item.name} width="37" height="34" /></a>
                        <span className="cart-content-count">x 1</span>
                        <strong><a href="#">{item.name}</a></strong>
                        <em>{item.value}</em>
                        <a
                          href="#"
                          className="del-goods"
                          onClick={() => removeFromSwaps(item.id)}
                        >&nbsp;</a>
                      </li>
                    ))
                  )}
                </ul>
                <div className="text-right">
                  <a href="/shop-shopping-cart.html" className="btn btn-default">View Swaps</a>
                  <a href="/shop-checkout.html" className="btn btn-primary">Exchange Now</a>
                </div>
              </div>
            </div>
          </div>
          {/* END SWAP BASKET */}

          {/* BEGIN NAVIGATION */}
          <div className="header-navigation">
            <ul>
              <li><Link to="/products">Browse Items</Link></li>
              <li className="dropdown">
                <a className="dropdown-toggle" data-toggle="dropdown" data-target="#" href="#">
                  Woman
                </a>

                {/* BEGIN DROPDOWN MENU */}
                <ul className="dropdown-menu">
                  <li className="dropdown-submenu">
                    <a href="/shop-product-list.html">Hi Tops <i className="fa fa-angle-right"></i></a>
                    <ul className="dropdown-menu" role="menu">
                      <li><a href="/shop-product-list.html">Second Level Link</a></li>
                      <li><a href="/shop-product-list.html">Second Level Link</a></li>
                      <li className="dropdown-submenu">
                        <a className="dropdown-toggle" data-toggle="dropdown" data-target="#" href="#">
                          Second Level Link
                          <i className="fa fa-angle-right"></i>
                        </a>
                        <ul className="dropdown-menu">
                          <li><a href="/shop-product-list.html">Third Level Link</a></li>
                          <li><a href="/shop-product-list.html">Third Level Link</a></li>
                          <li><a href="/shop-product-list.html">Third Level Link</a></li>
                        </ul>
                      </li>
                    </ul>
                  </li>
                  <li><a href="/shop-product-list.html">Running Shoes</a></li>
                  <li><a href="/shop-product-list.html">Jackets and Coats</a></li>
                </ul>
                {/* END DROPDOWN MENU */}
              </li>
              <li className="dropdown dropdown-megamenu">
                <a className="dropdown-toggle" data-toggle="dropdown" data-target="#" href="#">
                  Man
                </a>
                <ul className="dropdown-menu">
                  <li>
                    <div className="header-navigation-content">
                      <div className="row">
                        <div className="col-md-4 header-navigation-col">
                          <h4>Footwear</h4>
                          <ul>
                            <li><a href="/shop-product-list.html">Astro Trainers</a></li>
                            <li><a href="/shop-product-list.html">Basketball Shoes</a></li>
                            <li><a href="/shop-product-list.html">Boots</a></li>
                            <li><a href="/shop-product-list.html">Canvas Shoes</a></li>
                            <li><a href="/shop-product-list.html">Football Boots</a></li>
                            <li><a href="/shop-product-list.html">Golf Shoes</a></li>
                            <li><a href="/shop-product-list.html">Hi Tops</a></li>
                            <li><a href="/shop-product-list.html">Indoor and Court Trainers</a></li>
                          </ul>
                        </div>
                        <div className="col-md-4 header-navigation-col">
                          <h4>Clothing</h4>
                          <ul>
                            <li><a href="/shop-product-list.html">Base Layer</a></li>
                            <li><a href="/shop-product-list.html">Character</a></li>
                            <li><a href="/shop-product-list.html">Chinos</a></li>
                            <li><a href="/shop-product-list.html">Combats</a></li>
                            <li><a href="/shop-product-list.html">Cricket Clothing</a></li>
                            <li><a href="/shop-product-list.html">Fleeces</a></li>
                            <li><a href="/shop-product-list.html">Gilets</a></li>
                            <li><a href="/shop-product-list.html">Golf Tops</a></li>
                          </ul>
                        </div>
                        <div className="col-md-4 header-navigation-col">
                          <h4>Accessories</h4>
                          <ul>
                            <li><a href="/shop-product-list.html">Belts</a></li>
                            <li><a href="/shop-product-list.html">Caps</a></li>
                            <li><a href="/shop-product-list.html">Gloves, Hats and Scarves</a></li>
                          </ul>

                          <h4>Clearance</h4>
                          <ul>
                            <li><a href="/shop-product-list.html">Jackets</a></li>
                            <li><a href="/shop-product-list.html">Bottoms</a></li>
                          </ul>
                        </div>
                        <div className="col-md-12 nav-brands">
                          <ul>
                            <li><a href="/shop-product-list.html"><img title="esprit" alt="esprit" src="/assets/pages/img/brands/esprit.jpg" /></a></li>
                            <li><a href="/shop-product-list.html"><img title="gap" alt="gap" src="/assets/pages/img/brands/gap.jpg" /></a></li>
                            <li><a href="/shop-product-list.html"><img title="next" alt="next" src="/assets/pages/img/brands/next.jpg" /></a></li>
                            <li><a href="/shop-product-list.html"><img title="puma" alt="puma" src="/assets/pages/img/brands/puma.jpg" /></a></li>
                            <li><a href="/shop-product-list.html"><img title="zara" alt="zara" src="/assets/pages/img/brands/zara.jpg" /></a></li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </li>
                </ul>
              </li>
              <li><a href="/shop-product-list.html">Kids</a></li>
              <li className="dropdown dropdown100 nav-catalogue">
                <a className="dropdown-toggle" data-toggle="dropdown" data-target="#" href="#">
                  New
                </a>
                <ul className="dropdown-menu">
                  <li>
                    <div className="header-navigation-content">
                      <div className="row">
                        <div className="col-md-3 col-sm-4 col-xs-6">
                          <div className="product-item">
                            <div className="pi-img-wrapper">
                              <a href="/shop-item.html"><img src="/assets/pages/img/products/model4.jpg" className="img-responsive" alt="Berry Lace Dress" /></a>
                            </div>
                            <h3><a href="/shop-item.html">Berry Lace Dress</a></h3>
                            <div className="pi-price">Swap Value: ★★★★☆</div>
                            <a href="/shop-item.html" className="btn btn-default add2cart">Switch Now</a>
                          </div>
                        </div>
                        <div className="col-md-3 col-sm-4 col-xs-6">
                          <div className="product-item">
                            <div className="pi-img-wrapper">
                              <a href="/shop-item.html"><img src="/assets/pages/img/products/model3.jpg" className="img-responsive" alt="Berry Lace Dress" /></a>
                            </div>
                            <h3><a href="/shop-item.html">Berry Lace Dress</a></h3>
                            <div className="pi-price">Swap Value: ★★★★☆</div>
                            <a href="/shop-item.html" className="btn btn-default add2cart">Switch Now</a>
                          </div>
                        </div>
                        <div className="col-md-3 col-sm-4 col-xs-6">
                          <div className="product-item">
                            <div className="pi-img-wrapper">
                              <a href="/shop-item.html"><img src="/assets/pages/img/products/model7.jpg" className="img-responsive" alt="Berry Lace Dress" /></a>
                            </div>
                            <h3><a href="/shop-item.html">Berry Lace Dress</a></h3>
                            <div className="pi-price">Swap Value: ★★★★☆</div>
                            <a href="/shop-item.html" className="btn btn-default add2cart">Switch Now</a>
                          </div>
                        </div>
                        <div className="col-md-3 col-sm-4 col-xs-6">
                          <div className="product-item">
                            <div className="pi-img-wrapper">
                              <a href="/shop-item.html"><img src="/assets/pages/img/products/model4.jpg" className="img-responsive" alt="Berry Lace Dress" /></a>
                            </div>
                            <h3><a href="/shop-item.html">Berry Lace Dress</a></h3>
                            <div className="pi-price">Swap Value: ★★★★☆</div>
                            <a href="/shop-item.html" className="btn btn-default add2cart">Switch Now</a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </li>
                </ul>
              </li>
              <li className="dropdown">
                <a className="dropdown-toggle" data-toggle="dropdown" data-target="#" href="#">
                  Pages
                </a>

                <ul className="dropdown-menu">
                  <li className="active"><a href="/">Home</a></li>
                  <li><a href="/shop-product-list.html">Product List</a></li>
                  <li><a href="/shop-search-result.html">Search Result</a></li>
                  <li><a href="/shop-item.html">Product Page</a></li>
                  <li><a href="/shop-shopping-cart-null.html">My Swaps (Empty)</a></li>
                  <li><a href="/shop-shopping-cart.html">My Swap Basket</a></li>
                  <li><a href="/shop-checkout.html">Confirm Swaps</a></li>
                  <li><a href="/shop-about.html">About</a></li>
                  <li><a href="/shop-contacts.html">Contacts</a></li>
                  <li><a href="/shop-account.html">My Account</a></li>
                  <li><a href="/shop-wishlist.html">My Wish List</a></li>
                  <li><a href="/shop-goods-compare.html">Product Comparison</a></li>
                  <li><a href="/shop-standart-forms.html">Standard Forms</a></li>
                  <li><a href="/shop-faq.html">FAQ</a></li>
                  <li><a href="/shop-privacy-policy.html">Privacy Policy</a></li>
                  <li><a href="/shop-terms-conditions-page.html">Terms &amp; Conditions</a></li>
                </ul>
              </li>

              {/* BEGIN TOP SEARCH */}
              <li className="menu-search">
                <span className="sep"></span>
                <i className="fa fa-search search-btn"></i>
                <div className="search-box">
                  <form action="#" onSubmit={(e) => { e.preventDefault(); alert(`Searching for: ${e.target.search.value}`); }}>
                    <div className="input-group">
                      <input type="text" name="search" placeholder="Search for items to swap..." className="form-control" />
                      <span className="input-group-btn">
                        <button className="btn btn-primary" type="submit">Search</button>
                      </span>
                    </div>
                  </form>
                </div>
              </li>
              {/* END TOP SEARCH */}
            </ul>
          </div>
          {/* END NAVIGATION */}
        </div>
      </div>
      {/* END HEADER */}
    </>
  );
};

export default Header;
