<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Business\Sender;

use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Spryker\Zed\MerchantRelationship\Business\Builder\MerchantRelationshipDeleteMailBuilderInterface;
use Spryker\Zed\MerchantRelationship\Business\Reader\CompanyBusinessUnitReaderInterface;
use Spryker\Zed\MerchantRelationship\Business\Reader\MerchantReaderInterface;
use Spryker\Zed\MerchantRelationship\Dependency\Facade\MerchantRelationshipToMailFacadeInterface;

class MerchantRelationshipDeleteMailNotificationSender implements MerchantRelationshipDeleteMailNotificationSenderInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationship\Business\Reader\CompanyBusinessUnitReaderInterface
     */
    protected CompanyBusinessUnitReaderInterface $companyBusinessUnitReader;

    /**
     * @var \Spryker\Zed\MerchantRelationship\Business\Reader\MerchantReaderInterface
     */
    protected MerchantReaderInterface $merchantReader;

    /**
     * @var \Spryker\Zed\MerchantRelationship\Business\Builder\MerchantRelationshipDeleteMailBuilderInterface
     */
    protected MerchantRelationshipDeleteMailBuilderInterface $merchantRelationshipDeleteMailBuilder;

    /**
     * @var \Spryker\Zed\MerchantRelationship\Dependency\Facade\MerchantRelationshipToMailFacadeInterface
     */
    protected MerchantRelationshipToMailFacadeInterface $mailFacade;

    /**
     * @param \Spryker\Zed\MerchantRelationship\Business\Reader\CompanyBusinessUnitReaderInterface $companyBusinessUnitReader
     * @param \Spryker\Zed\MerchantRelationship\Business\Reader\MerchantReaderInterface $merchantReader
     * @param \Spryker\Zed\MerchantRelationship\Business\Builder\MerchantRelationshipDeleteMailBuilderInterface $merchantRelationshipDeleteMailBuilder
     * @param \Spryker\Zed\MerchantRelationship\Dependency\Facade\MerchantRelationshipToMailFacadeInterface $mailFacade
     */
    public function __construct(
        CompanyBusinessUnitReaderInterface $companyBusinessUnitReader,
        MerchantReaderInterface $merchantReader,
        MerchantRelationshipDeleteMailBuilderInterface $merchantRelationshipDeleteMailBuilder,
        MerchantRelationshipToMailFacadeInterface $mailFacade
    ) {
        $this->companyBusinessUnitReader = $companyBusinessUnitReader;
        $this->merchantReader = $merchantReader;
        $this->merchantRelationshipDeleteMailBuilder = $merchantRelationshipDeleteMailBuilder;
        $this->mailFacade = $mailFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return void
     */
    public function sendMerchantRelationshipDeleteMailNotification(MerchantRelationshipTransfer $merchantRelationshipTransfer): void
    {
        $companyBusinessUnitTransfersIndexedByIdCompanyBusinessUnit = $this->companyBusinessUnitReader
            ->getCompanyBusinessUnitTransfersIndexedByIdCompanyBusinessUnit(
                $this->extractCompanyBusinessUnitIds($merchantRelationshipTransfer),
            );
        if ($companyBusinessUnitTransfersIndexedByIdCompanyBusinessUnit === []) {
            return;
        }

        $merchantTransfer = $this->merchantReader->findMerchant($merchantRelationshipTransfer->getFkMerchantOrFail());
        if (!$merchantTransfer) {
            return;
        }

        if (
            !$this->hasOwnerCompanyBusinessUnitEmail(
                $merchantRelationshipTransfer,
                $companyBusinessUnitTransfersIndexedByIdCompanyBusinessUnit,
            )
        ) {
            return;
        }

        $assigneeCompanyBusinessUnitEmails = $this->getCompanyBusinessUnitEmails(
            $merchantRelationshipTransfer->getAssigneeCompanyBusinessUnitsOrFail(),
            $companyBusinessUnitTransfersIndexedByIdCompanyBusinessUnit,
        );

        $mailTransfer = $this->merchantRelationshipDeleteMailBuilder->createMailTransfer(
            $merchantRelationshipTransfer,
            $merchantTransfer,
            $assigneeCompanyBusinessUnitEmails,
        );

        $this->mailFacade->handleMail($mailTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return array<int, int>
     */
    protected function extractCompanyBusinessUnitIds(
        MerchantRelationshipTransfer $merchantRelationshipTransfer
    ): array {
        $companyBusinessUnitIds = [];

        $companyBusinessUnitIds[] = $merchantRelationshipTransfer->getOwnerCompanyBusinessUnitOrFail()->getIdCompanyBusinessUnitOrFail();
        foreach ($merchantRelationshipTransfer->getAssigneeCompanyBusinessUnitsOrFail()->getCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
            $companyBusinessUnitIds[] = $companyBusinessUnitTransfer->getIdCompanyBusinessUnitOrFail();
        }

        return array_unique($companyBusinessUnitIds);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer $companyBusinessUnitCollectionTransfer
     * @param array<int, \Generated\Shared\Transfer\CompanyBusinessUnitTransfer> $companyBusinessUnitTransfersIndexedByIdCompanyBusinessUnit
     *
     * @return array<int, string>
     */
    protected function getCompanyBusinessUnitEmails(
        CompanyBusinessUnitCollectionTransfer $companyBusinessUnitCollectionTransfer,
        array $companyBusinessUnitTransfersIndexedByIdCompanyBusinessUnit
    ): array {
        $companyBusinessUnitEmails = [];
        foreach ($companyBusinessUnitCollectionTransfer->getCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
            $idCompanyBusinessUnit = $companyBusinessUnitTransfer->getIdCompanyBusinessUnitOrFail();
            if (
                !$this->companyBusinessUnitEmailExists(
                    $idCompanyBusinessUnit,
                    $companyBusinessUnitTransfersIndexedByIdCompanyBusinessUnit,
                )
            ) {
                continue;
            }

            $companyBusinessUnitEmails[] = $companyBusinessUnitTransfersIndexedByIdCompanyBusinessUnit[$idCompanyBusinessUnit]->getEmailOrFail();
        }

        return array_unique($companyBusinessUnitEmails);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     * @param array<int, \Generated\Shared\Transfer\CompanyBusinessUnitTransfer> $companyBusinessUnitTransfersIndexedByIdCompanyBusinessUnit
     *
     * @return bool
     */
    protected function hasOwnerCompanyBusinessUnitEmail(
        MerchantRelationshipTransfer $merchantRelationshipTransfer,
        array $companyBusinessUnitTransfersIndexedByIdCompanyBusinessUnit
    ): bool {
        $idOwnerCompanyBusinessUnit = $merchantRelationshipTransfer->getOwnerCompanyBusinessUnitOrFail()->getIdCompanyBusinessUnitOrFail();

        return $this->companyBusinessUnitEmailExists(
            $idOwnerCompanyBusinessUnit,
            $companyBusinessUnitTransfersIndexedByIdCompanyBusinessUnit,
        );
    }

    /**
     * @param int $idCompanyBusinessUnit
     * @param array<int, \Generated\Shared\Transfer\CompanyBusinessUnitTransfer> $companyBusinessUnitTransfersIndexedByIdCompanyBusinessUnit
     *
     * @return bool
     */
    protected function companyBusinessUnitEmailExists(
        int $idCompanyBusinessUnit,
        array $companyBusinessUnitTransfersIndexedByIdCompanyBusinessUnit
    ): bool {
        return isset($companyBusinessUnitTransfersIndexedByIdCompanyBusinessUnit[$idCompanyBusinessUnit])
            && $companyBusinessUnitTransfersIndexedByIdCompanyBusinessUnit[$idCompanyBusinessUnit]->getEmail();
    }
}
