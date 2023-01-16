<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedStorage\Communication\Plugin\Publisher;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedCriteriaTransfer;
use Spryker\Shared\ProductDiscontinuedStorage\ProductDiscontinuedStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherTriggerPluginInterface;

/**
 * @method \Spryker\Zed\ProductDiscontinuedStorage\Business\ProductDiscontinuedStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductDiscontinuedStorage\Communication\ProductDiscontinuedStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductDiscontinuedStorage\ProductDiscontinuedStorageConfig getConfig()
 */
class ProductDiscontinuedPublisherTriggerPlugin extends AbstractPlugin implements PublisherTriggerPluginInterface
{
    /**
     * @uses \Orm\Zed\ProductDiscontinued\Persistence\Map\SpyProductDiscontinuedTableMap::COL_ID_PRODUCT_DISCONTINUED
     *
     * @var string
     */
    protected const COL_ID_PRODUCT_DISCONTINUED = 'spy_product_discontinued.id_product_discontinued';

    /**
     * {@inheritDoc}
     * - Retrieves collection of discontinued products by offset and limit from Persistence.
     *
     * @api
     *
     * @param int $offset
     * @param int $limit
     *
     * @return array<\Generated\Shared\Transfer\AssetTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer>
     */
    public function getData(int $offset, int $limit): array
    {
        $productDiscontinuedCriteriaTransfer = $this->createProductDiscontinuedCriteriaTransfer($offset, $limit);

        return $this->getFactory()
            ->getProductDiscontinuedFacade()
            ->getProductDiscontinuedCollection($productDiscontinuedCriteriaTransfer)
            ->getDiscontinuedProducts()->getArrayCopy();
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
        return ProductDiscontinuedStorageConfig::PRODUCT_DISCONTINUED_RESOURCE_NAME;
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
        return ProductDiscontinuedStorageConfig::PRODUCT_DISCONTINUED_PUBLISH;
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
        return static::COL_ID_PRODUCT_DISCONTINUED;
    }

    /**
     * @param int $offset
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedCriteriaTransfer
     */
    protected function createProductDiscontinuedCriteriaTransfer(int $offset, int $limit): ProductDiscontinuedCriteriaTransfer
    {
        return (new ProductDiscontinuedCriteriaTransfer())
            ->setPagination(
                (new PaginationTransfer())->setLimit($limit)->setOffset($offset),
            );
    }
}
