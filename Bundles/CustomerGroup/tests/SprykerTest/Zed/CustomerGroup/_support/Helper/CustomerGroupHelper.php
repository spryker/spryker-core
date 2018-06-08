<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CustomerGroup\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CustomerGroupBuilder;
use Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroupQuery;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class CustomerGroupHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\CustomerGroupTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function haveCustomerGroup(array $seed = [])
    {
        $customerGroupTransfer = (new CustomerGroupBuilder($seed))->build();

        $customerGroupFacade = $this->getCustomerGroupFacade();
        $customerGroupTransfer = $customerGroupFacade->add($customerGroupTransfer);

        $this->getDataCleanupHelper()->_addCleanup(function () use ($customerGroupTransfer) {
            $this->cleanupCustomerGroup($customerGroupTransfer->getIdCustomerGroup());
        });

        return $customerGroupTransfer;
    }

    /**
     * @return \Spryker\Zed\CustomerGroup\Business\CustomerGroupFacadeInterface
     */
    private function getCustomerGroupFacade()
    {
        return $this->getLocator()->customerGroup()->facade();
    }

    /**
     * @param int $idCustomerGroup
     *
     * @return void
     */
    private function cleanupCustomerGroup($idCustomerGroup)
    {
        $this->debug(sprintf('Deleting CustomerGroup: %d', $idCustomerGroup));

        $this->getCustomerGroupQuery()
            ->findByIdCustomerGroup($idCustomerGroup)
            ->delete();
    }

    /**
     * @return \Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroupQuery
     */
    private function getCustomerGroupQuery()
    {
        return SpyCustomerGroupQuery::create();
    }
}
