<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerGroup\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CustomerGroupToCustomerAssignmentTransfer;
use Generated\Shared\Transfer\CustomerGroupTransfer;

class CustomerGroupFormDataProvider
{
    /**
     * @var \Spryker\Zed\CustomerGroup\Persistence\CustomerGroupQueryContainerInterface
     */
    protected $customerGroupQueryContainer;

    /**
     * @param \Spryker\Zed\CustomerGroup\Persistence\CustomerGroupQueryContainerInterface $customerGroupQueryContainer
     */
    public function __construct($customerGroupQueryContainer)
    {
        $this->customerGroupQueryContainer = $customerGroupQueryContainer;
    }

    /**
     * @param int|null $idCustomerGroup
     *
     * @return \Generated\Shared\Transfer\CustomerGroupTransfer
     */
    public function getData($idCustomerGroup = null)
    {
        if ($idCustomerGroup === null) {
            return (new CustomerGroupTransfer())
                ->setCustomerAssignment(new CustomerGroupToCustomerAssignmentTransfer());
        }

        $customerGroupEntity = $this
            ->customerGroupQueryContainer
            ->queryCustomerGroupById($idCustomerGroup)
            ->findOne();
        if (!$customerGroupEntity) {
            return new CustomerGroupTransfer();
        }

        $customerGroupTransfer = (new CustomerGroupTransfer())
            ->fromArray($customerGroupEntity->toArray(), true)
            ->setCustomerAssignment(new CustomerGroupToCustomerAssignmentTransfer());

        return $customerGroupTransfer;
    }

    /**
     * @param int|null $idCustomerGroup
     *
     * @return array
     */
    public function getOptions($idCustomerGroup = null)
    {
        return [];
    }
}
