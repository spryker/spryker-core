<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSearch\Business\Mapper;

use Generated\Shared\Transfer\MerchantCollectionTransfer;
use Generated\Shared\Transfer\MerchantSearchCollectionTransfer;
use Generated\Shared\Transfer\MerchantSearchTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\MerchantSearch\Business\DataMapper\MerchantSearchDataMapperInterface;
use Spryker\Zed\MerchantSearch\Dependency\Service\MerchantSearchToUtilEncodingServiceInterface;

class MerchantSearchMapper implements MerchantSearchMapperInterface
{
    /**
     * @var \Spryker\Zed\MerchantSearch\Dependency\Service\MerchantSearchToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Zed\MerchantSearch\Business\DataMapper\MerchantSearchDataMapperInterface
     */
    protected $merchantSearchDataMapper;

    /**
     * @param \Spryker\Zed\MerchantSearch\Dependency\Service\MerchantSearchToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Zed\MerchantSearch\Business\DataMapper\MerchantSearchDataMapperInterface $merchantSearchDataMapper
     */
    public function __construct(
        MerchantSearchToUtilEncodingServiceInterface $utilEncodingService,
        MerchantSearchDataMapperInterface $merchantSearchDataMapper
    ) {
        $this->utilEncodingService = $utilEncodingService;
        $this->merchantSearchDataMapper = $merchantSearchDataMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     * @param \Generated\Shared\Transfer\MerchantSearchTransfer $merchantSearchTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantSearchTransfer
     */
    public function mapMerchantTransferToMerchantSearchTransfer(
        MerchantTransfer $merchantTransfer,
        MerchantSearchTransfer $merchantSearchTransfer
    ): MerchantSearchTransfer {
        $merchantSearchTransfer->setIdMerchant($merchantTransfer->getIdMerchant());
        $merchantSearchData = $merchantTransfer->toArray(true, true);
        $merchantSearchTransfer->setData(
            $this->merchantSearchDataMapper->mapMerchantDataToSearchData($merchantSearchData)
        );
        $merchantSearchTransfer->setStructuredData(
            $this->utilEncodingService->encodeJson($merchantSearchData)
        );

        return $merchantSearchTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCollectionTransfer $merchantCollectionTransfer
     * @param \Generated\Shared\Transfer\MerchantSearchCollectionTransfer $merchantSearchCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantSearchCollectionTransfer
     */
    public function mapMerchantCollectionTransferToMerchantSearchCollectionTransfer(
        MerchantCollectionTransfer $merchantCollectionTransfer,
        MerchantSearchCollectionTransfer $merchantSearchCollectionTransfer
    ): MerchantSearchCollectionTransfer {
        foreach ($merchantCollectionTransfer->getMerchants() as $merchantTransfer) {
            $merchantSearchTransfer = $this->mapMerchantTransferToMerchantSearchTransfer(
                $merchantTransfer,
                new MerchantSearchTransfer()
            );
            $merchantSearchCollectionTransfer->addMerchant($merchantSearchTransfer);
        }

        return $merchantSearchCollectionTransfer;
    }
}
