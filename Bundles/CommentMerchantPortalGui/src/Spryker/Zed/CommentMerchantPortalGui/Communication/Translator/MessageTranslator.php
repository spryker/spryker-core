<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CommentMerchantPortalGui\Communication\Translator;

use ArrayObject;
use Spryker\Zed\CommentMerchantPortalGui\Dependency\Facade\CommentMerchantPortalGuiToTranslatorFacadeInterface;

class MessageTranslator implements MessageTranslatorInterface
{
    /**
     * @var \Spryker\Zed\CommentMerchantPortalGui\Dependency\Facade\CommentMerchantPortalGuiToTranslatorFacadeInterface
     */
    protected CommentMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade;

    /**
     * @param \Spryker\Zed\CommentMerchantPortalGui\Dependency\Facade\CommentMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade
     */
    public function __construct(CommentMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade)
    {
        $this->translatorFacade = $translatorFacade;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MessageTransfer> $messageTransfers
     *
     * @return list<string>
     */
    public function translateErrorMessages(ArrayObject $messageTransfers): array
    {
        $errorMessages = [];
        foreach ($messageTransfers as $messageTransfer) {
            $errorMessages[] = $this->translatorFacade->trans(
                $messageTransfer->getValueOrFail(),
                $messageTransfer->getParameters(),
            );
        }

        return $errorMessages;
    }
}
