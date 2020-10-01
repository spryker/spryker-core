<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute\Business\Reader;

use ArrayObject;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedProductManagementAttributeKeyTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeCollectionTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeFilterTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Spryker\Shared\ProductAttribute\Code\KeyBuilder\GlossaryKeyBuilderInterface;
use Spryker\Zed\ProductAttribute\Business\Model\Attribute\Mapper\ProductAttributeTransferMapperInterface;
use Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToGlossaryInterface;
use Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToLocaleInterface;
use Spryker\Zed\ProductAttribute\Persistence\ProductAttributeRepositoryInterface;

class ProductManagementAttributeReader implements ProductManagementAttributeReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductAttribute\Persistence\ProductAttributeRepositoryInterface
     */
    protected $productAttributeRepository;

    /**
     * @var \Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToGlossaryInterface
     */
    protected $glossaryFacade;

    /**
     * @var \Spryker\Shared\ProductAttribute\Code\KeyBuilder\GlossaryKeyBuilderInterface
     */
    protected $glossaryKeyBuilder;

    /**
     * @var \Spryker\Zed\ProductAttribute\Business\Model\Attribute\Mapper\ProductAttributeTransferMapperInterface
     */
    protected $productAttributeTransferMapper;

    /**
     * @param \Spryker\Zed\ProductAttribute\Persistence\ProductAttributeRepositoryInterface $productAttributeRepository
     * @param \Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToLocaleInterface $localeFacade
     * @param \Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToGlossaryInterface $glossaryFacade
     * @param \Spryker\Shared\ProductAttribute\Code\KeyBuilder\GlossaryKeyBuilderInterface $glossaryKeyBuilder
     * @param \Spryker\Zed\ProductAttribute\Business\Model\Attribute\Mapper\ProductAttributeTransferMapperInterface $productAttributeTransferMapper
     */
    public function __construct(
        ProductAttributeRepositoryInterface $productAttributeRepository,
        ProductAttributeToLocaleInterface $localeFacade,
        ProductAttributeToGlossaryInterface $glossaryFacade,
        GlossaryKeyBuilderInterface $glossaryKeyBuilder,
        ProductAttributeTransferMapperInterface $productAttributeTransferMapper
    ) {
        $this->productAttributeRepository = $productAttributeRepository;
        $this->localeFacade = $localeFacade;
        $this->glossaryFacade = $glossaryFacade;
        $this->glossaryKeyBuilder = $glossaryKeyBuilder;
        $this->productAttributeTransferMapper = $productAttributeTransferMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeFilterTransfer $productManagementAttributeFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeCollectionTransfer
     */
    public function getProductManagementAttributes(
        ProductManagementAttributeFilterTransfer $productManagementAttributeFilterTransfer
    ): ProductManagementAttributeCollectionTransfer {
        $productManagementAttributeCollectionTransfer = $this->productAttributeRepository
            ->getProductManagementAttributes($productManagementAttributeFilterTransfer);

        if (!$productManagementAttributeCollectionTransfer->getProductManagementAttributes()->count()) {
            return $productManagementAttributeCollectionTransfer;
        }

        $productManagementAttributeTransfers = $productManagementAttributeCollectionTransfer->getProductManagementAttributes();

        $productManagementAttributeTransfers = $this->expandProductManagementAttributesWithValues($productManagementAttributeTransfers);
        $productManagementAttributeTransfers = $this->expandProductManagementAttributesWithTranslations($productManagementAttributeTransfers);

        return $productManagementAttributeCollectionTransfer
            ->setProductManagementAttributes($productManagementAttributeTransfers);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ProductManagementAttributeTransfer[] $productManagementAttributeTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ProductManagementAttributeTransfer[]
     */
    protected function expandProductManagementAttributesWithValues(ArrayObject $productManagementAttributeTransfers): ArrayObject
    {
        $productManagementAttributeIds = $this->extractProductManagementAttributeIds($productManagementAttributeTransfers);
        $productManagementAttributeValueTransfers = $this->productAttributeRepository
            ->getProductManagementAttributeValues($productManagementAttributeIds);

        $indexedProductManagementAttributeValueTransfers = $this->indexProductManagementAttributeValues($productManagementAttributeValueTransfers);

        foreach ($productManagementAttributeTransfers as $productManagementAttributeTransfer) {
            $values = $indexedProductManagementAttributeValueTransfers[$productManagementAttributeTransfer->getIdProductManagementAttribute()] ?? [];

            $productManagementAttributeTransfer->setValues(new ArrayObject($values));
        }

        return $productManagementAttributeTransfers;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ProductManagementAttributeTransfer[] $productManagementAttributeTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ProductManagementAttributeTransfer[]
     */
    protected function expandProductManagementAttributesWithTranslations(ArrayObject $productManagementAttributeTransfers): ArrayObject
    {
        $localeTransfers = $this->localeFacade->getLocaleCollection();
        $glossaryKeys = $this->prepareGlossaryKeys($productManagementAttributeTransfers);

        $glossaryKeyTransfers = $this->glossaryFacade->getGlossaryKeyTransfersByGlossaryKeys($glossaryKeys);
        $translationTransfers = $this->glossaryFacade->getTranslationsByGlossaryKeysAndLocaleTransfers(
            $glossaryKeys,
            $localeTransfers
        );

        $indexedTranslationTransfers = $this->productAttributeTransferMapper->indexTranslationsByKeyNameAndLocaleName(
            $translationTransfers,
            $localeTransfers,
            $glossaryKeyTransfers
        );

        foreach ($productManagementAttributeTransfers as $productManagementAttributeTransfer) {
            $this->setLocalizedAttributeKeys($productManagementAttributeTransfer, $localeTransfers, $indexedTranslationTransfers);
        }

        return $productManagementAttributeTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $productManagementAttributeTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer[] $localeTransfers
     * @param string[][] $translationsByLocaleNameAndGlossaryKey
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer
     */
    protected function setLocalizedAttributeKeys(
        ProductManagementAttributeTransfer $productManagementAttributeTransfer,
        array $localeTransfers,
        array $translationsByLocaleNameAndGlossaryKey = []
    ): ProductManagementAttributeTransfer {
        foreach ($localeTransfers as $localeTransfer) {
            $glossaryKey = $this->glossaryKeyBuilder->buildGlossaryKey($productManagementAttributeTransfer->getKey());
            $keyTranslation = $translationsByLocaleNameAndGlossaryKey[$glossaryKey][$localeTransfer->getLocaleName()] ?? null;

            $localizedAttributeKeyTransfer = (new LocalizedProductManagementAttributeKeyTransfer())
                ->setLocaleName($localeTransfer->getLocaleName())
                ->setKeyTranslation($keyTranslation);

            $productManagementAttributeTransfer->addLocalizedKey($localizedAttributeKeyTransfer);
        }

        return $productManagementAttributeTransfer;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ProductManagementAttributeTransfer[] $productManagementAttributeTransfers
     *
     * @return string[]
     */
    protected function prepareGlossaryKeys(ArrayObject $productManagementAttributeTransfers): array
    {
        $glossaryKeys = [];
        foreach ($productManagementAttributeTransfers as $productManagementAttributeTransfer) {
            $glossaryKeys[] = $this->glossaryKeyBuilder->buildGlossaryKey(
                $productManagementAttributeTransfer->getKey()
            );
        }

        return $glossaryKeys;
    }

    /**
     * @param string $glossaryKey
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string|null
     */
    protected function findTranslationByGlossaryKeyAndLocaleTransfer(string $glossaryKey, LocaleTransfer $localeTransfer): ?string
    {
        if ($this->glossaryFacade->hasTranslation($glossaryKey, $localeTransfer)) {
            return $this->glossaryFacade
                ->getTranslation($glossaryKey, $localeTransfer)
                ->getValue();
        }

        return null;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ProductManagementAttributeTransfer[] $productManagementAttributeTransfers
     *
     * @return int[]
     */
    protected function extractProductManagementAttributeIds(ArrayObject $productManagementAttributeTransfers): array
    {
        $productManagementAttributeIds = [];

        foreach ($productManagementAttributeTransfers as $productManagementAttributeTransfer) {
            $productManagementAttributeIds[] = $productManagementAttributeTransfer->getIdProductManagementAttribute();
        }

        return $productManagementAttributeIds;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeValueTransfer[] $productManagementAttributeValueTransfers
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeValueTransfer[][]
     */
    protected function indexProductManagementAttributeValues(array $productManagementAttributeValueTransfers): array
    {
        $indexedProductManagementAttributeValueTransfers = [];

        foreach ($productManagementAttributeValueTransfers as $productManagementAttributeValueTransfer) {
            $indexedProductManagementAttributeValueTransfers[$productManagementAttributeValueTransfer->getFkProductManagementAttribute()][]
                = $productManagementAttributeValueTransfer;
        }

        return $indexedProductManagementAttributeValueTransfers;
    }
}
