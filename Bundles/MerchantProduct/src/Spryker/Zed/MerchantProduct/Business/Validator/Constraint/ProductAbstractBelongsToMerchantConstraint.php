<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProduct\Business\Validator\Constraint;

use Spryker\Zed\MerchantProduct\Persistence\MerchantProductRepositoryInterface;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class ProductAbstractBelongsToMerchantConstraint extends SymfonyConstraint
{
    protected const MESSAGE = 'Merchant product is not found for product abstract id %d and merchant id %d.';

    /**
     * @var \Spryker\Zed\MerchantProduct\Persistence\MerchantProductRepositoryInterface
     */
    protected $merchantProductRepository;

    /**
     * @param \Spryker\Zed\MerchantProduct\Persistence\MerchantProductRepositoryInterface $merchantProductRepository
     */
    public function __construct(MerchantProductRepositoryInterface $merchantProductRepository)
    {
        $this->merchantProductRepository = $merchantProductRepository;

        parent::__construct();
    }

    /**
     * @return \Spryker\Zed\MerchantProduct\Persistence\MerchantProductRepositoryInterface
     */
    public function getMerchantProductRepository(): MerchantProductRepositoryInterface
    {
        return $this->merchantProductRepository;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return static::MESSAGE;
    }

    /**
     * @return string
     */
    public function getTargets(): string
    {
        return static::CLASS_CONSTRAINT;
    }
}