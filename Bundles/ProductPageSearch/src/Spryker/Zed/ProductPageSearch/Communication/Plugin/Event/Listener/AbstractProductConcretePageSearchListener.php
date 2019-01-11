<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener;

use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @method \Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacadeInterface getFacade()
 */
abstract class AbstractProductConcretePageSearchListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @param int[] $productIds
     *
     * @return void
     */
    protected function publish(array $productIds): void
    {
        $this->getFacade()->publishProductConcretes($productIds);
    }

    /**
     * @param int[] $productIds
     *
     * @return void
     */
    protected function unpublish(array $productIds): void
    {
        $this->getFacade()->unpublishProductConcretes($productIds);
    }
}
