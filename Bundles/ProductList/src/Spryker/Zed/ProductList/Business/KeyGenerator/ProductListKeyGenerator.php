<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Business\KeyGenerator;

use Spryker\Zed\ProductList\Dependency\Service\ProductListToUtilTextServiceInterface;
use Spryker\Zed\ProductList\Persistence\ProductListRepositoryInterface;

class ProductListKeyGenerator implements ProductListKeyGeneratorInterface
{
    protected const PRODUCT_LIST_KEY_PATTERN = '%s-%d';

    /**
     * @var \Spryker\Zed\ProductList\Persistence\ProductListRepositoryInterface
     */
    protected $productListRepository;

    /**
     * @var \Spryker\Zed\ProductList\Dependency\Service\ProductListToUtilTextServiceInterface
     */
    protected $utilTextService;

    /**
     * ProductListKeyGenerator constructor.
     *
     * @param \Spryker\Zed\ProductList\Persistence\ProductListRepositoryInterface $productListRepository
     * @param \Spryker\Zed\ProductList\Dependency\Service\ProductListToUtilTextServiceInterface $utilTextService
     */
    public function __construct(
        ProductListRepositoryInterface $productListRepository,
        ProductListToUtilTextServiceInterface $utilTextService
    ) {

        $this->productListRepository = $productListRepository;
        $this->utilTextService = $utilTextService;
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function generateProductListKey(string $name): string
    {
        $index = 0;
        do {
            $candidate = sprintf(
                static::PRODUCT_LIST_KEY_PATTERN,
                $this->utilTextService->generateSlug($name),
                ++$index
            );
        } while ($this->productListRepository->hasKey($candidate));

        return $candidate;
    }
}
