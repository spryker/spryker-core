<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreReference\Communication\Plugin\MessageBroker;

use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageValidatorPluginInterface;
use Spryker\Zed\StoreReference\Business\Exception\StoreReferenceNotFoundException;

/**
 * @method \Spryker\Zed\StoreReference\Business\StoreReferenceFacadeInterface getFacade()
 * @method \Spryker\Zed\StoreReference\StoreReferenceConfig getConfig()
 */
class StoreReferenceMessageValidatorPlugin extends AbstractPlugin implements MessageValidatorPluginInterface
{
    use LoggerTrait;

    /**
     * @var string
     */
    protected const VALIDATION_ERROR_STORE_REFERENCE_ERROR = 'Invalid storeReference in message "%s"';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $messageTransfer
     *
     * @return bool
     */
    public function isValid(TransferInterface $messageTransfer): bool
    {
        try {
            $storeReference = $this->getFacade()->getCurrentStore()->getStoreReference();
        } catch (StoreReferenceNotFoundException $exception) {
            $this->getLogger()->error('Store reference map is not configured');

            return false;
        }

        if ($storeReference !== $messageTransfer->getMessageAttributes()->getStoreReference()) {
            $this->getLogger()->error(
                sprintf(static::VALIDATION_ERROR_STORE_REFERENCE_ERROR, get_class($messageTransfer)),
                [
                    'message' => $messageTransfer->toArray(),
                    'storeReference' => $storeReference,
                ],
            );

            return false;
        }

        return true;
    }
}
