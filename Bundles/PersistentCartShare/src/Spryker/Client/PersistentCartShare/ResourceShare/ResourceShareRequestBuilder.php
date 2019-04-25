<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace  Spryker\Client\PersistentCartShare\ResourceShare;

use Generated\Shared\Transfer\ResourceShareDataTransfer;
use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareTransfer;
use Spryker\Client\PersistentCartShare\Dependency\Client\PersistentCartShareToCustomerClientInterface;

class ResourceShareRequestBuilder implements ResourceShareRequestBuilderInterface
{
    protected const RESOURCE_TYPE_QUOTE = 'quote';
    protected const ID_QUOTE_PARAMETER = 'id_quote';
    protected const SHARE_OPTION_PARAMETER = 'share_option';

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
        $resourceShareDataTransfer = (new ResourceShareDataTransfer())
            ->setData([
                static::ID_QUOTE_PARAMETER => $idQuote,
                static::SHARE_OPTION_PARAMETER => $shareOption,
            ]);

        $resourceShareTransfer = (new ResourceShareTransfer())
            ->setResourceType(static::RESOURCE_TYPE_QUOTE)
            ->setCustomerReference($this->customerClient->getCustomer()->getCustomerReference())
            ->setResourceShareData($resourceShareDataTransfer);

        $resourceShareRequestTransfer = new ResourceShareRequestTransfer();
        $resourceShareRequestTransfer->setResourceShare($resourceShareTransfer);

        return $resourceShareRequestTransfer;
    }
}
