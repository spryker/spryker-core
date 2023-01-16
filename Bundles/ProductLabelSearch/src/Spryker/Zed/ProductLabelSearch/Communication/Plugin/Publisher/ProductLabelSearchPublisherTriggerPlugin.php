<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelSearch\Communication\Plugin\Publisher;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductLabelCriteriaTransfer;
use Spryker\Shared\ProductLabelSearch\ProductLabelSearchConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherTriggerPluginInterface;

/**
 * @method \Spryker\Zed\ProductLabelSearch\Communication\ProductLabelSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductLabelSearch\Business\ProductLabelSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductLabelSearch\Persistence\ProductLabelSearchQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductLabelSearch\ProductLabelSearchConfig getConfig()
 */
class ProductLabelSearchPublisherTriggerPlugin extends AbstractPlugin implements PublisherTriggerPluginInterface
{
    /**
     * @uses \Orm\Zed\ProductLabel\Persistence\Map\SpyProductLabelTableMap::COL_ID_PRODUCT_LABEL
     *
     * @var string
     */
    protected const COL_ID_PRODUCT_LABEL = 'spy_product_label.id_product_label';

    /**
     * {@inheritDoc}
     * - Retrieves product labels by provided offset and limit from Persistence.
     *
     * @api
     *
     * @param int $offset
     * @param int $limit
     *
     * @return array<\Generated\Shared\Transfer\ProductLabelTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer>
     */
    public function getData(int $offset, int $limit): array
    {
        $productLabelCriteriaTransfer = $this->createProductLabelCriteriaTransfer($offset, $limit);

        return $this->getFactory()
            ->getProductLabelFacade()
            ->getProductLabelCollection($productLabelCriteriaTransfer)
            ->getProductLabels()
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
        return ProductLabelSearchConfig::PRODUCT_LABEL_RESOURCE_NAME;
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
        return ProductLabelSearchConfig::PRODUCT_LABEL_PUBLISH;
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
        return static::COL_ID_PRODUCT_LABEL;
    }

    /**
     * @param int $offset
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\ProductLabelCriteriaTransfer
     */
    protected function createProductLabelCriteriaTransfer(int $offset, int $limit): ProductLabelCriteriaTransfer
    {
        return (new ProductLabelCriteriaTransfer())
            ->setPagination(
                (new PaginationTransfer())->setOffset($offset)->setLimit($limit),
            );
    }
}
