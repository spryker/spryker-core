<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerGroup\Communication\Form\DataProvider;

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
     * @return array
     */
    public function getData($idCustomerGroup = null)
    {
        if ($idCustomerGroup === null) {
            return [];
        }

        $customerGroupEntity = $this
            ->customerGroupQueryContainer
            ->queryCustomerGroupById($idCustomerGroup)
            ->findOne();

        return $customerGroupEntity->toArray();
    }

    /**
     * @return array
     */
    public function getOptions($id = null)
    {
        return [];
    }

    /**
     * @return array
     */
    public function getAssignedUsers()
    {

    }

    /**
     * @return array
     */
    public function getAssignableUsers()
    {

    }

}
