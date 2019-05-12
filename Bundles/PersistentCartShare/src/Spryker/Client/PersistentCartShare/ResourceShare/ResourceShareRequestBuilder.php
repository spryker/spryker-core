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
    protected const RESOURCE_TYPE_QUOTE = 'quote';
    protected const ID_QUOTE_PARAMETER = 'id_quote';
    protected const SHARE_OPTION_PARAMETER = 'share_option';

    protected const KEY_OWNER_ID_COMPANY_BUSINESS_UNIT = 'owner_id_company_business_unit';
    protected const KEY_OWNER_ID_COMPANY_USER = 'owner_id_company_user';

    /**
     * @uses \Spryker\Zed\PersistentCartShare\PersistentCartShareConfig::SHARE_OPTION_PREVIEW
     */
    protected const SHARE_OPTION_PREVIEW = 'PREVIEW';

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

        $resourceShareDataTransfer = (new ResourceShareDataTransfer())
            ->setData($this->getResourceShareData($idQuote, $shareOption, $customerTransfer));

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
     * @return array
     */
    protected function getResourceShareData(int $idQuote, string $shareOption, CustomerTransfer $customerTransfer): array
    {
        $resourceShareData = [
            static::ID_QUOTE_PARAMETER => $idQuote,
            static::SHARE_OPTION_PARAMETER => $shareOption,
        ];

        if ($shareOption === static::SHARE_OPTION_PREVIEW) {
            return $resourceShareData;
        }

        $companyUserTransfer = $customerTransfer->getCompanyUserTransfer();
        if (!$companyUserTransfer) {
            return $resourceShareData;
        }

        return $resourceShareData + [
            static::KEY_OWNER_ID_COMPANY_USER => $companyUserTransfer->getIdCompanyUser(),
            static::KEY_OWNER_ID_COMPANY_BUSINESS_UNIT => $companyUserTransfer->getCompanyBusinessUnit()->getIdCompanyBusinessUnit(),
        ];
    }
}
