<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Business\KeyGenerator;

use Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipRepositoryInterface;

class MerchantRelationshipKeyGenerator implements MerchantRelationshipKeyGeneratorInterface
{
    protected const KEY_PREFIX = 'mr';

    /**
     * @var \Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipRepositoryInterface
     */
    protected $repository;

    /**
     * @param \Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipRepositoryInterface $repository
     */
    public function __construct(MerchantRelationshipRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return string
     */
    public function generateMerchantRelationshipKey(): string
    {
        $index = $this->repository->getMaxMerchantRelationshipId();
        do {
            $candidate = sprintf('%s-%d', $this->getKeyPrefix(), ++$index);
        } while ($this->repository->hasKey($candidate));

        return $candidate;
    }

    /**
     * @return string
     */
    protected function getKeyPrefix(): string
    {
        return static::KEY_PREFIX;
    }
}
