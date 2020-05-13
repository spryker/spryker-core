<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnPageSearch\Communication\Plugin\Publisher;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\ReturnReasonFilterTransfer;
use Spryker\Shared\SalesReturnPageSearch\SalesReturnPageSearchConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherTriggerPluginInterface;

/**
 * @method \Spryker\Zed\SalesReturnPageSearch\SalesReturnPageSearchConfig getConfig()
 * @method \Spryker\Zed\SalesReturnPageSearch\Business\SalesReturnPageSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesReturnPageSearch\Communication\SalesReturnPageSearchCommunicationFactory getFactory()
 */
class ReturnReasonPublisherTriggerPlugin extends AbstractPlugin implements PublisherTriggerPluginInterface
{
    /**
     * @uses \Orm\Zed\SalesReturn\Persistence\Map\SpySalesReturnReasonTableMap::COL_ID_SALES_RETURN_REASON
     */
    protected const COL_ID_SALES_RETURN_REASON = 'spy_sales_return_reason.id_sales_return_reason';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $offset
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\ReturnReasonTransfer[]
     */
    public function getData(int $offset, int $limit): array
    {
        $filterTransfer = (new FilterTransfer())
            ->setOffset($offset)->setLimit($limit);
        $returnReasonFilterTransfer = (new ReturnReasonFilterTransfer())
            ->setFilter($filterTransfer);

        return $this->getFactory()
            ->getSalesReturnFacade()
            ->getReturnReasons($returnReasonFilterTransfer)
            ->getReturnReasons()
            ->getArrayCopy();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getResourceName(): string
    {
        return SalesReturnPageSearchConfig::RETURN_REASON_RESOURCE_NAME;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getEventName(): string
    {
        return SalesReturnPageSearchConfig::RETURN_REASON_PUBLISH_WRITE;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string|null
     */
    public function getIdColumnName(): ?string
    {
        return static::COL_ID_SALES_RETURN_REASON;
    }
}
