import React from 'react';

const Slider = ({ addToSwaps }) => {
  return (
    <div className="page-slider margin-bottom-35">
        <div id="carousel-example-generic" className="carousel slide carousel-slider">
            {/* Indicators */}
            <ol className="carousel-indicators">
                <li data-target="#carousel-example-generic" data-slide-to="0" className="active"></li>
                <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                <li data-target="#carousel-example-generic" data-slide-to="2"></li>
                <li data-target="#carousel-example-generic" data-slide-to="3"></li>
            </ol>

            {/* Wrapper for slides */}
            <div className="carousel-inner" role="listbox">
                {/* First slide */}
                <div className="item carousel-item-four active">
                    <div className="container">
                        <div className="carousel-position-four text-center">
                            <h2 className="margin-bottom-20 animate-delay carousel-title-v3 border-bottom-title text-uppercase" data-animation="animated fadeInDown">
                                Tones of <br/><span className="color-red-v2">Swap & Switch Features</span><br/> designed
                            </h2>
                            <p className="carousel-subtitle-v2" data-animation="animated fadeInUp">Lorem ipsum dolor sit amet constectetuer diam <br/>
                            adipiscing elit euismod ut laoreet dolore.</p>
                        </div>
                    </div>
                </div>
                
                {/* Second slide */}
                <div className="item carousel-item-five">
                    <div className="container">
                        <div className="carousel-position-four text-center">
                            <h2 className="animate-delay carousel-title-v4" data-animation="animated fadeInDown">
                                Unlimited
                            </h2>
                            <p className="carousel-subtitle-v2" data-animation="animated fadeInDown">
                                Layout Options
                            </p>
                            <p className="carousel-subtitle-v3 margin-bottom-30" data-animation="animated fadeInUp">
                                Fully Responsive
                            </p>
                            <a className="carousel-btn" href="#" data-animation="animated fadeInUp">See More Details</a>
                        </div>
                        <img className="carousel-position-five animate-delay hidden-sm hidden-xs" src="/assets/pages/img/shop-slider/slide2/price.png" alt="Price" data-animation="animated zoomIn" />
                    </div>
                </div>

                {/* Third slide */}
                <div className="item carousel-item-six">
                    <div className="container">
                        <div className="carousel-position-four text-center">
                            <span className="carousel-subtitle-v3 margin-bottom-15" data-animation="animated fadeInDown">
                                Full Admin &amp; Frontend
                            </span>
                            <p className="carousel-subtitle-v4" data-animation="animated fadeInDown">
                                Switching Platform
                            </p>
                            <p className="carousel-subtitle-v3" data-animation="animated fadeInDown">
                                Is Ready For Your Project
                            </p>
                        </div>
                    </div>
                </div>

                {/* Fourth slide */}
                <div className="item carousel-item-seven">
                   <div className="center-block">
                        <div className="center-block-wrap">
                            <div className="center-block-body">
                                <h2 className="carousel-title-v1 margin-bottom-20" data-animation="animated fadeInDown">
                                    The most <br/>
                                    wanted bijouterie
                                </h2>
                                <a 
                                  className="carousel-btn" 
                                  href="javascript:;" 
                                  data-animation="animated fadeInUp"
                                  onClick={() => addToSwaps({ name: 'Luxury Bijouterie', img: '/assets/pages/img/products/model4.jpg', value: 'Swap Value: ★★★★★' })}
                                >Swap It Now!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {/* Controls */}
            <a className="left carousel-control carousel-control-shop" href="#carousel-example-generic" role="button" data-slide="prev">
                <i className="fa fa-angle-left" aria-hidden="true"></i>
            </a>
            <a className="right carousel-control carousel-control-shop" href="#carousel-example-generic" role="button" data-slide="next">
                <i className="fa fa-angle-right" aria-hidden="true"></i>
            </a>
        </div>
    </div>
  );
};

export default Slider;
