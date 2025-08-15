<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreGui\Communication\Form\Transformer;

use Spryker\Zed\StoreGui\Dependency\Service\StoreGuiToUtilEncodingServiceInterface;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * @implements \Symfony\Component\Form\DataTransformerInterface<array<int, int>|null, string|null>
 */
class IdStoresDataTransformer implements DataTransformerInterface
{
    /**
     * @var \Spryker\Zed\StoreGui\Dependency\Service\StoreGuiToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\StoreGui\Dependency\Service\StoreGuiToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(StoreGuiToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param array<mixed> $value Store IDs.
     *
     * @return mixed|string|null
     */
    public function transform($value)
    {
        return $this->utilEncodingService->encodeJson($value);
    }

    /**
     * @param mixed $value Store IDs.
     *
     * @return array<mixed>|null
     */
    public function reverseTransform($value)
    {
        /** @phpstan-var array<mixed>|null */
        return $this->utilEncodingService->decodeJson($value, true);
    }
}
