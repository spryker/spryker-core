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
 * @method \Spryker\Zed\ProductConfigurationStorage\Communication\ProductConfigurationStorageCommunicationFactory getFactory()
 */
class ProductConfigurationPublisherTriggerPlugin extends AbstractPlugin implements PublisherTriggerPluginInterface
{
    /**
     * @uses \Orm\Zed\ProductConfiguration\Persistence\Map\SpyProductConfigurationTableMap::COL_ID_PRODUCT_CONFIGURATION
     *
     * @var string
     */
    protected const COL_ID_PRODUCT_CONFIGURATION = 'spy_product_configuration.id_product_configuration';

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
     * - Retrieves product configurations by provided limit and offset.
     *
     * @api
     *
     * @param int $offset
     * @param int $limit
     *
     * @return array<\Generated\Shared\Transfer\ProductConfigurationTransfer>
     */
    public function getData(int $offset, int $limit): array
    {
        $productConfigurationCollection = $this->getFactory()
            ->getProductConfigurationFacade()
            ->getProductConfigurationCollection(
                $this->createProductConfigurationFilterTransfer($offset, $limit),
            );

        return $productConfigurationCollection->getProductConfigurations()->getArrayCopy();
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

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $offset
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationFilterTransfer
     */
    public function createProductConfigurationFilterTransfer(int $offset, int $limit): ProductConfigurationFilterTransfer
    {
        $productConfigurationFilterTransfer = new ProductConfigurationFilterTransfer();
        $productConfigurationFilterTransfer->setFilter((new FilterTransfer())->setLimit($limit)->setOffset($offset));

        return $productConfigurationFilterTransfer;
    }
}
