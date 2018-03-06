<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderCreationGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ManualOrderEntryTransfer;
use Spryker\Zed\ManualOrderCreationGui\Dependency\QueryContainer\ManualOrderCreationGuiToCustomerQueryContainerInterface;

class CheckoutFormDataProvider
{
    /**
     * @var \Spryker\Zed\ManualOrderCreationGui\Dependency\QueryContainer\ManualOrderCreationGuiToCustomerQueryContainerInterface
     */
    protected $customerQueryContainer;

    /**
     * @param \Spryker\Zed\ManualOrderCreationGui\Dependency\QueryContainer\ManualOrderCreationGuiToCustomerQueryContainerInterface $customerQueryContainer
     */
    public function __construct(
        ManualOrderCreationGuiToCustomerQueryContainerInterface $customerQueryContainer
    ) {
        $this->customerQueryContainer = $customerQueryContainer;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            'data_class' => ManualOrderEntryTransfer::class
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\ManualOrderEntryTransfer
     */
    public function getData()
    {
        return new ManualOrderEntryTransfer();
    }

}
