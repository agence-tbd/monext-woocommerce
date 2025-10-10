import { useEffect, useRef } from 'react';

const addCustomCss = (cssContent, attributes, container) => {
    const style = document.createElement("style");
    style.textContent = cssContent;
    Object.keys(attributes).forEach((key) => {
        style.setAttribute(key, attributes[key]);
    });
    container.appendChild(style);
}

const WidgetPayline = ( {settings, checkoutContext} ) => {

    const previousToken = useRef(null);

    //--> Chargement des CSS et JS nécessaires pour le widget Payline
    useEffect( () => {
        if ( checkoutContext.activePaymentMethod === "payline_cpt" && Payline?.Api ) {
            Payline.Api.reset();
        }

        const placeOrderButton = document.querySelector(".wc-block-components-checkout-place-order-button");
        if ( placeOrderButton ) {
            placeOrderButton.style.display = "none";
        }

        return () => {
            document.querySelectorAll("[data-added-by='payline']").forEach((element) => {
                element.remove();
            });

            if ( placeOrderButton ) {
                placeOrderButton.style.display = "";
            }
        };
    }, [] );

    useEffect(() => {
        const checkoutToken = checkoutContext.cartData.extensions?.monext_payline?.widget_token;
        const paylineWidgetContainer = document.getElementById('PaylineWidget');
        if (checkoutToken && (previousToken.current !== undefined && previousToken.current !== checkoutToken)) {
            paylineWidgetContainer.setAttribute('data-token', checkoutToken);
            if (Payline?.Api) {
                Payline.Api.reset();
            }
        }
        previousToken.current = checkoutToken;
    }, [checkoutContext])

    return (
        <div dangerouslySetInnerHTML={{__html: settings.payline_widget_div}} ></div>
    );
}


export default WidgetPayline;