<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderCreationGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Generated\Shared\Transfer\ManualOrderEntryTransfer;
use Orm\Zed\Category\Persistence\SpyCategory;
use Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryConnector;
use Spryker\Zed\CmsBlockCategoryConnector\Communication\Form\CmsBlockType;
use Spryker\Zed\CmsBlockCategoryConnector\Dependency\Facade\CmsBlockCategoryConnectorToLocaleInterface;
use Spryker\Zed\CmsBlockCategoryConnector\Dependency\QueryContainer\CmsBlockCategoryConnectorToCategoryQueryContainerInterface;
use Spryker\Zed\CmsBlockCategoryConnector\Persistence\CmsBlockCategoryConnectorQueryContainerInterface;
use Spryker\Zed\ManualOrderCreationGui\Communication\Form\Customer\CustomerType;
use Spryker\Zed\ManualOrderCreationGui\Dependency\QueryContainer\ManualOrderCreationGuiToCustomerQueryContainerInterface;

class CustomerDataProvider
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
            'data_class' => ManualOrderEntryTransfer::class,
            CustomerType::OPTION_CUSTOMER_ARRAY => $this->getCustomerList(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\ManualOrderEntryTransfer $manualOrderEntryTransfer
     *
     * @return \Generated\Shared\Transfer\ManualOrderEntryTransfer
     */
    public function getData(ManualOrderEntryTransfer $manualOrderEntryTransfer)
    {
        return $manualOrderEntryTransfer;
    }

    /**
     * @return array
     */
    protected function getCustomerList()
    {
        $customerCollection = $this->customerQueryContainer
            ->queryCustomers()
            ->find();

        $customerList = [];

        /** @var \Orm\Zed\Customer\Persistence\SpyCustomer $customerEntity */
        foreach ($customerCollection as $customerEntity) {
            $customerFieldData = $customerEntity->getLastName()
                . ' '
                . $customerEntity->getFirstName()
                . ' [' . $customerEntity->getEmail() . ']';

            $customerList[$customerEntity->getIdCustomer()] = $customerFieldData;
        }

        return $customerList;
    }

}
