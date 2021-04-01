;
(function($) {
    $(document).ready(function() {
        $('form.user-profile').on('submit', function(e) {
            var items = 'input, select, button, label span';
            var $form = $(this);
            e.preventDefault();
            /**
             * remove messages
             */
            $('.notice', $form).detach();
            /**
             * Check passwords
             */
            if ( $('[name="user[password][new1]"]', $form ).val() || $('[name="user[password][new2]"]', $form ).val()) {
                if ( $('[name="user[password][new1]"]', $form ).val() !== $('[name="user[password][new2]"]', $form ).val()) {
                    if ( '' === $('[name="user[password][old]"]', $form ).val() ) {
                        $form.prepend(
                            opi_jobs_theme.messages.password.all
                        );
                        return;
                    } else {
                        $form.prepend(
                            opi_jobs_theme.messages.password.missmatch
                        );
                        return;
                    }
                } else if ( '' === $('[name="user[password][old]"]', $form ).val() ) {
                    $form.prepend(
                        opi_jobs_theme.messages.password.empty
                    );
                    return;
                }
            }
            /**
             * send
             */
            $.ajax({
                url: opi_jobs_theme.ajaxurl,
                data: {
                    action: 'opi_jobs_save_profile',
                    data: $form.serialize(),
                },
                dataType: "json",
                type: "post",
                beforeSend: function() {
                    $form.addClass('sending');
                    $(items, $form).attr('disabled', 'disabled');
                },
                success: function(response) {
                    $form.removeClass('sending');
                    $(items, $form).removeAttr('disabled');
                    if ( 'undfined' !== typeof response.data.message ) {
                        $form.prepend( response.data.message );
                    }
                    if ( 'undfined' !== typeof response.data.reload && response.data.reload ) {
                        window.location.href = response.data.reload;
                    }
                },
                error: function(response) {
                    $form.removeClass('sending');
                    $(items, $form).removeAttr('disabled');
                }
            });
            return false;
        });
        $('form.user-profile select').on('change', function(e) {
            if ('-1' === $(this).val()) {
                $(this).closest('div').removeClass('has-select');
                $('input', $(this).closest('div')).focus();
            } else {
                $(this).closest('div').addClass('has-select');
            }
        });
    });
})(jQuery);
