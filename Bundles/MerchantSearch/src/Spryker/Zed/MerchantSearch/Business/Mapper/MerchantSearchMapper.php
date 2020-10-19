<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSearch\Business\Mapper;

use Generated\Shared\Transfer\MerchantSearchTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\MerchantSearch\Dependency\Service\MerchantSearchToUtilEncodingServiceInterface;

class MerchantSearchMapper implements MerchantSearchMapperInterface
{
    /**
     * @var \Spryker\Zed\MerchantSearch\Dependency\Service\MerchantSearchToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\MerchantSearch\Dependency\Service\MerchantSearchToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        MerchantSearchToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     * @param \Generated\Shared\Transfer\MerchantSearchTransfer $merchantSearchTransfer
     *
     * @return void
     */
    public function mapMerchantTransferToMerchantSearchTransfer(
        MerchantTransfer $merchantTransfer,
        MerchantSearchTransfer $merchantSearchTransfer
    ): void {
        $merchantSearchTransfer->setIdMerchant($merchantTransfer->getIdMerchant());
        $merchantSearchData = $merchantTransfer->toArray(true, true);
        $merchantSearchTransfer->setData($merchantSearchData);
        $merchantSearchTransfer->setStructuredData(
            $this->utilEncodingService->encodeJson($merchantSearchData)
        );
    }
}
