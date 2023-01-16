<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeStorage\Communication\Plugin\Publisher;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductAlternativeCriteriaTransfer;
use Spryker\Shared\ProductAlternativeStorage\ProductAlternativeStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherTriggerPluginInterface;

/**
 * @method \Spryker\Zed\ProductAlternativeStorage\Persistence\ProductAlternativeStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductAlternativeStorage\Business\ProductAlternativeStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductAlternativeStorage\Communication\ProductAlternativeStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductAlternativeStorage\ProductAlternativeStorageConfig getConfig()
 */
class ProductAlternativePublisherTriggerPlugin extends AbstractPlugin implements PublisherTriggerPluginInterface
{
    /**
     * @uses \Orm\Zed\Product\Persistence\Map\SpyProductTableMap::COL_ID_PRODUCT
     *
     * @var string
     */
    protected const COL_ID_PRODUCT = 'spy_product.id_product';

    /**
     * {@inheritDoc}
     * - Retrieves collection of product alternatives by offset and limit from Persistence.
     *
     * @api
     *
     * @param int $offset
     * @param int $limit
     *
     * @return array<\Spryker\Shared\Kernel\Transfer\AbstractTransfer>
     */
    public function getData(int $offset, int $limit): array
    {
        $productAlternativeCriteriaTransfer = $this->createProductAlternativeCriteriaTransfer($offset, $limit);

        return $this->getFactory()
            ->getProductAlternativeFacade()
            ->getProductAlternativeCollection($productAlternativeCriteriaTransfer)
            ->getProductAlternatives()->getArrayCopy();
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
        return ProductAlternativeStorageConfig::PRODUCT_ALTERNATIVE_RESOURCE_NAME;
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
        return ProductAlternativeStorageConfig::PRODUCT_ALTERNATIVE_PUBLISH;
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
        return static::COL_ID_PRODUCT;
    }

    /**
     * @param int $offset
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeCriteriaTransfer
     */
    protected function createProductAlternativeCriteriaTransfer(int $offset, int $limit): ProductAlternativeCriteriaTransfer
    {
        return (new ProductAlternativeCriteriaTransfer())->setPagination(
            (new PaginationTransfer())->setOffset($offset)->setLimit($limit),
        );
    }
}
