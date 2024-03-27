<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Business\Builder;

use ArrayObject;
use Generated\Shared\Transfer\MailRecipientTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\MerchantRelationship\Dependency\Facade\MerchantRelationshipToLocaleFacadeInterface;
use Spryker\Zed\MerchantRelationship\MerchantRelationshipConfig;

class MerchantRelationshipDeleteMailBuilder implements MerchantRelationshipDeleteMailBuilderInterface
{
    /**
     * @uses \Spryker\Zed\MerchantRelationship\Communication\Plugin\Mail\MerchantRelationshipDeleteMailTypeBuilderPlugin::MAIL_TYPE
     *
     * @var string
     */
    protected const MAIL_TYPE = 'merchant relationship delete';

    /**
     * @var \Spryker\Zed\MerchantRelationship\MerchantRelationshipConfig
     */
    protected MerchantRelationshipConfig $merchantRelationshipConfig;

    /**
     * @var \Spryker\Zed\MerchantRelationship\Dependency\Facade\MerchantRelationshipToLocaleFacadeInterface
     */
    protected MerchantRelationshipToLocaleFacadeInterface $localeFacade;

    /**
     * @param \Spryker\Zed\MerchantRelationship\MerchantRelationshipConfig $merchantRelationshipConfig
     * @param \Spryker\Zed\MerchantRelationship\Dependency\Facade\MerchantRelationshipToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        MerchantRelationshipConfig $merchantRelationshipConfig,
        MerchantRelationshipToLocaleFacadeInterface $localeFacade
    ) {
        $this->merchantRelationshipConfig = $merchantRelationshipConfig;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     * @param list<string> $assigneeCompanyBusinessUnitEmails
     *
     * @return \Generated\Shared\Transfer\MailTransfer
     */
    public function createMailTransfer(
        MerchantRelationshipTransfer $merchantRelationshipTransfer,
        MerchantTransfer $merchantTransfer,
        array $assigneeCompanyBusinessUnitEmails
    ): MailTransfer {
        $ownerCompanyBusinessUnitTransfer = $merchantRelationshipTransfer->getOwnerCompanyBusinessUnitOrFail();
        $mailTransfer = (new MailTransfer())
            ->setType(static::MAIL_TYPE)
            ->setMerchant($merchantTransfer)
            ->setCompanyBusinessUnit($ownerCompanyBusinessUnitTransfer);

        $this->addAssigneeCompanyBusinessUnitsToBccCopy(
            $mailTransfer,
            $ownerCompanyBusinessUnitTransfer->getEmailOrFail(),
            $assigneeCompanyBusinessUnitEmails,
        );

        return $mailTransfer->setMerchantUrl(
            $this->findMerchantUrlForCurrentLocale($merchantTransfer->getUrlCollection()),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     * @param string $ownerCompanyBusinessUnitEmail
     * @param list<string> $assigneeCompanyBusinessUnitEmails
     *
     * @return \Generated\Shared\Transfer\MailTransfer
     */
    protected function addAssigneeCompanyBusinessUnitsToBccCopy(
        MailTransfer $mailTransfer,
        string $ownerCompanyBusinessUnitEmail,
        array $assigneeCompanyBusinessUnitEmails
    ): MailTransfer {
        foreach ($assigneeCompanyBusinessUnitEmails as $assigneeCompanyBusinessUnitEmail) {
            if ($assigneeCompanyBusinessUnitEmail !== $ownerCompanyBusinessUnitEmail) {
                $mailTransfer->addRecipientBcc(
                    (new MailRecipientTransfer())
                        ->setEmail($assigneeCompanyBusinessUnitEmail),
                );
            }
        }

        return $mailTransfer;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\UrlTransfer> $urlTransfers
     *
     * @return string|null
     */
    protected function findMerchantUrlForCurrentLocale(ArrayObject $urlTransfers): ?string
    {
        $localeTransfer = $this->localeFacade->getCurrentLocale();
        foreach ($urlTransfers as $urlTransfer) {
            if ($urlTransfer->getFkLocaleOrFail() === $localeTransfer->getIdLocaleOrFail()) {
                return $this->formatUrl($urlTransfer->getUrlOrFail());
            }
        }

        return null;
    }

    /**
     * @param string $url
     *
     * @return string
     */
    protected function formatUrl(string $url): string
    {
        return sprintf('%s%s', $this->merchantRelationshipConfig->getYvesBaseUrl(), $url);
    }
}
