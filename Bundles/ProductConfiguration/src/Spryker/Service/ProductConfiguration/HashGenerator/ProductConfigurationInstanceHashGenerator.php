<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\ProductConfiguration\HashGenerator;

use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Spryker\Service\ProductConfiguration\Dependency\Service\ProductConfigurationToUtilEncodingServiceInterface;
use Spryker\Service\ProductConfiguration\Dependency\Service\ProductConfigurationToUtilTextServiceInterface;

class ProductConfigurationInstanceHashGenerator implements ProductConfigurationInstanceHashGeneratorInterface
{
    /**
     * @var \Spryker\Service\ProductConfiguration\Dependency\Service\ProductConfigurationToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Service\ProductConfiguration\Dependency\Service\ProductConfigurationToUtilTextServiceInterface
     */
    protected $utilTextService;

    /**
     * @param \Spryker\Service\ProductConfiguration\Dependency\Service\ProductConfigurationToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Service\ProductConfiguration\Dependency\Service\ProductConfigurationToUtilTextServiceInterface $utilTextService
     */
    public function __construct(
        ProductConfigurationToUtilEncodingServiceInterface $utilEncodingService,
        ProductConfigurationToUtilTextServiceInterface $utilTextService
    ) {
        $this->utilEncodingService = $utilEncodingService;
        $this->utilTextService = $utilTextService;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
     *
     * @return string
     */
    public function getProductConfigurationInstanceHash(ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer): string
    {
        $encodedProductConfigurationInstanceData = $this->utilEncodingService->encodeJson(
            $productConfigurationInstanceTransfer->toArray()
        );

        return $this->utilTextService->hashValue($encodedProductConfigurationInstanceData, 'md5');
    }
}
