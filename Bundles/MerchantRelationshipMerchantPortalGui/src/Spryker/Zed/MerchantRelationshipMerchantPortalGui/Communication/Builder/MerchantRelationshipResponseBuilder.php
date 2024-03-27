<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\Builder;

use ArrayObject;
use Spryker\Shared\ZedUi\ZedUiFactoryInterface;
use Spryker\Zed\MerchantRelationshipMerchantPortalGui\Dependency\Facade\MerchantRelationshipMerchantPortalGuiToTranslatorFacadeInterface;

class MerchantRelationshipResponseBuilder implements MerchantRelationshipResponseBuilderInterface
{
    /**
     * @var \Spryker\Shared\ZedUi\ZedUiFactoryInterface
     */
    protected ZedUiFactoryInterface $zedUiFactory;

    /**
     * @var \Spryker\Zed\MerchantRelationshipMerchantPortalGui\Dependency\Facade\MerchantRelationshipMerchantPortalGuiToTranslatorFacadeInterface
     */
    protected MerchantRelationshipMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade;

    /**
     * @param \Spryker\Shared\ZedUi\ZedUiFactoryInterface $zedUiFactory
     * @param \Spryker\Zed\MerchantRelationshipMerchantPortalGui\Dependency\Facade\MerchantRelationshipMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade
     */
    public function __construct(
        ZedUiFactoryInterface $zedUiFactory,
        MerchantRelationshipMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade
    ) {
        $this->zedUiFactory = $zedUiFactory;
        $this->translatorFacade = $translatorFacade;
    }

    /**
     * @param array<string, mixed> $responseData
     * @param string $notificationMessage
     *
     * @return array<string, mixed>
     */
    public function addSuccessfulResponseDataToResponse(array $responseData, string $notificationMessage): array
    {
        $notification = $this->translatorFacade->trans($notificationMessage);

        $zedUiFormResponseTransfer = $this->zedUiFactory
            ->createZedUiFormResponseBuilder()
            ->addSuccessNotification($notification)
            ->addActionCloseDrawer()
            ->addActionRefreshTable()
            ->createResponse();

        return array_merge($responseData, $zedUiFormResponseTransfer->toArray());
    }

    /**
     * @param array<string, mixed> $responseData
     * @param string $notificationMessage
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantRelationshipErrorTransfer>|null $merchantRelationshipErrorTransfers
     *
     * @return array<string, mixed>
     */
    public function addErrorResponseDataToResponse(
        array $responseData,
        string $notificationMessage,
        ?ArrayObject $merchantRelationshipErrorTransfers = null
    ): array {
        $notification = $this->translatorFacade->trans($notificationMessage);

        $zedUiFormResponseBuilder = $this->zedUiFactory
            ->createZedUiFormResponseBuilder()
            ->addErrorNotification($notification);

        if ($merchantRelationshipErrorTransfers === null) {
            return array_merge($responseData, $zedUiFormResponseBuilder->createResponse()->toArray());
        }

        foreach ($merchantRelationshipErrorTransfers as $merchantRelationshipErrorTransfer) {
            $zedUiFormResponseBuilder->addErrorNotification(
                $this->translatorFacade->trans($merchantRelationshipErrorTransfer->getMessageOrFail()),
            );
        }

        return array_merge($responseData, $zedUiFormResponseBuilder->createResponse()->toArray());
    }
}
