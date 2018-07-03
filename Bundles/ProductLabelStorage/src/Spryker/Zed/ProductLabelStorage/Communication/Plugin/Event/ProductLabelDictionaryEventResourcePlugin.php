<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Communication\Plugin\Event;

use Spryker\Shared\ProductLabelStorage\ProductLabelStorageConfig;
use Spryker\Zed\EventBehavior\Dependency\Plugin\EventResourceQueryContainerPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductLabel\Dependency\ProductLabelEvents;

/**
 * @method \Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductLabelStorage\Business\ProductLabelStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductLabelStorage\Communication\ProductLabelStorageCommunicationFactory getFactory()
 */
class ProductLabelDictionaryEventResourcePlugin extends AbstractPlugin implements EventResourceQueryContainerPluginInterface
{
    /**
     * Specification:
     *  - Returns the name of resource
     *
     * @api
     *
     * @return string
     */
    public function getResourceName(): string
    {
        return ProductLabelStorageConfig::PRODUCT_LABEL_DICTIONARY_RESOURCE_NAME;
    }

    /**
     * Specification:
     *  - Returns query of resource entity, provided $ids parameter
     *    will apply to query to limit the result
     *
     * @api
     *
     * @param int[] $ids
     *
     * @return null
     */
    public function queryData($ids = [])
    {
        return null;
    }

    /**
     * Specification:
     *  - Returns the event name of resource entity
     *
     * @api
     *
     * @return string
     */
    public function getEventName(): string
    {
        return ProductLabelEvents::PRODUCT_LABEL_DICTIONARY_PUBLISH;
    }

    /**
     * Specification:
     *  - Returns the name of ID column for publishing
     *
     * @api
     *
     * @return string
     */
    public function getIdColumnName(): string
    {
        return null;
    }
}
