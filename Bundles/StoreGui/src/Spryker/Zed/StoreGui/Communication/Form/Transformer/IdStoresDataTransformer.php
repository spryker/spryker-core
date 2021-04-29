<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreGui\Communication\Form\Transformer;

use Spryker\Zed\StoreGui\Dependency\Service\StoreGuiToUtilEncodingServiceInterface;
use Symfony\Component\Form\DataTransformerInterface;

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
     * @param array|null $storeIds
     *
     * @return string|null
     */
    public function transform($storeIds)
    {
        return $this->utilEncodingService->encodeJson($storeIds);
    }

    /**
     * @param string $storeIds
     *
     * @return mixed
     */
    public function reverseTransform($storeIds)
    {
        return $this->utilEncodingService->decodeJson($storeIds, true);
    }
}
