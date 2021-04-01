;
window.opi = window.opi || [];
window.opi.filter = window.opi.filter || [];
(function() {
    window.opi.filter.form = document.getElementById('opi-jobs-filter-form');
    if (!window.opi.filter.form) {
        return;
    }
    window.opi.filter.pageField = document.getElementById('opi-jobs-filter-page');
    if (!window.opi.filter.pageField) {
        return;
    }
    /**
     * pagination
     */
    window.opi.filter.pagination = document.getElementById('opi-jobs-pagination');
    if (window.opi.filter.pagination) {
        window.opi.filter.paginationLinks = window.opi.filter.pagination.getElementsByTagName('a');
        for (i = 0; i < window.opi.filter.paginationLinks.length; i++) {
            window.opi.filter.paginationLinks[i].addEventListener('click', function(event) {
                event.preventDefault();
                window.opi.filter.pageField.value = event.target.getAttribute('data-page');
                window.opi.filter.form.submit();
                return false;
            });
        }
    }
    /**
     * input
     */
    window.opi.filter.formInputs = window.opi.filter.form.getElementsByTagName('input');
    for (i = 0; i < window.opi.filter.formInputs.length; i++) {
        window.opi.filter.formInputs[i].addEventListener('change', function(event) {
            window.opi.filter.pageField.value = 1;
        });
    }
    /**
     * select
     */
    window.opi.filter.formSelects = window.opi.filter.form.getElementsByTagName('select');
    for (i = 0; i < window.opi.filter.formSelects.length; i++) {
        window.opi.filter.formSelects[i].addEventListener('change', function(event) {
            window.opi.filter.pageField.value = 1;
        });
    }
}());

jQuery(document).ready(function($) {
    $('.job-manager-remove-uploaded-file').on( 'click', function(event){
        var $parent = $(this).closest( 'div' );
        event.preventDefault();
        /**
         * send
         */
        $.ajax({
            url: opi_jobs_theme.ajaxurl,
            data: {
                action: 'opi_jobs_delete_attachement',
                id: $parent.data('id'),
                nonce: $parent.data('nonce' )
            },
            type: "post",
            success: function(response) {
                $parent.detach();
            },
            error: function(response) {
            }
        });
        return false;
    });
});
jQuery(document).ready(function($) {
    $('.no_job_listings_found a').on('click', function() {
        window.location.href = $('form.job_filters').attr('action');
    });
    if ('function' !== typeof $.fn.select2) {
        return;
    }
    var charsWords = opi_jobs_theme.select2.charsWords;
    var itemsWords = opi_jobs_theme.select2.itemsWords;
    var pluralWord = function pluralWord(numberOfChars, words) {
        if (numberOfChars === 1) {
            return words[0];
        }
        if (numberOfChars > 1 && numberOfChars <= 4) {
            return words[1];
        }
        if (numberOfChars >= 5) {
            return words[2];
        }
    };
    $('select[data-select2-action]').each(function() {
        $(this).select2({
            placeholder: $(this).data('select2-placeholder'),
            language: {
                errorLoading: function () {
                    return opi_jobs_theme.select2.errorLoading;
                },
                inputTooLong: function (args) {
                    var overChars = args.input.length - args.maximum;
                    return opi_jobs_theme.select2.inputTooLong + overChars + ' ' + pluralWord(overChars, charsWords);
                },
                inputTooShort: function (args) {
                    var remainingChars = args.minimum - args.input.length;
                    return opi_jobs_theme.select2.inputTooShort + remainingChars + ' ' + pluralWord(remainingChars, charsWords);
                },
                loadingMore: function () {
                    return opi_jobs_theme.select2.loadingMore;
                },
                maximumSelected: function (args) {
                    return opi_jobs_theme.select2.maximumSelected.prefix + args.maximum + ' ' + pluralWord(args.maximum, itemsWords);
                },
                noResults: function () {
                    return opi_jobs_theme.select2.noResults;
                },
                searching: function () {
                    return opi_jobs_theme.select2.searching;
                },
                removeAllItems: function () {
                    return opi_jobs_theme.select2.removeAllItems;
                },
                removeItem: function () {
                    return opi_jobs_theme.select2.removeItem;
                }
            },
            ajax: {
                url: opi_jobs_theme.ajaxurl,
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term, // search term
                        page: params.page,
                        action: $(this).data('select2-action'),
                        _wpnonce: opi_jobs_theme._wpnonce,
                    };
                },
                processResults: function(response, params) {
                    params.page = params.page || 1;
                    return {
                        results: response.data.items,
                        pagination: {
                            more: (params.page * 30) < response.data.total_count
                        }
                    };
                },
                cache: true
            },
            tags: true
        });
    });

});
