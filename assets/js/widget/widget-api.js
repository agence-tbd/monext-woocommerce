const customizeWidget = paylineData.customizeWidget === 'yes';
const ctaLabel = paylineData.ctaButton;
const textUnderCta = paylineData.textUnderCta;

window.eventDidshowstate = function (e) {
    if ( e.state && e.state === "PAYMENT_METHODS_LIST" && customizeWidget ) {
        if (ctaLabel != "") {
            jQuery(".PaylineWidget .pl-pay-btn, .PaylineWidget .pl-btn").html(ctaLabel.replace("{{amount}}", Payline.Api.getContextInfo("PaylineFormattedAmount")));
        }

        if (textUnderCta) {
            jQuery(".PaylineWidget .pl-pay-btn, .PaylineWidget .pl-btn").after(jQuery("<p>").html(textUnderCta).addClass("pl-text-under-cta"))
        }
    }
}
hideReceivedContext = function() {
    jQuery(".storefront-breadcrumb").hide();
    jQuery(".order_details").hide();
    jQuery("h1.entry-title").html("'. __('Payment', 'payline') .'")
    jQuery("#site-header-cart").hide();
};

eventFinalstatehasbeenreached= function (e) {
    if ( e.state === "PAYMENT_SUCCESS" ) {
        //--> Redirect to success page
        //--> Ticket is hidden by CSS
        //--> Wait for DOM update to simulate a click on the ticket confirmation button
        window.setTimeout(() => {
            const ticketConfirmationButton = document.getElementById("pl-ticket-default-ticket_btn");
            if ( ticketConfirmationButton ) {
                ticketConfirmationButton.click();
            }
        }, 0);
    }
};

// To delete if \WC_Abstract_Payline::generate_payline_form
cancelPaylinePayment = function ()
{
    Payline.Api.endToken(); // end the token s life
    window.location.href = Payline.Api.getCancelAndReturnUrls().cancelUrl; // redirect the user to cancelUrl
}
function togglePlaceOrderButton () {
    if ( paylineData.widget_integration === 'redirection' ) {
        return;
    }
    
    const currentPaymentMethod = jQuery('form.checkout').find( 'input[name="payment_method"]:checked' ).val();
    const placeOrderButton = jQuery('#place_order');
    if ( currentPaymentMethod === 'payline_cpt' ) {
        placeOrderButton.hide();
    } else {
        placeOrderButton.show();
    }
}

// Refresh the Payline widget when the cart is updated
jQuery( document.body ).on( 'updated_cart_totals updated_checkout', function() {
    if ( Payline?.Api ) {
        togglePlaceOrderButton();
        Payline.Api.reset();
    }
});

jQuery(document.body).on('payment_method_selected', function () {
    togglePlaceOrderButton();
})
