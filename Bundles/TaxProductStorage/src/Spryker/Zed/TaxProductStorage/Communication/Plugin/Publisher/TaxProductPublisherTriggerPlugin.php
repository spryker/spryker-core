<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductStorage\Communication\Plugin\Publisher;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductAbstractCriteriaTransfer;
use Spryker\Shared\TaxProductStorage\TaxProductStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherTriggerPluginInterface;

/**
 * @method \Spryker\Zed\TaxProductStorage\TaxProductStorageConfig getConfig()
 * @method \Spryker\Zed\TaxProductStorage\Persistence\TaxProductStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\TaxProductStorage\Business\TaxProductStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\TaxProductStorage\Communication\TaxProductStorageCommunicationFactory getFactory()
 */
class TaxProductPublisherTriggerPlugin extends AbstractPlugin implements PublisherTriggerPluginInterface
{
    /**
     * @uses \Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT
     *
     * @var string
     */
    protected const COL_ID_PRODUCT_ABSTRACT = 'spy_product_abstract.id_product_abstract';

    /**
     * {@inheritDoc}
     * - Retrieves product abstracts by offset and limit from Persistence.
     *
     * @api
     *
     * @param int $offset
     * @param int $limit
     *
     * @return array<\Generated\Shared\Transfer\ProductAbstractTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer>
     */
    public function getData(int $offset, int $limit): array
    {
        $productAbstractCriteriaTransfer = $this->createProductAbstractCriteria($offset, $limit);

        return $this->getRepository()
            ->getProductAbstractCollection($productAbstractCriteriaTransfer)
            ->getProductAbstracts()
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
        return TaxProductStorageConfig::TAX_PRODUCT_RESOURCE_NAME;
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
        return TaxProductStorageConfig::TAX_PRODUCT_PUBLISH;
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
        return static::COL_ID_PRODUCT_ABSTRACT;
    }

    /**
     * @param int $offset
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\ProductAbstractCriteriaTransfer
     */
    protected function createProductAbstractCriteria(int $offset, int $limit): ProductAbstractCriteriaTransfer
    {
        return (new ProductAbstractCriteriaTransfer())
            ->setPagination(
                (new PaginationTransfer())
                    ->setLimit($limit)
                    ->setOffset($offset),
            );
    }
}
