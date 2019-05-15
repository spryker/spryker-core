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

class ResourceShareRequestBuilder implements ResourceShareRequestBuilderInterface
{
    /**
     * @uses \Spryker\Zed\PersistentCartShare\PersistentCartShareConfig::RESOURCE_TYPE_QUOTE
     */
    protected const RESOURCE_TYPE_QUOTE = 'quote';

    /**
     * @uses \Spryker\Zed\PersistentCartShare\PersistentCartShareConfig::KEY_ID_QUOTE
     */
    protected const KEY_ID_QUOTE = 'id_quote';

    /**
     * @uses \Spryker\Zed\PersistentCartShare\PersistentCartShareConfig::KEY_SHARE_OPTION
     */
    protected const KEY_SHARE_OPTION = 'share_option';

    /**
     * @uses \Spryker\Zed\PersistentCartShare\PersistentCartShareConfig::SHARE_OPTION_PREVIEW
     */
    protected const SHARE_OPTION_PREVIEW = 'PREVIEW';

    protected const KEY_OWNER_ID_COMPANY_BUSINESS_UNIT = 'owner_id_company_business_unit';

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
        $resourceShareDataTransfer = $this->getResourceShareDataTransfer($idQuote, $shareOption, $customerTransfer);

        $resourceShareTransfer = (new ResourceShareTransfer())
            ->setResourceType(static::RESOURCE_TYPE_QUOTE)
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
    protected function getResourceShareDataTransfer(int $idQuote, string $shareOption, CustomerTransfer $customerTransfer): ResourceShareDataTransfer
    {
        $resourceShareDataTransfer = (new ResourceShareDataTransfer())
            ->setIdQuote($idQuote)
            ->setShareOption($shareOption);

        if ($shareOption === static::SHARE_OPTION_PREVIEW) {
            return $resourceShareDataTransfer;
        }

        $companyUserTransfer = $customerTransfer->getCompanyUserTransfer();
        if (!$companyUserTransfer) {
            return $resourceShareDataTransfer;
        }

        return $resourceShareDataTransfer->setOwnerIdCompanyBusinessUnit($companyUserTransfer->getFkCompanyBusinessUnit());
    }
}
