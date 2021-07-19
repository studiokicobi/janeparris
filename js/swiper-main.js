// Swiper init
const swiper = new Swiper('.swiper-container', {
    // Optional parameters
    //direction: 'vertical',
    loop: true,
    autoplay: {
        delay: 7500,
        disableOnInteraction: true,
    },
    pagination: {
        el: '.swiper-pagination',
        clickable: true,
    },

    // Navigation arrows
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },

    // And if we need scrollbar
    // scrollbar: {
    //     el: '.swiper-scrollbar',
    // },
});
