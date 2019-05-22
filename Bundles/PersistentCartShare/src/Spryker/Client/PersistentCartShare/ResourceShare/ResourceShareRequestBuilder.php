<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace  Spryker\Client\PersistentCartShare\ResourceShare;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ResourceShareDataTransfer;
use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareTransfer;
use Spryker\Client\PersistentCartShare\Dependency\Client\PersistentCartShareToCustomerClientInterface;
use Spryker\Shared\PersistentCartShare\PersistentCartShareConfig;

class ResourceShareRequestBuilder implements ResourceShareRequestBuilderInterface
{
    /**
     * @uses \Spryker\Shared\PersistentCartShare\PersistentCartShareConfig::RESOURCE_TYPE_QUOTE
     */
    protected const RESOURCE_TYPE_QUOTE = 'quote';

    /**
     * @var \Spryker\Client\PersistentCartShare\Dependency\Client\PersistentCartShareToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @param \Spryker\Client\PersistentCartShare\Dependency\Client\PersistentCartShareToCustomerClientInterface $customerClient
     */
    public function __construct(PersistentCartShareToCustomerClientInterface $customerClient)
    {
        $this->customerClient = $customerClient;
    }

    /**
     * @param int $idQuote
     * @param string $shareOption
     *
     * @return \Generated\Shared\Transfer\ResourceShareRequestTransfer
     */
    public function buildResourceShareRequest(int $idQuote, string $shareOption): ResourceShareRequestTransfer
    {
        $customerTransfer = $this->customerClient->getCustomer();
        $resourceShareDataTransfer = $this->createResolvedByShareOptionResourceShareDataTransfer($idQuote, $shareOption, $customerTransfer);

        $resourceShareTransfer = (new ResourceShareTransfer())
            ->setResourceType(PersistentCartShareConfig::RESOURCE_TYPE_QUOTE)
            ->setCustomerReference($customerTransfer->getCustomerReference())
            ->setResourceShareData($resourceShareDataTransfer);

        $resourceShareRequestTransfer = new ResourceShareRequestTransfer();
        $resourceShareRequestTransfer->setResourceShare($resourceShareTransfer);

        return $resourceShareRequestTransfer;
    }

    /**
     * @param int $idQuote
     * @param string $shareOption
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareDataTransfer
     */
    protected function createResolvedByShareOptionResourceShareDataTransfer(int $idQuote, string $shareOption, CustomerTransfer $customerTransfer): ResourceShareDataTransfer
    {
        if ($shareOption === PersistentCartShareConfig::SHARE_OPTION_PREVIEW) {
            return $this->createCartPreviewResourceShareDataTransfer($idQuote);
        }

        return $this->createCartShareResourceShareDataTransfer($idQuote, $shareOption, $customerTransfer);
    }

    /**
     * @param int $idQuote
     *
     * @return \Generated\Shared\Transfer\ResourceShareDataTransfer
     */
    protected function createCartPreviewResourceShareDataTransfer(int $idQuote): ResourceShareDataTransfer
    {
        return (new ResourceShareDataTransfer())
            ->setIdQuote($idQuote)
            ->setShareOption(PersistentCartShareConfig::SHARE_OPTION_PREVIEW);
    }

    /**
     * @param int $idQuote
     * @param string $shareOption
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareDataTransfer
     */
    protected function createCartShareResourceShareDataTransfer(int $idQuote, string $shareOption, CustomerTransfer $customerTransfer): ResourceShareDataTransfer
    {
        $customerTransfer->requireCompanyUserTransfer();
        $companyUserTransfer = $customerTransfer->getCompanyUserTransfer();

        return (new ResourceShareDataTransfer())
            ->setIdQuote($idQuote)
            ->setShareOption($shareOption)
            ->setOwnerIdCompanyUser($companyUserTransfer->getIdCompanyUser())
            ->setOwnerIdCompanyBusinessUnit($companyUserTransfer->getFkCompanyBusinessUnit());
    }
}
