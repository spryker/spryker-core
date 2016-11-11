<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerGroup\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CustomerGroupToCustomerTransfer;
use Generated\Shared\Transfer\CustomerGroupTransfer;
use Orm\Zed\CustomerGroup\Persistence\Map\SpyCustomerGroupToCustomerTableMap;

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

        $data = $customerGroupEntity->toArray();

        $customersArray = $this
            ->customerGroupQueryContainer
            ->queryCustomerGroupToCustomerByFkCustomerGroup($idCustomerGroup)
            ->select(SpyCustomerGroupToCustomerTableMap::COL_FK_CUSTOMER)
            ->find()->toArray();

        $data['customers'] = $customersArray;

        return $data;
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

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\CustomerGroupTransfer
     */
    public function prepareDataAsTransfer(array $data)
    {
        $customerGroupTransfer = new CustomerGroupTransfer();
        $customers = $data['customers'];
        unset($data['customers']);

        $customerGroupTransfer->fromArray($data, true);
        $this->addCustomers($customerGroupTransfer, $customers);

        return $customerGroupTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerGroupTransfer $customerGroupTransfer
     * @param array $idCustomers
     *
     * @return void
     */
    protected function addCustomers(CustomerGroupTransfer $customerGroupTransfer, array $idCustomers)
    {
        foreach ($idCustomers as $idCustomer) {
            $customerTransfer = new CustomerGroupToCustomerTransfer();
            $customerTransfer->setFkCustomer($idCustomer);
            $customerGroupTransfer->addCustomer($customerTransfer);
        }
    }

}
