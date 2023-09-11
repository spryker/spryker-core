<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchHttp\Communication\Plugin\MessageBroker;

use Generated\Shared\Transfer\SearchEndpointAvailableTransfer;
use Generated\Shared\Transfer\SearchHttpConfigTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageHandlerPluginInterface;

/**
 * @deprecated Use {@link \Spryker\Zed\SearchHttp\Communication\Plugin\MessageBroker\SearchEndpointMessageHandlerPlugin} instead.
 *
 * @method \Spryker\Zed\SearchHttp\Business\SearchHttpFacadeInterface getFacade()
 * @method \Spryker\Zed\SearchHttp\Communication\SearchHttpCommunicationFactory getFactory()
 * @method \Spryker\Zed\SearchHttp\SearchHttpConfig getConfig()
 */
class SearchEndpointAvailableMessageHandlerPlugin extends AbstractPlugin implements MessageHandlerPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchEndpointAvailableTransfer $searchEndpointAvailableTransfer
     *
     * @return void
     */
    public function onSearchEndpointActive(SearchEndpointAvailableTransfer $searchEndpointAvailableTransfer): void
    {
        $searchHttpConfigTransfer = (new SearchHttpConfigTransfer())
            ->setApplicationid($searchEndpointAvailableTransfer->getMessageAttributesOrFail()->getEmitterOrFail())
            ->setUrl($searchEndpointAvailableTransfer->getUrl())
            ->setSuggestionUrl($searchEndpointAvailableTransfer->getSuggestionUrl());

        $this->getFacade()->publishSearchHttpConfig(
            $searchHttpConfigTransfer,
            $searchEndpointAvailableTransfer->getMessageAttributesOrFail()->getStoreReferenceOrFail(),
        );
    }

    /**
     * {@inheritDoc}
     * - Adds new search endpoint to the config for the store.
     * - Returns an array where the key is the class name to be handled and the value is the callable that handles the message.
     *
     * @api
     *
     * @return array<string, callable>
     */
    public function handles(): iterable
    {
        return [SearchEndpointAvailableTransfer::class => [$this, 'onSearchEndpointActive']];
    }
}
