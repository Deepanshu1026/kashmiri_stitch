document.addEventListener("DOMContentLoaded", (event) => {
    // preloader
    const preloader = document.getElementById('preloader');
    preloader.style.display = 'none';
    document.body.style.position = 'static';

    // HEADER NAV IN MOBILE
    if (document.querySelector(".ul-header-nav")) {
        const ulSidebar = document.querySelector(".ul-sidebar");
        const ulSidebarOpener = document.querySelector(".ul-header-sidebar-opener");
        const ulSidebarCloser = document.querySelector(".ul-sidebar-closer");
        const ulMobileMenuContent = document.querySelector(".to-go-to-sidebar-in-mobile");
        const ulHeaderNavMobileWrapper = document.querySelector(".ul-sidebar-header-nav-wrapper");
        const ulHeaderNavOgWrapper = document.querySelector(".ul-header-nav-wrapper");

        function updateMenuPosition() {
            if (window.innerWidth < 992) {
                ulHeaderNavMobileWrapper.appendChild(ulMobileMenuContent);
            }

            if (window.innerWidth >= 992) {
                ulHeaderNavOgWrapper.appendChild(ulMobileMenuContent);
            }
        }

        updateMenuPosition();

        window.addEventListener("resize", () => {
            updateMenuPosition();
        });

        ulSidebarOpener.addEventListener("click", () => {
            ulSidebar.classList.add("active");
        });

        ulSidebarCloser.addEventListener("click", () => {
            ulSidebar.classList.remove("active");
        });


        // menu dropdown/submenu in mobile
        const ulHeaderNavMobile = document.querySelector(".ul-header-nav");
        const ulHeaderNavMobileItems = ulHeaderNavMobile.querySelectorAll(".has-sub-menu");
        ulHeaderNavMobileItems.forEach((item) => {
            if (window.innerWidth < 992) {
                item.addEventListener("click", () => {
                    item.classList.toggle("active");
                });
            }
        });
    }

    // header search in mobile start
    const ulHeaderSearchOpener = document.querySelector(".ul-header-mobile-search-opener");
    const ulHeaderSearchCloser = document.querySelector(".ul-header-mobile-search-closer");
    if (ulHeaderSearchOpener) {
        ulHeaderSearchOpener.addEventListener("click", () => {
            document.querySelector(".ul-header-search-form-wrapper").classList.add("active");
        });
    }

    if (ulHeaderSearchCloser) {
        ulHeaderSearchCloser.addEventListener("click", () => {
            document.querySelector(".ul-header-search-form-wrapper").classList.remove("active");
        });
    }
    // header search in mobile end

    // Header Search Logic: Clean URL, Instant Category Redirect, Live Suggestions
    const headerSearchForm = document.querySelector('.ul-header-search-form');
    if (headerSearchForm) {
        const categorySelect = document.getElementById('ul-header-search-category');
        const searchInput = headerSearchForm.querySelector('input[name="product-search"]');
        const suggestionsBox = document.getElementById('ul-search-suggestions');

        // 1. Clean URL on Submit
        headerSearchForm.addEventListener('submit', function (e) {
            if (searchInput && !searchInput.value.trim()) {
                searchInput.disabled = true;
                setTimeout(() => { searchInput.disabled = false; }, 100);
            }
        });

        // 2. Instant Category Redirect
        if (categorySelect) {
            categorySelect.addEventListener('change', function () {
                const selectedCategory = this.value;
                if (selectedCategory) {
                    window.location.href = 'shop.php?category=' + encodeURIComponent(selectedCategory);
                }
            });
        }

        // 3. Live Search Suggestions with Debounce & Keyboard Nav
        if (searchInput && suggestionsBox) {
            let debounceTimer;
            let activeIndex = -1;

            const debounce = (func, delay) => {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(func, delay);
            };

            searchInput.addEventListener('input', function () {
                const query = this.value.trim();

                debounce(() => {
                    if (query.length < 2) {
                        suggestionsBox.classList.remove('active');
                        suggestionsBox.innerHTML = '';
                        return;
                    }

                    const category = categorySelect ? categorySelect.value : '';
                    let url = 'search_suggestions.php?q=' + encodeURIComponent(query);
                    if (category) {
                        url += '&category=' + encodeURIComponent(category);
                    }

                    fetch(url)
                        .then(response => response.json())
                        .then(data => {
                            suggestionsBox.innerHTML = '';
                            activeIndex = -1; // Reset selection
                            if (data.length > 0) {
                                data.forEach((product, index) => {
                                    const item = document.createElement('a');
                                    item.href = 'shop-details.php?id=' + product.id;
                                    item.className = 'ul-search-suggestion-item';
                                    item.innerHTML = `
                                        <img src="${product.image}" alt="${product.title}" class="ul-search-suggestion-img">
                                        <div class="ul-search-suggestion-info">
                                            <h4>${product.title}</h4>
                                            <span>₹${product.price}</span>
                                        </div>
                                    `;
                                    suggestionsBox.appendChild(item);
                                });
                                suggestionsBox.classList.add('active');
                            } else {
                                // Show "No results" message properly
                                const noResult = document.createElement('div');
                                noResult.className = 'ul-search-suggestion-item';
                                noResult.style.cursor = 'default';
                                noResult.innerHTML = '<span style="color: #666; font-size: 14px;">No products found</span>';
                                suggestionsBox.appendChild(noResult);
                                suggestionsBox.classList.add('active');
                            }
                        })
                        .catch(err => console.error('Search error:', err));
                }, 300); // 300ms debounce
            });

            // Keyboard Navigation
            searchInput.addEventListener('keydown', function (e) {
                const items = suggestionsBox.querySelectorAll('a.ul-search-suggestion-item');
                if (items.length === 0) return;

                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    activeIndex++;
                    if (activeIndex >= items.length) activeIndex = 0;
                    updateActiveItem(items);
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    activeIndex--;
                    if (activeIndex < 0) activeIndex = items.length - 1;
                    updateActiveItem(items);
                } else if (e.key === 'Enter') {
                    if (activeIndex > -1 && items[activeIndex]) {
                        e.preventDefault();
                        items[activeIndex].click();
                    }
                    // If no item selected, default form submit happens (which goes to shop.php)
                } else if (e.key === 'Escape') {
                    suggestionsBox.classList.remove('active');
                    searchInput.blur();
                }
            });

            function updateActiveItem(items) {
                // Clear previous highlights
                items.forEach(item => item.style.backgroundColor = '');

                // Highlight new active item
                if (items[activeIndex]) {
                    items[activeIndex].style.backgroundColor = '#f9f9f9';
                    items[activeIndex].scrollIntoView({ block: 'nearest' });
                }
            }

            // Close suggestions when clicking outside
            document.addEventListener('click', function (e) {
                if (!headerSearchForm.contains(e.target)) {
                    suggestionsBox.classList.remove('active');
                }
            });

            // Re-open if input has value and focused
            searchInput.addEventListener('focus', function () {
                if (this.value.trim().length >= 2 && suggestionsBox.children.length > 0) {
                    suggestionsBox.classList.add('active');
                }
            });
        }
    }

    if (document.querySelector(".ul-header-top-slider")) {
        new Splide('.ul-header-top-slider', {
            arrows: false,
            pagination: false,
            type: 'loop',
            drag: 'free',
            focus: 'center',
            perPage: 9,
            autoWidth: true,
            gap: 15,
            autoScroll: {
                speed: 1.5,
            },
        }).mount(window.splide.Extensions);
    }

    // search category
    if (document.querySelector("#ul-header-search-category")) {
        new SlimSelect({
            select: '#ul-header-search-category',
            settings: {
                showSearch: false,
            }
        })
    }

    // banner image slider
    const bannerThumbSlider = new Swiper(".ul-banner-img-slider", {
        slidesPerView: 1.4,
        loop: true,
        autoplay: true,
        spaceBetween: 15,
        // slideToClickedSlide: true,
        // centeredSlides: true,
        breakpoints: {
            992: {
                spaceBetween: 15,
            },
            1680: {
                spaceBetween: 26,
            },
            1700: {
                spaceBetween: 30,
            }
        }
    });


    // BANNER SLIDER
    const bannerSlider = new Swiper(".ul-banner-slider", {
        slidesPerView: 1,
        loop: true,
        // slideToClickedSlide: true,
        // effect: "fade",
        autoplay: true,
        thumbs: {
            swiper: bannerThumbSlider,
        },
        navigation: {
            nextEl: ".ul-banner-slider-nav .next",
            prevEl: ".ul-banner-slider-nav .prev",
        },
        pagination: {
            el: ".ul-banner-pagination",
            clickable: true,
        },
    });

    // bannerThumbSlider.on('slideChange', function () {
    //     bannerSlider.slideTo(bannerThumbSlider.activeIndex);
    // });


    // products filtering 
    if (document.querySelector(".ul-filter-products-wrapper")) {
        mixitup('.ul-filter-products-wrapper');
    }


    // product slider
    new Swiper(".ul-products-slider-1", {
        slidesPerView: 3,
        loop: true,
        autoplay: true,
        spaceBetween: 15,
        navigation: {
            nextEl: ".ul-products-slider-1-nav .next",
            prevEl: ".ul-products-slider-1-nav .prev",
        },
        breakpoints: {
            0: {
                slidesPerView: 1,
            },
            480: {
                slidesPerView: 2,
            },
            992: {
                slidesPerView: 3,
            },
            1200: {
                spaceBetween: 20,
            },
            1400: {
                spaceBetween: 22,
            },
            1600: {
                spaceBetween: 26,
            },
            1700: {
                spaceBetween: 30,
            }
        }
    });

    // product slider
    new Swiper(".ul-products-slider-2", {
        slidesPerView: 3,
        loop: true,
        autoplay: true,
        spaceBetween: 15,
        navigation: {
            nextEl: ".ul-products-slider-2-nav .next",
            prevEl: ".ul-products-slider-2-nav .prev",
        },
        breakpoints: {
            0: {
                slidesPerView: 1,
            },
            480: {
                slidesPerView: 2,
            },
            992: {
                slidesPerView: 3,
            },
            1200: {
                spaceBetween: 20,
            },
            1400: {
                spaceBetween: 22,
            },
            1600: {
                spaceBetween: 26,
            },
            1700: {
                spaceBetween: 30,
            }
        }
    });

    // flash sale slider\
    new Swiper(".ul-flash-sale-slider", {
        slidesPerView: 1,
        loop: true,
        autoplay: true,
        spaceBetween: 15,
        breakpoints: {
            480: {
                slidesPerView: 2,
            },
            768: {
                slidesPerView: 3,
            },
            992: {
                slidesPerView: 4,
            },
            1200: {
                spaceBetween: 20,
                slidesPerView: 4,
            },
            1680: {
                spaceBetween: 26,
                slidesPerView: 4,
            },
            1700: {
                spaceBetween: 30,
                slidesPerView: 4.7,
            }
        }
    })

    // reviews slider
    new Swiper(".ul-reviews-slider", {
        slidesPerView: 1,
        loop: true,
        autoplay: true,
        spaceBetween: 15,
        breakpoints: {
            768: {
                slidesPerView: 2,
            },
            992: {
                spaceBetween: 20,
                slidesPerView: 3,
            },
            1200: {
                spaceBetween: 20,
                slidesPerView: 4,
            },
            1680: {
                slidesPerView: 4,
                spaceBetween: 26,
            },
            1700: {
                slidesPerView: 4,
                spaceBetween: 30,
            }
        }
    });

    // gallery slider
    new Swiper(".ul-gallery-slider", {
        slidesPerView: 2.2,
        loop: true,
        autoplay: true,
        centeredSlides: true,
        spaceBetween: 15,
        breakpoints: {
            480: {
                slidesPerView: 3.4,
            },
            576: {
                slidesPerView: 4,
            },
            768: {
                slidesPerView: 5,
            },
            992: {
                spaceBetween: 20,
                slidesPerView: 5.5,
            },
            1680: {
                spaceBetween: 26,
                slidesPerView: 5.5,
            },
            1700: {
                spaceBetween: 30,
                slidesPerView: 5.5,
            },
            1920: {
                spaceBetween: 30,
                slidesPerView: 6,
                centeredSlides: false,
            }
        }
    });

    // product page price filter
    var priceFilterSlider = document.getElementById('ul-products-price-filter-slider');

    if (priceFilterSlider) {
        noUiSlider.create(priceFilterSlider, {
            start: [20, 80],
            connect: true,
            range: {
                'min': 0,
                'max': 100
            }
        });
    }

    // product details slider
    new Swiper(".ul-product-details-img-slider", {
        slidesPerView: 1,
        loop: true,
        autoplay: true,
        spaceBetween: 0,
        navigation: {
            nextEl: "#ul-product-details-img-slider-nav .next",
            prevEl: "#ul-product-details-img-slider-nav .prev",
        },
    });

    // search category
    if (document.querySelector("#ul-checkout-country")) {
        new SlimSelect({
            select: '#ul-checkout-country',
            settings: {
                showSearch: false,
                contentLocation: document.querySelector('.ul-checkout-country-wrapper')
            }
        })
    }

    // sidebar products slider
    new Swiper(".ul-sidebar-products-slider", {
        slidesPerView: 1,
        loop: true,
        autoplay: true,
        spaceBetween: 30,
        navigation: {
            nextEl: ".ul-sidebar-products-slider-nav .next",
            prevEl: ".ul-sidebar-products-slider-nav .prev",
        },
        breakpoints: {
            1400: {
                slidesPerView: 2,
            }
        }
    });


    // quantity field
    if (document.querySelector(".ul-product-quantity-wrapper")) {
        const quantityWrapper = document.querySelectorAll(".ul-product-quantity-wrapper");

        quantityWrapper.forEach((item) => {
            const quantityInput = item.querySelector(".ul-product-quantity");
            const quantityIncreaseButton = item.querySelector(".quantityIncreaseButton");
            const quantityDecreaseButton = item.querySelector(".quantityDecreaseButton");

            quantityIncreaseButton.addEventListener("click", function () {
                quantityInput.value = parseInt(quantityInput.value) + 1;
            });
            quantityDecreaseButton.addEventListener("click", function () {
                if (quantityInput.value > 1) {
                    quantityInput.value = parseInt(quantityInput.value) - 1;
                }
            });
        })
    }

    // parallax effect
    const parallaxImage = document.querySelector(".ul-video-cover");

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                window.addEventListener("scroll", parallaxEffect);
                parallaxEffect(); // Initialize position
            } else {
                window.removeEventListener("scroll", parallaxEffect);
            }
        });
    });

    if (parallaxImage) {
        observer.observe(parallaxImage);
    }

    function parallaxEffect() {
        const rect = parallaxImage.getBoundingClientRect();
        const windowHeight = window.innerHeight;
        const imageCenter = rect.top + rect.height / 2;
        const viewportCenter = windowHeight / 2;

        // Calculate offset from viewport center
        const offset = (imageCenter - viewportCenter) * -0.5; // Adjust speed with multiplier

        parallaxImage.style.transform = `translateY(${offset}px)`;
    }

    // Add to Wishlist Functionality
    document.addEventListener('click', function (e) {
        if (e.target.closest('.add-to-wishlist')) {
            e.preventDefault();
            const btn = e.target.closest('.add-to-wishlist');

            let pid = btn.getAttribute('data-pid');

            // Fallback for details page if not on button
            if (!pid) {
                // Try finding it in hidden input commonly used in details forms
                const input = document.querySelector('input[name="product_id"]');
                if (input) pid = input.value;
                // Or try parsing URL if on details page
                else {
                    const urlParams = new URLSearchParams(window.location.search);
                    pid = urlParams.get('id');
                }
            }

            if (!pid) {
                console.error("Product ID not found for wishlist action");
                return;
            }

            const formData = new FormData();
            formData.append('product_id', pid);

            fetch('add_to_wishlist.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        const icon = btn.querySelector('i');
                        if (icon) {
                            // Change style to indicate success
                            icon.style.color = '#BF0A30';
                            icon.style.fontWeight = 'bold';
                        }
                    } else {
                        if (data.message && data.message.toLowerCase().includes('login')) {
                            if (confirm('Please login to add to wishlist')) {
                                window.location.href = 'login.php';
                            }
                        } else {
                            alert(data.message);
                        }
                    }
                })
                .catch(err => {
                    console.error('Error adding to wishlist:', err);
                });
        }
    });

    // Initialize WOW.js for scroll animations
    if (typeof WOW !== 'undefined') {
        new WOW().init();
    }
});