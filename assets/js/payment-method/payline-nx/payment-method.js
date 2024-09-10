import { registerPaymentMethod } from '@woocommerce/blocks-registry';
import { __ } from '@wordpress/i18n';
import { getPaymentMethodData } from '@woocommerce/settings';
import { decodeEntities } from '@wordpress/html-entities';


/**
 * Internal dependencies
 */
import { PAYMENT_METHOD_NAME } from './constants';

const settings = getPaymentMethodData( PAYMENT_METHOD_NAME, {} );
const defaultLabel = __(
    'Payline NX',
    'payline'
);
const label = decodeEntities( settings?.title || '' ) || defaultLabel;

/**
 * Content component
 */
const Content = () => {
    return decodeEntities( settings.description || '' );
};

/**
 * Label component
 *
 * @param {*} props Props from payment API.
 */
const Label = ( props ) => {
    const { PaymentMethodLabel } = props.components;
    return <PaymentMethodLabel text={ label } />;
};


const paylineRecPaymentMethod = {
    name: PAYMENT_METHOD_NAME,
    label: <Label />,
    content: <Content />,
    edit: <Content />,
    canMakePayment: () => true,
    ariaLabel: label,
    supports: {
        features: settings?.supports ?? [],
    },
};

registerPaymentMethod( paylineRecPaymentMethod );
