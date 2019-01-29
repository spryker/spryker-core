<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Form\DataProvider;

class ShipmentFormCreateDataProvider extends AbstractShipmentFormDataProvider
{
    /**
     * @param int $idSalesOrder
     * @param int|null $idSalesShipment
     *
     * @return array
     */
    public function getData(int $idSalesOrder, ?int $idSalesShipment = null)
    {
        $formData = [];
        $defaults = $this->getDefaultFormFields($idSalesOrder, $idSalesShipment);

        $formData = array_merge($defaults, $formData);

        return $formData;
    }
}
