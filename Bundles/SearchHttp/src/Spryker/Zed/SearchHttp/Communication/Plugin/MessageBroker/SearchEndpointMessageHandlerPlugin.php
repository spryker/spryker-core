<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchHttp\Communication\Plugin\MessageBroker;

use Generated\Shared\Transfer\MessageAttributesTransfer;
use Generated\Shared\Transfer\SearchEndpointAvailableTransfer;
use Generated\Shared\Transfer\SearchEndpointRemovedTransfer;
use Generated\Shared\Transfer\SearchHttpConfigTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageHandlerPluginInterface;

/**
 * @method \Spryker\Zed\SearchHttp\Business\SearchHttpFacadeInterface getFacade()
 * @method \Spryker\Zed\SearchHttp\Communication\SearchHttpCommunicationFactory getFactory()
 * @method \Spryker\Zed\SearchHttp\SearchHttpConfig getConfig()
 */
class SearchEndpointMessageHandlerPlugin extends AbstractPlugin implements MessageHandlerPluginInterface
{
    /**
     * {@inheritDoc}
     * - Handles `SearchEndpointAvailable` message by saving given search HTTP config to all the stores.
     * - Handles `SearchEndpointRemoved` message by deleting given search HTTP config from all the stores.
     *
     * @api
     *
     * @return array<string, callable>
     */
    public function handles(): iterable
    {
        return [
            SearchEndpointAvailableTransfer::class => function (SearchEndpointAvailableTransfer $searchEndpointAvailableTransfer) {
                $searchHttpConfigTransfer = (new SearchHttpConfigTransfer())
                    ->setApplicationId(
                        $this->getApplicationId($searchEndpointAvailableTransfer->getMessageAttributesOrFail()),
                    )
                    ->setUrl($searchEndpointAvailableTransfer->getUrl());

                $this->getFacade()->saveSearchHttpConfig($searchHttpConfigTransfer);
            },
            SearchEndpointRemovedTransfer::class => function (SearchEndpointRemovedTransfer $searchEndpointRemovedTransfer) {
                $searchHttpConfigTransfer = (new SearchHttpConfigTransfer())
                    ->setApplicationId(
                        $this->getApplicationId($searchEndpointRemovedTransfer->getMessageAttributesOrFail()),
                    );

                $this->getFacade()->deleteSearchHttpConfig($searchHttpConfigTransfer);
            },
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\MessageAttributesTransfer $messageAttributesTransfer
     *
     * @return string
     */
    protected function getApplicationId(MessageAttributesTransfer $messageAttributesTransfer): string
    {
        return $messageAttributesTransfer->getActorId() ?: $messageAttributesTransfer->getEmitterOrFail();
    }
}
