import React from 'react';

const HomeContent = ({ addToSwaps }) => {
  return (
    <>
      {/* BEGIN SALE PRODUCT & NEW ARRIVALS */}
      <div className="row margin-bottom-40">
        {/* BEGIN SALE PRODUCT */}
        <div className="col-md-12 sale-product">
          <h2>Fresh Swaps</h2>
          <div className="owl-carousel owl-carousel5">
            <div>
              <div className="product-item">
                <div className="pi-img-wrapper">
                  <img src="/assets/pages/img/products/model1.jpg" className="img-responsive" alt="Berry Lace Dress" />
                  <div>
                    <a href="/assets/pages/img/products/model1.jpg" className="btn btn-default fancybox-button">Zoom</a>
                    <a href="#product-pop-up" className="btn btn-default fancybox-fast-view">View</a>
                  </div>
                </div>
                <h3><a href="#">Berry Lace Dress</a></h3>
                <div className="pi-price">Swap Value: ★★★★☆</div>
                <a 
                  href="javascript:;" 
                  className="btn btn-default add2cart"
                  onClick={() => addToSwaps({ name: 'Berry Lace Dress', img: '/assets/pages/img/products/model1.jpg', value: 'Swap Value: ★★★★☆' })}
                >Switch Now</a>
                <div className="sticker sticker-sale"></div>
              </div>
            </div>
            <div>
              <div className="product-item">
                <div className="pi-img-wrapper">
                  <img src="/assets/pages/img/products/model2.jpg" className="img-responsive" alt="Berry Lace Dress" />
                  <div>
                    <a href="/assets/pages/img/products/model2.jpg" className="btn btn-default fancybox-button">Zoom</a>
                    <a href="#product-pop-up" className="btn btn-default fancybox-fast-view">View</a>
                  </div>
                </div>
                <h3><a href="#">Berry Lace Dress 2</a></h3>
                <div className="pi-price">Swap Value: ★★★☆☆</div>
                <a 
                  href="javascript:;" 
                  className="btn btn-default add2cart"
                  onClick={() => addToSwaps({ name: 'Berry Lace Dress 2', img: '/assets/pages/img/products/model2.jpg', value: 'Swap Value: ★★★☆☆' })}
                >Switch Now</a>
              </div>
            </div>
            <div>
              <div className="product-item">
                <div className="pi-img-wrapper">
                  <img src="/assets/pages/img/products/model6.jpg" className="img-responsive" alt="Berry Lace Dress" />
                  <div>
                    <a href="/assets/pages/img/products/model6.jpg" className="btn btn-default fancybox-button">Zoom</a>
                    <a href="#product-pop-up" className="btn btn-default fancybox-fast-view">View</a>
                  </div>
                </div>
                <h3><a href="#">Berry Lace Dress 3</a></h3>
                <div className="pi-price">Swap Value: ★★★★★</div>
                <a 
                  href="javascript:;" 
                  className="btn btn-default add2cart"
                  onClick={() => addToSwaps({ name: 'Berry Lace Dress 3', img: '/assets/pages/img/products/model6.jpg', value: 'Swap Value: ★★★★★' })}
                >Switch Now</a>
              </div>
            </div>
            {/* ... other items can be added here ... */}
          </div>
        </div>
        {/* END SALE PRODUCT */}
      </div>
      {/* END SALE PRODUCT & NEW ARRIVALS */}

      {/* BEGIN SIDEBAR & CONTENT */}
      <div className="row margin-bottom-40 ">
        {/* BEGIN SIDEBAR */}
        <div className="sidebar col-md-3 col-sm-4">
          <ul className="list-group margin-bottom-25 sidebar-menu">
            <li className="list-group-item clearfix"><a href="javascript:;" onClick={() => alert('Filtering by Ladies')}><i className="fa fa-angle-right"></i> Ladies</a></li>
            <li className="list-group-item clearfix dropdown">
              <a href="javascript:;" onClick={() => alert('Filtering by Mens')}>
                <i className="fa fa-angle-right"></i>
                Mens
              </a>
              <ul className="dropdown-menu">
                <li className="list-group-item dropdown clearfix">
                  <a href="javascript:;" onClick={() => alert('Filtering by Shoes')}><i className="fa fa-angle-right"></i> Shoes </a>
                    <ul className="dropdown-menu">
                      <li className="list-group-item dropdown clearfix">
                        <a href="javascript:;" onClick={() => alert('Filtering by Classic')}><i className="fa fa-angle-right"></i> Classic </a>
                        <ul className="dropdown-menu">
                          <li><a href="javascript:;" onClick={() => alert('Filtering by Classic 1')}><i className="fa fa-angle-right"></i> Classic 1</a></li>
                          <li><a href="javascript:;" onClick={() => alert('Filtering by Classic 2')}><i className="fa fa-angle-right"></i> Classic 2</a></li>
                        </ul>
                      </li>
                    </ul>
                </li>
                <li><a href="javascript:;" onClick={() => alert('Filtering by Trainers')}><i className="fa fa-angle-right"></i> Trainers</a></li>
                <li><a href="javascript:;" onClick={() => alert('Filtering by Jeans')}><i className="fa fa-angle-right"></i> Jeans</a></li>
              </ul>
            </li>
            <li className="list-group-item clearfix"><a href="javascript:;" onClick={() => alert('Filtering by Kids')}><i className="fa fa-angle-right"></i> Kids</a></li>
            <li className="list-group-item clearfix"><a href="javascript:;" onClick={() => alert('Filtering by Accessories')}><i className="fa fa-angle-right"></i> Accessories</a></li>
            <li className="list-group-item clearfix"><a href="javascript:;" onClick={() => alert('Filtering by Sports')}><i className="fa fa-angle-right"></i> Sports</a></li>
            <li className="list-group-item clearfix"><a href="javascript:;" onClick={() => alert('Filtering by Brands')}><i className="fa fa-angle-right"></i> Brands</a></li>
            <li className="list-group-item clearfix"><a href="javascript:;" onClick={() => alert('Filtering by Electronics')}><i className="fa fa-angle-right"></i> Electronics</a></li>
            <li className="list-group-item clearfix"><a href="javascript:;" onClick={() => alert('Filtering by Home & Garden')}><i className="fa fa-angle-right"></i> Home & Garden</a></li>
          </ul>
        </div>
        {/* END SIDEBAR */}
        {/* BEGIN CONTENT */}
        <div className="col-md-9 col-sm-8">
          <h2>Popular Swaps</h2>
          <div className="owl-carousel owl-carousel3">
            <div>
              <div className="product-item">
                <div className="pi-img-wrapper">
                  <img src="/assets/pages/img/products/k1.jpg" className="img-responsive" alt="Berry Lace Dress" />
                  <div>
                    <a href="/assets/pages/img/products/k1.jpg" className="btn btn-default fancybox-button">Zoom</a>
                    <a href="#product-pop-up" className="btn btn-default fancybox-fast-view">View</a>
                  </div>
                </div>
                <h3><a href="#">Berry Lace Dress</a></h3>
                <div className="pi-price">Swap Value: ★★★★☆</div>
                <a 
                  href="javascript:;" 
                  className="btn btn-default add2cart"
                  onClick={() => addToSwaps({ name: 'Berry Lace Dress', img: '/assets/pages/img/products/k1.jpg', value: 'Swap Value: ★★★★☆' })}
                >Switch Now</a>
                <div className="sticker sticker-new"></div>
              </div>
            </div>
            <div>
              <div className="product-item">
                <div className="pi-img-wrapper">
                  <img src="/assets/pages/img/products/k2.jpg" className="img-responsive" alt="Berry Lace Dress" />
                  <div>
                    <a href="/assets/pages/img/products/k2.jpg" className="btn btn-default fancybox-button">Zoom</a>
                    <a href="#product-pop-up" className="btn btn-default fancybox-fast-view">View</a>
                  </div>
                </div>
                <h3><a href="#">Berry Lace Dress 2</a></h3>
                <div className="pi-price">Swap Value: ★★★★☆</div>
                <a 
                  href="javascript:;" 
                  className="btn btn-default add2cart"
                  onClick={() => addToSwaps({ name: 'Berry Lace Dress 2', img: '/assets/pages/img/products/k2.jpg', value: 'Swap Value: ★★★★☆' })}
                >Switch Now</a>
              </div>
            </div>
            <div>
              <div className="product-item">
                <div className="pi-img-wrapper">
                  <img src="/assets/pages/img/products/k3.jpg" className="img-responsive" alt="Berry Lace Dress" />
                  <div>
                    <a href="/assets/pages/img/products/k3.jpg" className="btn btn-default fancybox-button">Zoom</a>
                    <a href="#product-pop-up" className="btn btn-default fancybox-fast-view">View</a>
                  </div>
                </div>
                <h3><a href="#">Berry Lace Dress 3</a></h3>
                <div className="pi-price">Swap Value: ★★★★★</div>
                <a 
                  href="javascript:;" 
                  className="btn btn-default add2cart"
                  onClick={() => addToSwaps({ name: 'Berry Lace Dress 3', img: '/assets/pages/img/products/k3.jpg', value: 'Swap Value: ★★★★★' })}
                >Switch Now</a>
              </div>
            </div>
          </div>
        </div>
        {/* END CONTENT */}
      </div>
      {/* END SIDEBAR & CONTENT */}

      {/* BEGIN TWO PRODUCTS & PROMO */}
      <div className="row margin-bottom-35 ">
        {/* BEGIN TWO PRODUCTS */}
        <div className="col-md-6 two-items-bottom-items">
          <h2>Featured Exchanges</h2>
          <div className="owl-carousel owl-carousel2">
            <div>
              <div className="product-item">
                <div className="pi-img-wrapper">
                  <img src="/assets/pages/img/products/k4.jpg" className="img-responsive" alt="Berry Lace Dress" />
                  <div>
                    <a href="/assets/pages/img/products/k4.jpg" className="btn btn-default fancybox-button">Zoom</a>
                    <a href="#product-pop-up" className="btn btn-default fancybox-fast-view">View</a>
                  </div>
                </div>
                <h3><a href="#">Berry Lace Dress</a></h3>
                <div className="pi-price">Swap Value: ★★★☆☆</div>
                <a 
                  href="javascript:;" 
                  className="btn btn-default add2cart"
                  onClick={() => addToSwaps({ name: 'Berry Lace Dress', img: '/assets/pages/img/products/k4.jpg', value: 'Swap Value: ★★★☆☆' })}
                >Switch Now</a>
              </div>
            </div>
            <div>
              <div className="product-item">
                <div className="pi-img-wrapper">
                  <img src="/assets/pages/img/products/k2.jpg" className="img-responsive" alt="Berry Lace Dress" />
                  <div>
                    <a href="/assets/pages/img/products/k2.jpg" className="btn btn-default fancybox-button">Zoom</a>
                    <a href="#product-pop-up" className="btn btn-default fancybox-fast-view">View</a>
                  </div>
                </div>
                <h3><a href="#">Berry Lace Dress 2</a></h3>
                <div className="pi-price">Swap Value: ★★★★☆</div>
                <a 
                  href="javascript:;" 
                  className="btn btn-default add2cart"
                  onClick={() => addToSwaps({ name: 'Berry Lace Dress 2', img: '/assets/pages/img/products/k2.jpg', value: 'Swap Value: ★★★★☆' })}
                >Switch Now</a>
              </div>
            </div>
          </div>
        </div>
        {/* END TWO PRODUCTS */}
        {/* BEGIN PROMO */}
        <div className="col-md-6 shop-index-carousel">
          <div className="content-slider">
            <div id="myCarousel" className="carousel slide" data-ride="carousel">
              {/* Indicators */}
              <ol className="carousel-indicators">
                <li data-target="#myCarousel" data-slide-to="0" className="active"></li>
                <li data-target="#myCarousel" data-slide-to="1"></li>
                <li data-target="#myCarousel" data-slide-to="2"></li>
              </ol>
              <div className="carousel-inner">
                <div className="item active">
                  <img src="/assets/pages/img/index-sliders/slide1.jpg" className="img-responsive" alt="Berry Lace Dress" />
                </div>
                <div className="item">
                  <img src="/assets/pages/img/index-sliders/slide2.jpg" className="img-responsive" alt="Berry Lace Dress" />
                </div>
                <div className="item">
                  <img src="/assets/pages/img/index-sliders/slide3.jpg" className="img-responsive" alt="Berry Lace Dress" />
                </div>
              </div>
            </div>
          </div>
        </div>
        {/* END PROMO */}
      </div>        
      {/* END TWO PRODUCTS & PROMO */}

    </>
  );
};

export default HomeContent;
