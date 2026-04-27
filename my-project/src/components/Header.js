import React from 'react';

const Header = ({ swaps, removeFromSwaps }) => {
  return (
    <>
      {/* BEGIN TOP BAR */}
      <div className="pre-header">
          <div className="container">
              <div className="row">
                  {/* BEGIN TOP BAR LEFT PART */}
                  <div className="col-md-6 col-sm-6 additional-shop-info">
                      <ul className="list-unstyled list-inline">
                          <li><i className="fa fa-phone"></i><span>+1 456 6717</span></li>
                          {/* BEGIN SWITCH INFO */}
                          <li className="shop-currencies">
                              <span className="current">SWAP MODE</span>
                          </li>
                          {/* END SWITCH INFO */}
                          {/* BEGIN LANGS */}
                          <li className="langs-block">
                              <a href="javascript:void(0);" className="current">English </a>
                              <div className="langs-block-others-wrapper"><div className="langs-block-others">
                                <a href="javascript:void(0);">French</a>
                                <a href="javascript:void(0);">Germany</a>
                                <a href="javascript:void(0);">Turkish</a>
                              </div></div>
                          </li>
                          {/* END LANGS */}
                      </ul>
                  </div>
                  {/* END TOP BAR LEFT PART */}
                  {/* BEGIN TOP BAR MENU */}
                  <div className="col-md-6 col-sm-6 additional-nav">
                      <ul className="list-unstyled list-inline pull-right">
                          <li><a href="#">My Profile</a></li>
                          <li><a href="#">Saved Items</a></li>
                          <li><a href="#">Confirm Swaps</a></li>
                          <li><a href="#">Log In</a></li>
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

          <a href="javascript:void(0);" className="mobi-toggler"><i className="fa fa-bars"></i></a>

          {/* BEGIN SWAP BASKET */}
          <div className="top-cart-block">
            <div className="top-cart-info">
              <a href="javascript:void(0);" className="top-cart-info-count">{swaps.length} items</a>
              <a href="javascript:void(0);" className="top-cart-info-value">To Swap</a>
            </div>
            <i className="fa fa-refresh"></i>
                          
            <div className="top-cart-content-wrapper">
              <div className="top-cart-content">
                <ul className="scroller" style={{height: '250px'}}>
                  {swaps.length === 0 ? (
                    <li style={{padding: '20px', textAlign: 'center'}}>Your basket is empty</li>
                  ) : (
                    swaps.map((item) => (
                      <li key={item.id}>
                        <a href="#"><img src={item.img} alt={item.name} width="37" height="34" /></a>
                        <span className="cart-content-count">x 1</span>
                        <strong><a href="#">{item.name}</a></strong>
                        <em>{item.value}</em>
                        <a 
                          href="javascript:void(0);" 
                          className="del-goods" 
                          onClick={() => removeFromSwaps(item.id)}
                        >&nbsp;</a>
                      </li>
                    ))
                  )}
                </ul>
                <div className="text-right">
                  <a href="javascript:;" className="btn btn-default" onClick={() => alert(`You have ${swaps.length} items in your basket.`)}>View Swaps</a>
                  <a href="javascript:;" className="btn btn-primary" onClick={() => {
                    if (swaps.length > 0) {
                      alert('Redirecting to secure exchange gateway...');
                    } else {
                      alert('Your basket is empty. Add some items first!');
                    }
                  }}>Exchange Now</a>
                </div>
              </div>
            </div>            
          </div>
          {/* END SWAP BASKET */}

          {/* BEGIN NAVIGATION */}
          <div className="header-navigation">
            <ul>
              <li className="dropdown">
                <a className="dropdown-toggle" data-toggle="dropdown" data-target="#" href="javascript:;">
                  Woman 
                </a>
                  
                {/* BEGIN DROPDOWN MENU */}
                <ul className="dropdown-menu">
                  <li className="dropdown-submenu">
                    <a href="#">Hi Tops <i className="fa fa-angle-right"></i></a>
                    <ul className="dropdown-menu" role="menu">
                      <li><a href="#">Second Level Link</a></li>
                      <li><a href="#">Second Level Link</a></li>
                      <li className="dropdown-submenu">
                        <a className="dropdown-toggle" data-toggle="dropdown" data-target="#" href="javascript:;">
                          Second Level Link 
                          <i className="fa fa-angle-right"></i>
                        </a>
                        <ul className="dropdown-menu">
                          <li><a href="#">Third Level Link</a></li>
                          <li><a href="#">Third Level Link</a></li>
                          <li><a href="#">Third Level Link</a></li>
                        </ul>
                      </li>
                    </ul>
                  </li>
                  <li><a href="#">Running Shoes</a></li>
                  <li><a href="#">Jackets and Coats</a></li>
                </ul>
                {/* END DROPDOWN MENU */}
              </li>
              <li className="dropdown dropdown-megamenu">
                <a className="dropdown-toggle" data-toggle="dropdown" data-target="#" href="javascript:;">
                  Man
                </a>
                <ul className="dropdown-menu">
                  <li>
                    <div className="header-navigation-content">
                      <div className="row">
                        <div className="col-md-4 header-navigation-col">
                          <h4>Footwear</h4>
                          <ul>
                            <li><a href="#">Astro Trainers</a></li>
                            <li><a href="#">Basketball Shoes</a></li>
                            <li><a href="#">Boots</a></li>
                            <li><a href="#">Canvas Shoes</a></li>
                            <li><a href="#">Football Boots</a></li>
                            <li><a href="#">Golf Shoes</a></li>
                            <li><a href="#">Hi Tops</a></li>
                            <li><a href="#">Indoor and Court Trainers</a></li>
                          </ul>
                        </div>
                        <div className="col-md-4 header-navigation-col">
                          <h4>Clothing</h4>
                          <ul>
                            <li><a href="#">Base Layer</a></li>
                            <li><a href="#">Character</a></li>
                            <li><a href="#">Chinos</a></li>
                            <li><a href="#">Combats</a></li>
                            <li><a href="#">Cricket Clothing</a></li>
                            <li><a href="#">Fleeces</a></li>
                            <li><a href="#">Gilets</a></li>
                            <li><a href="#">Golf Tops</a></li>
                          </ul>
                        </div>
                        <div className="col-md-4 header-navigation-col">
                          <h4>Accessories</h4>
                          <ul>
                            <li><a href="#">Belts</a></li>
                            <li><a href="#">Caps</a></li>
                            <li><a href="#">Gloves, Hats and Scarves</a></li>
                          </ul>

                          <h4>Clearance</h4>
                          <ul>
                            <li><a href="#">Jackets</a></li>
                            <li><a href="#">Bottoms</a></li>
                          </ul>
                        </div>
                        <div className="col-md-12 nav-brands">
                          <ul>
                            <li><a href="#"><img title="esprit" alt="esprit" src="/assets/pages/img/brands/esprit.jpg" /></a></li>
                            <li><a href="#"><img title="gap" alt="gap" src="/assets/pages/img/brands/gap.jpg" /></a></li>
                            <li><a href="#"><img title="next" alt="next" src="/assets/pages/img/brands/next.jpg" /></a></li>
                            <li><a href="#"><img title="puma" alt="puma" src="/assets/pages/img/brands/puma.jpg" /></a></li>
                            <li><a href="#"><img title="zara" alt="zara" src="/assets/pages/img/brands/zara.jpg" /></a></li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </li>
                </ul>
              </li>
              <li><a href="#">Kids</a></li>
              <li className="dropdown dropdown100 nav-catalogue">
                <a className="dropdown-toggle" data-toggle="dropdown" data-target="#" href="javascript:;">
                  New
                </a>
                <ul className="dropdown-menu">
                  <li>
                    <div className="header-navigation-content">
                      <div className="row">
                        <div className="col-md-3 col-sm-4 col-xs-6">
                          <div className="product-item">
                            <div className="pi-img-wrapper">
                              <a href="#"><img src="/assets/pages/img/products/model4.jpg" className="img-responsive" alt="Berry Lace Dress" /></a>
                            </div>
                            <h3><a href="#">Berry Lace Dress</a></h3>
                            <div className="pi-price">Swap Value: ★★★★☆</div>
                            <a href="javascript:;" className="btn btn-default add2cart">Switch Now</a>
                          </div>
                        </div>
                        <div className="col-md-3 col-sm-4 col-xs-6">
                          <div className="product-item">
                            <div className="pi-img-wrapper">
                              <a href="#"><img src="/assets/pages/img/products/model3.jpg" className="img-responsive" alt="Berry Lace Dress" /></a>
                            </div>
                            <h3><a href="#">Berry Lace Dress</a></h3>
                            <div className="pi-price">Swap Value: ★★★★☆</div>
                            <a href="javascript:;" className="btn btn-default add2cart">Switch Now</a>
                          </div>
                        </div>
                        <div className="col-md-3 col-sm-4 col-xs-6">
                          <div className="product-item">
                            <div className="pi-img-wrapper">
                              <a href="#"><img src="/assets/pages/img/products/model7.jpg" className="img-responsive" alt="Berry Lace Dress" /></a>
                            </div>
                            <h3><a href="#">Berry Lace Dress</a></h3>
                            <div className="pi-price">Swap Value: ★★★★☆</div>
                            <a href="javascript:;" className="btn btn-default add2cart">Switch Now</a>
                          </div>
                        </div>
                        <div className="col-md-3 col-sm-4 col-xs-6">
                          <div className="product-item">
                            <div className="pi-img-wrapper">
                              <a href="#"><img src="/assets/pages/img/products/model4.jpg" className="img-responsive" alt="Berry Lace Dress" /></a>
                            </div>
                            <h3><a href="#">Berry Lace Dress</a></h3>
                            <div className="pi-price">Swap Value: ★★★★☆</div>
                            <a href="javascript:;" className="btn btn-default add2cart">Switch Now</a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </li>
                </ul>
              </li>
              <li className="dropdown">
                <a className="dropdown-toggle" data-toggle="dropdown" data-target="#" href="javascript:;">
                  Pages 
                </a>
                  
                <ul className="dropdown-menu">
                  <li className="active"><a href="/">Home Default</a></li>
                  <li><a href="#">Product List</a></li>
                  <li><a href="#">Search Result</a></li>
                  <li><a href="#">Product Page</a></li>
                  <li><a href="#">My Swaps (Empty)</a></li>
                  <li><a href="#">My Swap Basket</a></li>
                  <li><a href="#">Confirm Swaps</a></li>
                  <li><a href="#">About</a></li>
                  <li><a href="#">Contacts</a></li>
                  <li><a href="#">My account</a></li>
                  <li><a href="#">My Wish List</a></li>
                  <li><a href="#">Product Comparison</a></li>
                  <li><a href="#">Standart Forms</a></li>
                  <li><a href="#">FAQ</a></li>
                  <li><a href="#">Privacy Policy</a></li>
                  <li><a href="#">Terms &amp; Conditions</a></li>
                </ul>
              </li>
              
              <li><a href="http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes&amp;utm_source=download&amp;utm_medium=banner&amp;utm_campaign=metronic_frontend_freebie" target="_blank" rel="noreferrer">Admin theme</a></li>

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
