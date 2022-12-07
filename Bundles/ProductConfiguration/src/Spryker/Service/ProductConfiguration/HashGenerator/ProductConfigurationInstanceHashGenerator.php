<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\ProductConfiguration\HashGenerator;

use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Spryker\Service\ProductConfiguration\Dependency\Service\ProductConfigurationToUtilEncodingServiceInterface;
use Spryker\Service\ProductConfiguration\Dependency\Service\ProductConfigurationToUtilTextServiceInterface;
use Spryker\Service\ProductConfiguration\ProductConfigurationConfig;

class ProductConfigurationInstanceHashGenerator implements ProductConfigurationInstanceHashGeneratorInterface
{
    /**
     * @var \Spryker\Service\ProductConfiguration\Dependency\Service\ProductConfigurationToUtilEncodingServiceInterface
     */
    protected ProductConfigurationToUtilEncodingServiceInterface $utilEncodingService;

    /**
     * @var \Spryker\Service\ProductConfiguration\Dependency\Service\ProductConfigurationToUtilTextServiceInterface
     */
    protected ProductConfigurationToUtilTextServiceInterface $utilTextService;

    /**
     * @var \Spryker\Service\ProductConfiguration\ProductConfigurationConfig
     */
    protected ProductConfigurationConfig $productConfigurationConfig;

    /**
     * @param \Spryker\Service\ProductConfiguration\Dependency\Service\ProductConfigurationToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Service\ProductConfiguration\Dependency\Service\ProductConfigurationToUtilTextServiceInterface $utilTextService
     * @param \Spryker\Service\ProductConfiguration\ProductConfigurationConfig $productConfigurationConfig
     */
    public function __construct(
        ProductConfigurationToUtilEncodingServiceInterface $utilEncodingService,
        ProductConfigurationToUtilTextServiceInterface $utilTextService,
        ProductConfigurationConfig $productConfigurationConfig
    ) {
        $this->utilEncodingService = $utilEncodingService;
        $this->utilTextService = $utilTextService;
        $this->productConfigurationConfig = $productConfigurationConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
     *
     * @return string
     */
    public function getProductConfigurationInstanceHash(ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer): string
    {
        $productConfigurationInstanceData = $this->filterProductConfigurationInstanceData(
            $productConfigurationInstanceTransfer->toArray(),
        );
        $encodedProductConfigurationInstanceData = $this->utilEncodingService->encodeJson($productConfigurationInstanceData);

        return $this->utilTextService->hashValue($encodedProductConfigurationInstanceData, 'md5');
    }

    /**
     * @param array<string, mixed> $productConfigurationInstanceData
     *
     * @return array<string, mixed>
     */
    public function filterProductConfigurationInstanceData(array $productConfigurationInstanceData): array
    {
        $configurationFieldsNotAllowedForEncoding = $this->productConfigurationConfig->getConfigurationFieldsNotAllowedForEncoding();
        if ($configurationFieldsNotAllowedForEncoding === []) {
            return $productConfigurationInstanceData;
        }

        $configurationFieldsNotAllowedForEncoding = array_map(function (string $field) {
            return $this->utilTextService->camelCaseToSeparator($field, '_');
        }, $configurationFieldsNotAllowedForEncoding);

        return array_diff_key($productConfigurationInstanceData, array_flip($configurationFieldsNotAllowedForEncoding));
    }
}
