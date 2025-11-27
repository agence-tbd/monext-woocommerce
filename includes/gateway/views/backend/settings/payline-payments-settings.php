<?php
?>
<style>
    #paylineSettings {
        .payline_settings_hero__container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 5px;

            margin-bottom: 35px;

            h1 {
                margin: 0;
                padding: 0;
            }

            nav {
                a {
                    display: inline-block;
                    padding: 10px 20px;
                    background-color: #EF3F44;
                    color: #fff;
                    border-radius: 25px;
                    text-decoration: none;
                    width: 100%;
                    text-align: center;
                    font-weight: bold;
                    font-size: 16px;
                    transition: background-color ease-in-out 0.3s;
                }

                a:hover {
                    background-color: #aa2d30;
                }

                a.payline_back_link {
                    background: #8c8c8c;
                }

                a.payline_active_link {
                    background: #8c8c8c;
                }

                a.payline_back_link:hover {
                    background-color: #000;
                }
            }
        }
    }

    body.woocommerce_page_wc-settings #mainform {
        background: #FFF;
    }

    .notice.wcs-nux__notice {
        display: none;
    }

    #paylineCtaPreviewContainer {
        padding: 120px;
        border: 1px solid #000;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        gap: 15px;
    }

    #buttonPreview {
        padding: 10px 26px;
        font-size: 20px;
        border: none;
        border-radius: 3px;
        color: #fff;
        background-color: #26A434;
    }

    #buttonPreview:hover {
        background-color: #1c7b27;
        text-decoration: underline;
    }
</style>

<?php
$title = [
    'payline' => __('Common settings', 'payline'),
    'payline_cpt' => __('Setup Standard payment (CPT)', 'payline'),
    'payline_rec' => __('Setup Installment Payment (REC)', 'payline'),
    'payline_nx' => __('Setup Subscription payment (NX)', 'payline'),
];

$pageTitle = !empty($section) && !empty($title[$section]) ? $title[$section] : '';
?>

<div id="paylineSettings">
    <div class="payline_settings_hero__container">
        <img src="<?php echo WCPAYLINE_PLUGIN_URL . 'assets/images/logo-monext.svg'?>" alt="Monext" width="250" />
        <h1><?= $pageTitle ?></h1>
        <p>
            <?php echo "Monext extension v".$this->extensionVersion;?><br/>
            <?= __('Developed by <a href="https://www.monext.fr/retail" target="#">Monext</a> for WooCommerce', 'payline') ?><br/>
            <?= __('For any question please contact Monext support', 'payline') ?><br/>
        </p>

        <nav>
            <ul>
                <li><a href="<?= admin_url('admin.php?page=wc-settings&tab=checkout&section=payline') ?>" class=""><?= $title['payline'] ?></a></li>
                <li><a class="<?php if($section === 'payline_cpt'): ?>payline_active_link <?php endif; ?>" href="<?= admin_url('admin.php?page=wc-settings&tab=checkout&section=payline_cpt') ?>"><?= $title['payline_cpt'] ?></a></li>
                <li><a class="<?php if($section === 'payline_rec'): ?>payline_active_link <?php endif; ?>" href="<?= admin_url('admin.php?page=wc-settings&tab=checkout&section=payline_rec') ?>"><?= $title['payline_rec'] ?></a></li>
                <li><a class="<?php if($section === 'payline_nx'): ?>payline_active_link <?php endif; ?>" href="<?= admin_url('admin.php?page=wc-settings&tab=checkout&section=payline_nx') ?>"><?= $title['payline_nx'] ?></a></li>
            </ul>
        </nav>
    </div>

    <?php if (!empty($reset_message)) : ?>
        <div class='inline updated'><p><?= $reset_message ?></p></div>
    <?php endif; ?>


    <?php if (!empty($errors)) : ?>
        <div class='inline error'>
            <p><?= implode('</p><p>', $errors) ?></p>
        </div>
    <?php endif; ?>

    <?php if (!empty($confirmations)) : ?>
        <div class='inline updated'>
            <p><?= implode('</p><p>', $confirmations) ?></p>
        </div>
    <?php endif; ?>

    <?php if ($section === 'payline_rec' || $section === 'payline_nx') : ?>
        <div class='inline notice notice-info'><strong>URL notification : </strong><?= $this->get_request_url('notification') ?></div>
    <?php endif; ?>

    <nav class="nav-tab-wrapper woo-nav-tab-wrapper" id="settingsFormNav" style="margin-top: 35px;"></nav>

    <table class="form-table" id="advancedSettings">
        <?php echo $this->generate_settings_html() ?>
    </table>

</div>

<script>
    const settingsFormTitles = document.querySelectorAll("#mainform h3");
    function slugify(texte) {
        return texte
            .toString()
            .toLowerCase()
            .trim()
            .replace(/\s+/g, '-')
            .replace(/[^\w\-]/g, '')
            .replace(/\-\-+/g, '-')
            .replace(/^-+/, '')
            .replace(/-+$/, '');
    }

    const getNextSibling = (element) => {
        let nextSibling = element.nextElementSibling;
        let retVal = null;

        while (nextSibling) {
            if (nextSibling.tagName === 'TABLE') {
                retVal = nextSibling;
                break;
            }
            nextSibling = nextSibling.nextElementSibling;
        }

        return retVal;
    }

    // Création des onglets
    settingsFormTitles.forEach(title => {
        const titleText = title.innerText;
        const titleSlug = slugify(titleText);

        const nextTable = getNextSibling(title);

        if (nextTable) {
            nextTable.style.display = "none";
            nextTable.setAttribute("id", titleSlug);
            title.style.display = "none";
        }

        const link = document.createElement('a');
        link.href = '#'; // Remplacez '#' par l'URL souhaitée
        link.textContent = title.innerText;
        link.setAttribute('data-tab', titleSlug)
        link.classList.add("nav-tab");

        link.addEventListener('click', e => {
            e.preventDefault();
            const tableID = e.target.dataset.tab;
            document.querySelectorAll("#mainform table.form-table:not([id='" + tableID + "'])").forEach(table => {
                table.style.display = "none";
            });

            document.querySelectorAll("#settingsFormNav .nav-tab-active").forEach(link => {
                link.classList.remove('nav-tab-active');
            });

            e.target.classList.add("nav-tab-active");

            const formTable = document.getElementById(tableID)
            if (formTable) {
                formTable.style.display = ""
            }
        });

        var settingsFormNav = document.getElementById('settingsFormNav');
        if (settingsFormNav) {
            settingsFormNav.appendChild(link);
        }

        document.querySelector("#settingsFormNav a:first-child").click();
    });

    function adjustHexColor(hex, amount, lighten) {
        hex = hex.replace(/^#/, '');
        if (hex.length === 3) {
            hex = hex.split('').map(x => x + x).join('');
        }
        let num = parseInt(hex, 16);
        let r = (num >> 16) & 0xFF;
        let g = (num >> 8) & 0xFF;
        let b = num & 0xFF;

        if (lighten) {
            amount = 1 + (amount / 100);
        } else {
            amount = 1 - (amount / 100);
        }


        r = Math.min(255, Math.round(r * amount));
        g = Math.min(255, Math.round(g * amount));
        b = Math.min(255, Math.round(b * amount));

        return '#' + [r, g, b].map(x => x.toString(16).padStart(2, '0')).join('');
    }

    const previewContainer = document.getElementById("paylineCtaPreviewContainer");
    const previewButton = document.getElementById('buttonPreview');
    const previewTextUnderCta = document.querySelector('#paylineCtaPreviewContainer p');
    const inputCtaText = document.getElementById("woocommerce_payline_cpt_widget_settings_cta_label");
    const ctaBgColorSelect = document.getElementById("woocommerce_payline_cpt_widget_settings_css_cta_bg_color");
    const ctaBgColorCustom = document.getElementById("woocommerce_payline_cpt_widget_settings_css_cta_bg_color_custom");
    const ctaHoverDarkerSelect = document.getElementById("woocommerce_payline_cpt_widget_settings_css_cta_bg_color_hover_darker");
    const ctaHoverLighterSelect = document.getElementById("woocommerce_payline_cpt_widget_settings_css_cta_bg_color_hover_lighter");
    const ctaColorSelect = document.getElementById("woocommerce_payline_cpt_widget_settings_css_cta_text_color");
    const ctaFontSizeSelect = document.getElementById("woocommerce_payline_cpt_widget_settings_css_font_size");
    const ctaBorderRadiusSelect = document.getElementById("woocommerce_payline_cpt_widget_settings_css_border_radius");
    const ctaTextUnder = document.getElementById("woocommerce_payline_cpt_widget_settings_text_under_cta");
    const widgetContainerBgColorSelect = document.getElementById("woocommerce_payline_cpt_widget_settings_css_bg_color");


    const eventsListeners = [
        {
            type : 'blur',
            elements : [inputCtaText, ctaTextUnder]
        },
        {
            type : 'change',
            elements: [ctaBgColorSelect, ctaColorSelect, ctaFontSizeSelect, ctaBorderRadiusSelect, widgetContainerBgColorSelect]
        }
    ];

    eventsListeners.forEach(evtListener => {
        evtListener.elements.forEach(evtListenerElement => {
            if (evtListenerElement) {
                evtListenerElement.addEventListener(evtListener.type, e => {
                    updateWidgetPreview();
                });
            }
        })
    })

    //--> Toggle CTA custom color
    const toggleCustomColorField = function (focusField = false) {
        if (ctaBgColorSelect && ctaBgColorCustom) {
            const isCustom = ctaBgColorSelect.value === 'custom';
            ctaBgColorCustom.closest('tr').style.display = isCustom ? '' : 'none';

            if (focusField) {
                ctaBgColorCustom.focus();
            }
        }
    }

    const ctaBgColorSelectToggle = document.getElementById("woocommerce_payline_cpt_widget_settings_css_cta_bg_color");
    if (ctaBgColorSelectToggle) {
        ctaBgColorSelectToggle.addEventListener('change', e => {
            toggleCustomColorField(true);
        });
    }

    toggleCustomColorField();


    //--> Listen for color picker changes without remove previous iris change event
    const colorpickpreview = document.querySelector('.colorpickpreview');
    if (colorpickpreview) {
        const observer = new MutationObserver((mutationsList) => {
            for (const mutation of mutationsList) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                    updateWidgetPreview();
                }
            }
        });

        observer.observe(colorpickpreview, { attributes: true, attributeFilter: ['style'] });
    }

    //--> Toggle Widget Settings
    const widgetSettingsToggle = document.getElementById('woocommerce_payline_cpt_widget_settings_customize');
    if (widgetSettingsToggle) {

        const widgetSettingsNodes = Array.from(document.querySelectorAll("[id^='woocommerce_payline_cpt_widget_settings_']")).filter(node => node !== widgetSettingsToggle);
        const previewContainer = document.getElementById("paylineCtaPreviewContainer");
        if (previewContainer) {
            widgetSettingsNodes.push(previewContainer);
        }
        
        const toggleFieldsSettings = (nodeList) => {
            const isChecked = widgetSettingsToggle.checked;
            nodeList.forEach(node => {
                node.closest('tr').style.display = isChecked ? '' : 'none';
            });

            if (widgetSettingsToggle.checked === true) {
                toggleCustomColorField();
            }
        }

        widgetSettingsToggle.addEventListener('change', e => {
            toggleFieldsSettings(widgetSettingsNodes);
        });

        toggleFieldsSettings(widgetSettingsNodes);
    }

    //--> Prevent click on preview Button
    if (previewButton) {
        previewButton.addEventListener('click', e => {
            e.preventDefault();
            return false;
        })
    }

    const getCtaBgColor = () => {
        let ctaBgColor = '#26A434';
        if (ctaBgColorSelect) {
            const newBgCtaColor = ctaBgColorSelect.value.trim();
            if (newBgCtaColor && newBgCtaColor !== 'custom') {
                ctaBgColor = newBgCtaColor;
            }

            if (newBgCtaColor === 'custom' && ctaBgColorCustom) {
                const customColorValue = typeof jQuery(ctaBgColorCustom).iris === 'function' ? jQuery(ctaBgColorCustom).iris('color') : ctaBgColorCustom.value.trim();
                if (customColorValue) {
                    ctaBgColor = customColorValue;
                }
            }
        }

        return ctaBgColor;
    }

    //--> Preview du bouton
    const updateWidgetPreview = () => {

        if (!previewContainer || !previewButton) {
            return;
        }

        //--> Update button text
        let buttonText = "Payer par carte";

        if (inputCtaText) {
            const newValue = inputCtaText.value.trim().replace('{{amount}}', '155.25 EUR');
            if (newValue) {
                buttonText = newValue;
            }
        }
        previewButton.innerText = buttonText;

        //--> Test under CTA
        let textUnderCta = '';
        if (ctaTextUnder) {
            const newTextUnderCta = ctaTextUnder.value.trim().replace('{{amount}}', '155.25 EUR');
            if (newTextUnderCta) {
                textUnderCta = newTextUnderCta;
            }
        }
        previewTextUnderCta.innerText = textUnderCta;

        //--> Cta BG Color
        previewButton.style.backgroundColor = getCtaBgColor();

        //--> Text color
        let ctaColor = '#fff';
        if (ctaColorSelect) {
            const newCtaColor = ctaColorSelect.value.trim();
            if (newCtaColor) {
                ctaColor = newCtaColor;
            }
        }
        previewButton.style.color = ctaColor;

        //--> font Size
        let ctaFontSize = '18px';
        const fontSizes = {
            'small' : '14px',
            'average' : '20px',
            'big' : '24px',
        }
        if (ctaFontSizeSelect) {
            const newCtaFontSize = ctaFontSizeSelect.value.trim();
            if (newCtaFontSize) {
                ctaFontSize = fontSizes[newCtaFontSize];
            }
        }
        previewButton.style.fontSize = ctaFontSize;

        //--> Border Radius
        let ctaBorderRadius = '6px';
        const bordersRadius = {
            'none' : '0',
            'small' : '3px',
            'average' : '8px',
            'big' : '24px'
        }
        if (ctaBorderRadiusSelect) {
            const newCtaBorderRadius = ctaBorderRadiusSelect.value.trim();
            if (newCtaBorderRadius) {
                ctaBorderRadius = bordersRadius[newCtaBorderRadius];
            }
        }

        previewButton.style.borderRadius = ctaBorderRadius;

        //--> Container background color
        let widgetContainerBgColor = '#f8f8f8';
        const widgetContainerBgColors = {
            'lighter' : '#fefefe',
            'darker' : '#dfdfdf'
        }
        if (widgetContainerBgColorSelect) {
            const newWidgetContainerBgColor = widgetContainerBgColorSelect.value.trim();
            if (newWidgetContainerBgColor) {
                widgetContainerBgColor = widgetContainerBgColors[newWidgetContainerBgColor];
            }
        }

        previewContainer.style.backgroundColor = widgetContainerBgColor;
    }

    if (previewButton) {
        //--> Couleur du hover
        previewButton.addEventListener('mouseover', function() {
            let hoverCtaBgColor = '#1c7b27';
            let isLighter = true;
            let amount = 0;

            //--> Darker version
            if (ctaHoverDarkerSelect) {
                const darkerAmountValue = ctaHoverDarkerSelect.value.trim();
                if (darkerAmountValue > 0) {
                    amount = parseInt(darkerAmountValue);
                    hoverCtaBgColor = getCtaBgColor();
                    isLighter = false;
                }

            }

            //--> Lighter version
            if (ctaHoverLighterSelect) {
                const lighterAmountValue = ctaHoverLighterSelect.value.trim();
                if (lighterAmountValue > 0) {
                    amount = parseInt(lighterAmountValue);
                    hoverCtaBgColor = getCtaBgColor();
                    isLighter = true;
                }
            }


            previewButton.style.backgroundColor = adjustHexColor(hoverCtaBgColor, amount, isLighter); // couleur de hover
        });

        previewButton.addEventListener('mouseout', function() {
            previewButton.style.backgroundColor = getCtaBgColor(); // couleur normale
        });
    }



    updateWidgetPreview();

    //--> Contracts select auto adjust height
    const contractsSelect = [
        document.getElementById("woocommerce_payline_cpt_primary_contracts"),
        document.getElementById("woocommerce_payline_rec_primary_contracts"),
        document.getElementById("woocommerce_payline_nx_primary_contracts"),
    ];

    contractsSelect.forEach(c => {
        if (c) {
            const nbOptions = c.querySelectorAll('option').length
            if ( nbOptions > 19 ) {
                c.setAttribute('size', 19);
            } else {
                c.setAttribute('size', nbOptions);
            }
        }
    });

</script>