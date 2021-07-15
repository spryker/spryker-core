<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Persistence\Mapper;

use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Orm\Zed\Product\Persistence\SpyProductLocalizedAttributes;
use Spryker\Zed\Product\Dependency\Service\ProductToUtilEncodingInterface;

class LocalizedAttributesMapper
{
    /**
     * @var \Spryker\Zed\Product\Dependency\Service\ProductToUtilEncodingInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\Product\Dependency\Service\ProductToUtilEncodingInterface $utilEncodingService
     */
    public function __construct(ProductToUtilEncodingInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Generated\Shared\Transfer\LocalizedAttributesTransfer $localizedAttributesTransfer
     * @param \Orm\Zed\Product\Persistence\SpyProductLocalizedAttributes $productLocalizedAttributesEntity
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductLocalizedAttributes
     */
    public function mapLocalizedAttributesTransferToProductLocalizedAttributesEntity(
        LocalizedAttributesTransfer $localizedAttributesTransfer,
        SpyProductLocalizedAttributes $productLocalizedAttributesEntity
    ): SpyProductLocalizedAttributes {
        $locale = $localizedAttributesTransfer->getLocale();
        $encodedAttributes = $this->utilEncodingService->encodeJson($localizedAttributesTransfer->getAttributes());
        $localizedAttributesData = $localizedAttributesTransfer->toArray();
        unset($localizedAttributesData[LocalizedAttributesTransfer::ATTRIBUTES]);

        $productLocalizedAttributesEntity->fromArray($localizedAttributesData);
        $productLocalizedAttributesEntity->setFkLocale($locale->getIdLocaleOrFail())
            ->setAttributes($encodedAttributes);

        return $productLocalizedAttributesEntity;
    }
}
