<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Communication\Plugin\Publisher;

use Generated\Shared\Transfer\FilterTransfer;
use Orm\Zed\ProductLabel\Persistence\Map\SpyProductLabelProductAbstractTableMap;
use Spryker\Shared\ProductLabelStorage\ProductLabelStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherTriggerPluginInterface;

/**
 * @method \Spryker\Zed\ProductLabelStorage\Business\ProductLabelStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductLabelStorage\Communication\ProductLabelStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductLabelStorage\ProductLabelStorageConfig getConfig()
 * @method \Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageQueryContainerInterface getQueryContainer()
 */
class ProductAbstractLabelPublisherTriggerPlugin extends AbstractPlugin implements PublisherTriggerPluginInterface
{
    /**
     * {@inheritDoc}
     * - Retrieves product label product abstracts by provided limit and offset.
     *
     * @api
     *
     * @param int $offset
     * @param int $limit
     *
     * @return array
     */
    public function getData(int $offset, int $limit): array
    {
        $filterTransfer = $this->createFilterTransfer($offset, $limit);

        return $this->getFactory()
            ->getProductLabelFacade()
            ->getProductLabelProductAbstractsByFilter($filterTransfer);
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
        return ProductLabelStorageConfig::PRODUCT_ABSTRACT_LABEL_RESOURCE_NAME;
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
        return ProductLabelStorageConfig::PRODUCT_LABEL_PRODUCT_ABSTRACT_PUBLISH;
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
        return SpyProductLabelProductAbstractTableMap::COL_FK_PRODUCT_ABSTRACT;
    }

    /**
     * @param int $offset
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\FilterTransfer
     */
    protected function createFilterTransfer(int $offset, int $limit): FilterTransfer
    {
        return (new FilterTransfer())
            ->setOffset($offset)
            ->setLimit($limit);
    }
}
