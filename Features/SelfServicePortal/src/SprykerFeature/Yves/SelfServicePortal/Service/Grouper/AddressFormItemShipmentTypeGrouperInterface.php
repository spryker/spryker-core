<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Service\Grouper;

use Symfony\Component\Form\FormView;

interface AddressFormItemShipmentTypeGrouperInterface
{
    /**
     * @param \Symfony\Component\Form\FormView $checkoutAddressForm
     *
     * @return array<string, array<string, list<\Symfony\Component\Form\FormView>>>
     */
    public function groupItemsByShipmentType(FormView $checkoutAddressForm): array;
}
