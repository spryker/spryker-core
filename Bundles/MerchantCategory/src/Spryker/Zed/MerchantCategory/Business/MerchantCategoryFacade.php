<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCategory\Business;

use Generated\Shared\Transfer\MerchantCategoryCriteriaTransfer;
use Generated\Shared\Transfer\MerchantCategoryResponseTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\MerchantCategory\Business\MerchantCategoryBusinessFactory getFactory()
 * @method \Spryker\Zed\MerchantCategory\Persistence\MerchantCategoryRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantCategory\Persistence\MerchantCategoryEntityManagerInterface getEntityManager()
 */
class MerchantCategoryFacade extends AbstractFacade implements MerchantCategoryFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantCategoryCriteriaTransfer $merchantCategoryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCategoryResponseTransfer
     */
    public function get(MerchantCategoryCriteriaTransfer $merchantCategoryCriteriaTransfer): MerchantCategoryResponseTransfer
    {
        return $this->getFactory()
            ->createMerchantCategoryReader()
            ->get($merchantCategoryCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $transfers
     *
     * @return void
     */
    public function publishMerchantCategoryEventsByCategoryEvents(array $transfers): void
    {
        $this->getFactory()
            ->createMerchantCategoryPublisher()
            ->publishMerchantCategoryEventsByCategoryEvents($transfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantCategoryCriteriaTransfer $merchantCategoryCriteriaTransfer
     *
     * @return void
     */
    public function delete(MerchantCategoryCriteriaTransfer $merchantCategoryCriteriaTransfer): void
    {
        $this
            ->getFactory()
            ->createMerchantCategoryDeleter()
            ->delete($merchantCategoryCriteriaTransfer);
    }
}
