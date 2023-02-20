<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchHttp\Communication\Plugin\MessageBroker;

use Generated\Shared\Transfer\SearchEndpointRemovedTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageHandlerPluginInterface;

/**
 * @method \Spryker\Zed\SearchHttp\Business\SearchHttpFacadeInterface getFacade()
 * @method \Spryker\Zed\SearchHttp\Communication\SearchHttpCommunicationFactory getFactory()
 * @method \Spryker\Zed\SearchHttp\SearchHttpConfig getConfig()
 */
class SearchEndpointRemovedMessageHandlerPlugin extends AbstractPlugin implements MessageHandlerPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchEndpointRemovedTransfer $searchEndpointRemovedTransfer
     *
     * @return void
     */
    public function onSearchEndpointRemoved(SearchEndpointRemovedTransfer $searchEndpointRemovedTransfer): void
    {
        $this->getFacade()
            ->unpublishSearchHttpConfig(
                $searchEndpointRemovedTransfer->getMessageAttributesOrFail()->getStoreReferenceOrFail(),
                $searchEndpointRemovedTransfer->getMessageAttributesOrFail()->getEmitterOrFail(),
            );
    }

    /**
     * {@inheritDoc}
     * - Removes search endpoint from the config for the store.
     * - Returns an array where the key is the class name to be handled and the value is the callable that handles the message.
     *
     * @api
     *
     * @return array<string, callable>
     */
    public function handles(): iterable
    {
        return [SearchEndpointRemovedTransfer::class => [$this, 'onSearchEndpointRemoved']];
    }
}
