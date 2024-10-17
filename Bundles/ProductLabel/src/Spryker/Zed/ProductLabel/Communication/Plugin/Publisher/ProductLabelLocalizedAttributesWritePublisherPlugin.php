<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Communication\Plugin\Publisher;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface;

/**
 * @method \Spryker\Zed\ProductLabel\ProductLabelConfig getConfig()
 * @method \Spryker\Zed\ProductLabel\Business\ProductLabelFacadeInterface getFacade()
 */
class ProductLabelLocalizedAttributesWritePublisherPlugin extends AbstractPlugin implements PublisherPluginInterface
{
    /**
     * @uses \Spryker\Shared\StoreStorage\StoreStorageConfig::ENTITY_SPY_LOCALE_STORE_CREATE
     *
     * @var string
     */
    protected const ENTITY_SPY_LOCALE_STORE_CREATE = 'Entity.spy_locale_store.create';

    /**
     * @uses \Spryker\Shared\ProductLabelStorage\ProductLabelStorageConfig::ENTITY_SPY_PRODUCT_LABEL_CREATE
     *
     * @var string
     */
    protected const ENTITY_SPY_PRODUCT_LABEL_CREATE = 'Entity.spy_product_label.create';

    /**
     * @uses \Spryker\Shared\ProductLabelStorage\ProductLabelStorageConfig::ENTITY_SPY_PRODUCT_LABEL_UPDATE
     *
     * @var string
     */
    protected const ENTITY_SPY_PRODUCT_LABEL_UPDATE = 'Entity.spy_product_label.update';

    /**
     * {@inheritDoc}
     * - Finds all existing product label localized attributes entries if Dynamic Store is enabled.
     * - Creates missing product label localized attributes.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $transfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $transfers, $eventName): void
    {
        $this->getFacade()->createMissingLocalizedAttributes();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array<string>
     */
    public function getSubscribedEvents(): array
    {
        return [
            static::ENTITY_SPY_LOCALE_STORE_CREATE,
            static::ENTITY_SPY_PRODUCT_LABEL_CREATE,
            static::ENTITY_SPY_PRODUCT_LABEL_UPDATE,
        ];
    }
}
