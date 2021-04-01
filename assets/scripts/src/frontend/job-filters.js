var isMobile = false;

(function($) {
    $(document).ready(function() {

        toggleDivOnMobile();
        handleWindowResize();

        window.addEventListener("resize", function() {
            handleWindowResize();
        });
    });
})(jQuery);

function handleWindowResize() {
    isMobile = window.outerWidth <= 768;
}

function toggleDivOnMobile() {
    jQuery(".job-filters-title").click(function(e) {
        e.preventDefault();
        var content = $(".job_filters_fields");
        if (isMobile) {
            content.slideToggle(250);
            $(this).toggleClass("hide");
        }
    });
    return false;
}