<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Persistence\Mapper;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Orm\Zed\Locale\Persistence\SpyLocale;
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

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductLocalizedAttributes $productLocalizedAttributesEntity
     * @param \Generated\Shared\Transfer\LocalizedAttributesTransfer $localizedAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\LocalizedAttributesTransfer
     */
    public function mapProductLocalizedAttributesEntityToTransfer(
        SpyProductLocalizedAttributes $productLocalizedAttributesEntity,
        LocalizedAttributesTransfer $localizedAttributesTransfer
    ): LocalizedAttributesTransfer {
        $localeTransfer = $this->mapLocaleEntityToTransfer(
            $productLocalizedAttributesEntity->getLocale(),
            new LocaleTransfer(),
        );

        $localizedAttributesData = $productLocalizedAttributesEntity->toArray();

        unset($localizedAttributesData[LocalizedAttributesTransfer::ATTRIBUTES]);

        $attributes = $productLocalizedAttributesEntity->getAttributes();

        return $localizedAttributesTransfer
            ->fromArray($localizedAttributesData, true)
            ->setAttributes($this->decodeAttributes($attributes))
            ->setLocale($localeTransfer);
    }

    /**
     * @param \Orm\Zed\Locale\Persistence\SpyLocale $localeEntity
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function mapLocaleEntityToTransfer(SpyLocale $localeEntity, LocaleTransfer $localeTransfer): LocaleTransfer
    {
        return $localeTransfer->fromArray($localeEntity->toArray(), true);
    }

    /**
     * @param string $attributes
     *
     * @return array
     */
    protected function decodeAttributes(string $attributes): array
    {
        $result = $this->utilEncodingService->decodeJson($attributes, true);

        if (!is_array($result)) {
            return [];
        }

        return $result;
    }
}
