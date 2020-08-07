<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationStorage\Communication\Plugin\Publisher;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\ProductConfigurationFilterTransfer;
use Spryker\Shared\ProductConfigurationStorage\ProductConfigurationStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherTriggerPluginInterface;

/**
 * @method \Spryker\Zed\ProductConfigurationStorage\Business\ProductConfigurationStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductConfigurationStorage\ProductConfigurationStorageConfig getConfig()
 */
class ProductConfigurationPublisherTriggerPlugin extends AbstractPlugin implements PublisherTriggerPluginInterface
{
    /**
     * @uses \Orm\Zed\ProductConfiguration\Persistence\Map\SpyProductConfigurationTableMap::COL_ID_PRODUCT_CONFIGURATIO
     */
    protected const COL_ID_PRODUCT_CONFIGURATION = 'spy_product_configuration.id_product_configuration';

    /**
     * @uses \Orm\Zed\ProductConfiguration\Persistence\Map\SpyProductConfigurationTableMap::COL_FK_PRODUCT
     */
    protected const COL_FK_PRODUCT = 'spy_product_configuration.fk_product';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getResourceName(): string
    {
        return ProductConfigurationStorageConfig::PRODUCT_CONFIGURATION_RESOURCE_NAME;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $offset
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationTransfer[]
     */
    public function getData(int $offset, int $limit): array
    {
        $criteria = new ProductConfigurationFilterTransfer();
        $filter = new FilterTransfer();

        $criteria->setFilter(
            $filter->setLimit($limit)->setOffset($offset)
        );

        return (array)$this->getFacade()->getProductConfigurationCollection($criteria)->getProductConfigurations();
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
        return ProductConfigurationStorageConfig::PRODUCT_CONFIGURATION_PUBLISH;
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
        return static::COL_ID_PRODUCT_CONFIGURATION;
    }
}
