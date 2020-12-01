<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Communication\Plugin\Publisher\ProductLabelDictionary;

use Spryker\Shared\ProductLabelStorage\ProductLabelStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface;

/**
 * @method \Spryker\Zed\ProductLabelStorage\Business\ProductLabelStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductLabelStorage\Communication\ProductLabelStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductLabelStorage\ProductLabelStorageConfig getConfig()
 * @method \Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageQueryContainerInterface getQueryContainer()
 */
class ProductLabelDictionaryWritePublisherPlugin extends AbstractPlugin implements PublisherPluginInterface
{
    /**
     * {@inheritDoc}
     * - Publishes product label dictionary data by publish ProductLabelDictionary event.
     * - Publishes product label dictionary data spy_product_label and spy_product_label_localized_attributes entities events.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventEntityTransfers, $eventName): void
    {
        $this->getFacade()->writeProductLabelDictionaryStorageCollection();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string[]
     */
    public function getSubscribedEvents(): array
    {
        return [
            ProductLabelStorageConfig::PRODUCT_LABEL_DICTIONARY_PUBLISH,
            ProductLabelStorageConfig::ENTITY_SPY_PRODUCT_LABEL_CREATE,
            ProductLabelStorageConfig::ENTITY_SPY_PRODUCT_LABEL_UPDATE,
            ProductLabelStorageConfig::ENTITY_SPY_PRODUCT_LABEL_DELETE,
            ProductLabelStorageConfig::ENTITY_SPY_PRODUCT_LABEL_LOCALIZED_ATTRIBUTE_CREATE,
            ProductLabelStorageConfig::ENTITY_SPY_PRODUCT_LABEL_LOCALIZED_ATTRIBUTE_UPDATE,
            ProductLabelStorageConfig::ENTITY_SPY_PRODUCT_LABEL_LOCALIZED_ATTRIBUTE_DELETE,
            ProductLabelStorageConfig::ENTITY_SPY_PRODUCT_LABEL_STORE_CREATE,
            ProductLabelStorageConfig::ENTITY_SPY_PRODUCT_LABEL_STORE_UPDATE,
            ProductLabelStorageConfig::ENTITY_SPY_PRODUCT_LABEL_STORE_DELETE,
        ];
    }
}
