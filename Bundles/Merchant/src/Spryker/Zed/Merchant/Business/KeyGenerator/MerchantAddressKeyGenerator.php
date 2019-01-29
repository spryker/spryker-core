<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Business\KeyGenerator;

use Spryker\Zed\Merchant\Persistence\MerchantRepositoryInterface;

class MerchantAddressKeyGenerator implements MerchantAddressKeyGeneratorInterface
{
    /**
     * @var \Spryker\Zed\Merchant\Persistence\MerchantRepositoryInterface
     */
    protected $repository;

    /**
     * @param \Spryker\Zed\Merchant\Persistence\MerchantRepositoryInterface $repository
     */
    public function __construct(MerchantRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return string
     */
    public function generateMerchantAddressKey(): string
    {
        $index = 0;
        do {
            $candidate = sprintf(
                "merchant-address-%d",
                ++$index
            );
        } while ($this->repository->hasAddressKey($candidate));

        return $candidate;
    }
}
