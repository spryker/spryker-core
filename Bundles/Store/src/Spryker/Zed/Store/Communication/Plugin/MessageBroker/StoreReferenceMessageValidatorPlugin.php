<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Communication\Plugin\MessageBroker;

use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageValidatorPluginInterface;

/**
 * @method \Spryker\Zed\Store\Business\StoreFacadeInterface getFacade()
 * @method \Spryker\Zed\Store\StoreConfig getConfig()
 * @method \Spryker\Zed\Store\Communication\StoreCommunicationFactory getFactory()
 * @method \Spryker\Zed\Store\Persistence\StoreQueryContainerInterface getQueryContainer()
 */
class StoreReferenceMessageValidatorPlugin extends AbstractPlugin implements MessageValidatorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Validates if `storeReference` from the message matches `storeReference` of one the configured stores.
     *
     * @api
     *
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $messageTransfer
     *
     * @return bool
     */
    public function isValid(TransferInterface $messageTransfer): bool
    {
        return $this->getFacade()->validateMessageTransfer($messageTransfer)->getIsValid();
    }
}
