<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfile\Business\MerchantProfile;

use Generated\Shared\Transfer\MerchantProfileCollectionTransfer;
use Generated\Shared\Transfer\MerchantProfileCriteriaTransfer;
use Generated\Shared\Transfer\MerchantProfileTransfer;
use Spryker\Zed\MerchantProfile\Persistence\MerchantProfileRepositoryInterface;

class MerchantProfileReader implements MerchantProfileReaderInterface
{
    /**
     * @var \Spryker\Zed\MerchantProfile\Persistence\MerchantProfileRepositoryInterface
     */
    protected $merchantProfileRepository;

    /**
     * @param \Spryker\Zed\MerchantProfile\Persistence\MerchantProfileRepositoryInterface $merchantProfileRepository
     */
    public function __construct(MerchantProfileRepositoryInterface $merchantProfileRepository)
    {
        $this->merchantProfileRepository = $merchantProfileRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileCriteriaTransfer $merchantProfileCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer|null
     */
    public function findOne(MerchantProfileCriteriaTransfer $merchantProfileCriteriaTransfer): ?MerchantProfileTransfer
    {
        return $this->merchantProfileRepository->findOne($merchantProfileCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileCriteriaTransfer $merchantProfileCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileCollectionTransfer
     */
    public function get(MerchantProfileCriteriaTransfer $merchantProfileCriteriaTransfer): MerchantProfileCollectionTransfer
    {
        return $this->merchantProfileRepository->get($merchantProfileCriteriaTransfer);
    }

    /**
     * @param array $merchantReferences
     *
     * @return array
     */
    public function findMerchantProfileAddressesCollectionIndexedByMerchantReference(array $merchantReferences): array
    {
        return $this->merchantProfileRepository->findMerchantProfileAddressesCollectionIndexedByMerchantReference($merchantReferences);
    }
}
