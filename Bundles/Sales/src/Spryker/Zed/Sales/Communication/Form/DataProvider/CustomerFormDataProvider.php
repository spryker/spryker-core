<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Communication\Form\DataProvider;

use Spryker\Zed\Sales\Communication\Form\CustomerForm;

class CustomerFormDataProvider extends AbstractSalesFormDataProvider
{

    /**
     * @param int $idSalesOrder
     *
     * @return array
     */
    public function getData($idSalesOrder)
    {
        $order = $this
            ->salesQueryContainer
            ->querySalesOrderById($idSalesOrder)
            ->findOne();

        return [
            CustomerForm::FIELD_FIRST_NAME => $order->getFirstName(),
            CustomerForm::FIELD_LAST_NAME => $order->getLastName(),
            CustomerForm::FIELD_SALUTATION => $order->getSalutation(),
            CustomerForm::FIELD_EMAIL => $order->getEmail(),
        ];
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            CustomerForm::OPTION_SALUTATION_CHOICES => $this->getSalutationOptions(),
        ];
    }

}
