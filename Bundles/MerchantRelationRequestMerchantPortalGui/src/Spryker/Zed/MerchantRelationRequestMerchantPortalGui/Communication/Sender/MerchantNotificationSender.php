<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Communication\Sender;

use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer;
use Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Dependency\Facade\MerchantRelationRequestMerchantPortalGuiToMailFacadeInterface;
use Spryker\Zed\MerchantRelationRequestMerchantPortalGui\MerchantRelationRequestMerchantPortalGuiConfig;

class MerchantNotificationSender implements MerchantNotificationSenderInterface
{
    /**
     * @uses \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Communication\Plugin\Mail\MerchantNotificationOfMerchantRelationRequestCreationMailTypeBuilderPlugin::MAIL_TYPE
     *
     * @var string
     */
    protected const MAIL_TYPE = 'merchant notification of merchant relation request creation';

    /**
     * @var \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\MerchantRelationRequestMerchantPortalGuiConfig
     */
    protected MerchantRelationRequestMerchantPortalGuiConfig $merchantRelationRequestMerchantPortalGuiConfig;

    /**
     * @var \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Dependency\Facade\MerchantRelationRequestMerchantPortalGuiToMailFacadeInterface
     */
    protected MerchantRelationRequestMerchantPortalGuiToMailFacadeInterface $mailFacade;

    /**
     * @param \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\MerchantRelationRequestMerchantPortalGuiConfig $merchantRelationRequestMerchantPortalGuiConfig
     * @param \Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Dependency\Facade\MerchantRelationRequestMerchantPortalGuiToMailFacadeInterface $mailFacade
     */
    public function __construct(
        MerchantRelationRequestMerchantPortalGuiConfig $merchantRelationRequestMerchantPortalGuiConfig,
        MerchantRelationRequestMerchantPortalGuiToMailFacadeInterface $mailFacade
    ) {
        $this->merchantRelationRequestMerchantPortalGuiConfig = $merchantRelationRequestMerchantPortalGuiConfig;
        $this->mailFacade = $mailFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer $merchantRelationRequestCollectionResponseTransfer
     *
     * @return void
     */
    public function sentNotificationToMerchant(
        MerchantRelationRequestCollectionResponseTransfer $merchantRelationRequestCollectionResponseTransfer
    ): void {
        $merchantRelationRequestTableLink = $this->createMerchantRelationRequestTableLink();
        $merchantTransfers = $this->extractUniqueMerchants($merchantRelationRequestCollectionResponseTransfer);

        foreach ($merchantTransfers as $merchantTransfer) {
            $this->mailFacade->handleMail(
                (new MailTransfer())
                    ->setType(static::MAIL_TYPE)
                    ->setMerchant($merchantTransfer)
                    ->setMerchantRelationRequestTableLink($merchantRelationRequestTableLink),
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer $merchantRelationRequestCollectionResponseTransfer
     *
     * @return array<int, \Generated\Shared\Transfer\MerchantTransfer>
     */
    protected function extractUniqueMerchants(
        MerchantRelationRequestCollectionResponseTransfer $merchantRelationRequestCollectionResponseTransfer
    ): array {
        $merchantTransfers = [];

        foreach ($merchantRelationRequestCollectionResponseTransfer->getMerchantRelationRequests() as $merchantRelationRequestTransfer) {
            $idMerchant = $merchantRelationRequestTransfer->getMerchantOrFail()->getIdMerchantOrFail();

            if (!isset($merchantTransfers[$idMerchant])) {
                $merchantTransfers[$idMerchant] = $merchantRelationRequestTransfer->getMerchantOrFail();
            }
        }

        return $merchantTransfers;
    }

    /**
     * @return string
     */
    protected function createMerchantRelationRequestTableLink(): string
    {
        return sprintf(
            '%s%s?%s',
            $this->merchantRelationRequestMerchantPortalGuiConfig->getMerchantPortalBaseUrl(),
            $this->merchantRelationRequestMerchantPortalGuiConfig->getMerchantRelationRequestTablePath(),
            $this->merchantRelationRequestMerchantPortalGuiConfig->getMerchantRelationRequestTableQuery(),
        );
    }
}
