<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;
use SprykerFeature\Yves\SelfServicePortal\Service\Form\SingleAddressPerShipmentTypeAddressStepForm;
use Symfony\Component\Form\FormView;

/**
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalFactory getFactory()
 */
class SingleAddressPerShipmentTypeWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const PARAMETER_IS_VISIBLE = 'isVisible';

    /**
     * @var string
     */
    protected const PARAMETER_SINGLE_ADDRESS_PER_SHIPMENT_TYPE_FORM_FIELD = 'singleAddressPerShipmentTypeFormField';

    /**
     * @param \Symfony\Component\Form\FormView $checkoutAddressForm
     */
    public function __construct(FormView $checkoutAddressForm)
    {
        $this->addIsVisibleParameter($checkoutAddressForm);
        $this->addFormViewParameter($checkoutAddressForm);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'SingleAddressPerShipmentTypeWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@SelfServicePortal/views/single-address-per-shipment-type/single-address-per-shipment-type.twig';
    }

    /**
     * @param \Symfony\Component\Form\FormView $checkoutAddressForm
     *
     * @return void
     */
    protected function addIsVisibleParameter(FormView $checkoutAddressForm): void
    {
        $isVisible = isset($checkoutAddressForm->children[SingleAddressPerShipmentTypeAddressStepForm::FIELD_IS_SINGLE_ADDRESS_PER_SHIPMENT_TYPE]);

        $this->addParameter(static::PARAMETER_IS_VISIBLE, $isVisible);
    }

    /**
     * @param \Symfony\Component\Form\FormView $checkoutAddressForm
     *
     * @return void
     */
    protected function addFormViewParameter(FormView $checkoutAddressForm): void
    {
        $this->addParameter(
            static::PARAMETER_SINGLE_ADDRESS_PER_SHIPMENT_TYPE_FORM_FIELD,
            $checkoutAddressForm->children[SingleAddressPerShipmentTypeAddressStepForm::FIELD_IS_SINGLE_ADDRESS_PER_SHIPMENT_TYPE] ?? null,
        );
    }
}
