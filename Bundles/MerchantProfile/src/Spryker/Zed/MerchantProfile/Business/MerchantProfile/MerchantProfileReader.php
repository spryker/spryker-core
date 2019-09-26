<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfile\Business\MerchantProfile;

use Generated\Shared\Transfer\MerchantProfileCriteriaFilterTransfer;
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
     * @param \Generated\Shared\Transfer\MerchantProfileCriteriaFilterTransfer $merchantProfileCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer|null
     */
    public function findOne(MerchantProfileCriteriaFilterTransfer $merchantProfileCriteriaFilterTransfer): ?MerchantProfileTransfer
    {
        return $this->merchantProfileRepository->findOne($merchantProfileCriteriaFilterTransfer);
    }
}
