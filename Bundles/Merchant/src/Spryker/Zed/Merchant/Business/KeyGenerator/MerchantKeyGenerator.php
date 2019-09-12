<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Business\KeyGenerator;

use Spryker\Zed\Merchant\Dependency\Service\MerchantToUtilTextServiceInterface;
use Spryker\Zed\Merchant\Persistence\MerchantRepositoryInterface;

class MerchantKeyGenerator implements MerchantKeyGeneratorInterface
{
    /**
     * @var \Spryker\Zed\Merchant\Persistence\MerchantRepositoryInterface
     */
    protected $merchantRepository;

    /**
     * @var \Spryker\Zed\Merchant\Dependency\Service\MerchantToUtilTextServiceInterface
     */
    protected $utilTextService;

    /**
     * @param \Spryker\Zed\Merchant\Persistence\MerchantRepositoryInterface $merchantRepository
     * @param \Spryker\Zed\Merchant\Dependency\Service\MerchantToUtilTextServiceInterface $utilTextService
     */
    public function __construct(
        MerchantRepositoryInterface $merchantRepository,
        MerchantToUtilTextServiceInterface $utilTextService
    ) {
        $this->merchantRepository = $merchantRepository;
        $this->utilTextService = $utilTextService;
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function generateMerchantKey(string $name): string
    {
        $index = 0;
        do {
            $candidate = sprintf(
                "%s-%d",
                $this->utilTextService->generateSlug($name),
                ++$index
            );
        } while ($this->merchantRepository->hasKey($candidate));

        return $candidate;
    }
}
