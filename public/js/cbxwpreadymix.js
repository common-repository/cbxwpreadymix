(function ($) {
    'use strict';

    $(document).ready(function () {
        //testimonial
        $('.cbxwpreadymix_testimonial_owl').owlCarousel({
            items: 1,
            loop: true,
            autoplay: true,
            margin: 10,
            nav: false,
        });


        //portfolio
        $(".cbxwpreadymix_portfolio_wrapper").each(function (index, element) {

            var $element = $(element);

            var $portfolio_container = $element.find('.cbxwpreadymix_portfolio_container').isotope({
                // options
                itemSelector: '.cbxwpreadymix_portfolio',
            });

            $element.find('.cbxwpreadymix_portfolio_filter').on('click', 'button', function (e) {
                var filterValue = $(this).attr('data-filter');
                $portfolio_container.isotope({filter: filterValue});
            });
        });
    });


})(jQuery);
