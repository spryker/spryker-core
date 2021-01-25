<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute\Business\Model\Attribute\Mapper;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedProductManagementAttributeKeyTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeValueTransfer;
use Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttribute;
use Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValue;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\ProductAttribute\Business\Mapper\TranslationMapperInterface;
use Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToGlossaryInterface;
use Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToLocaleInterface;

class ProductAttributeTransferMapper implements ProductAttributeTransferMapperInterface
{
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
     * @var \Spryker\Zed\ProductAttribute\Business\Mapper\TranslationMapperInterface
     */
    protected $translationMapper;

    /**
     * @param \Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToLocaleInterface $localeFacade
     * @param \Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToGlossaryInterface $glossaryFacade
     * @param \Spryker\Shared\ProductAttribute\Code\KeyBuilder\GlossaryKeyBuilderInterface $glossaryKeyBuilder
     * @param \Spryker\Zed\ProductAttribute\Business\Mapper\TranslationMapperInterface $translationMapper
     */
    public function __construct(
        ProductAttributeToLocaleInterface $localeFacade,
        ProductAttributeToGlossaryInterface $glossaryFacade,
        $glossaryKeyBuilder,
        TranslationMapperInterface $translationMapper
    ) {
        $this->localeFacade = $localeFacade;
        $this->glossaryFacade = $glossaryFacade;
        $this->glossaryKeyBuilder = $glossaryKeyBuilder;
        $this->translationMapper = $translationMapper;
    }

    /**
     * @param \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttribute $productAttributeEntity
     * @param string[][] $translationsByLocaleNameAndGlossaryKey
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer
     */
    public function convertProductAttribute(SpyProductManagementAttribute $productAttributeEntity, array $translationsByLocaleNameAndGlossaryKey = [])
    {
        $attributeTransfer = (new ProductManagementAttributeTransfer())
            ->fromArray($productAttributeEntity->toArray(), true);

        $productAttributeKeyEntity = $productAttributeEntity->getSpyProductAttributeKey();

        $attributeTransfer
            ->setKey($productAttributeKeyEntity->getKey())
            ->setIsSuper($productAttributeKeyEntity->getIsSuper());

        $attributeTransfer = $this->setLocalizedAttributeKeys($attributeTransfer, $translationsByLocaleNameAndGlossaryKey);
        $attributeTransfer = $this->setAttributeValues($attributeTransfer, $productAttributeEntity);

        return $attributeTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttribute[] $productAttributeEntityCollection
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer[]
     */
    public function convertProductAttributeCollection(ObjectCollection $productAttributeEntityCollection)
    {
        $transferList = [];
        $glossaryKeys = $this->prepareGlossaryKeys($productAttributeEntityCollection);
        $localeTransfers = $this->localeFacade->getLocaleCollection();
        $glossaryKeyTransfers = $this->glossaryFacade->getGlossaryKeyTransfersByGlossaryKeys($glossaryKeys);
        $translationTransfers = $this->glossaryFacade->getTranslationsByGlossaryKeysAndLocaleTransfers(
            $glossaryKeys,
            $localeTransfers
        );
        $indexedTranslationTransfers = $this->translationMapper
            ->mapTranslationsByKeyNameAndLocaleName($translationTransfers, $localeTransfers, $glossaryKeyTransfers);

        foreach ($productAttributeEntityCollection as $productAttributeEntity) {
            $transferList[] = $this->convertProductAttribute($productAttributeEntity, $indexedTranslationTransfers);
        }

        return $transferList;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $productAttributeEntityCollection
     *
     * @return array
     */
    protected function prepareGlossaryKeys(ObjectCollection $productAttributeEntityCollection): array
    {
        $glossaryKeys = [];
        foreach ($productAttributeEntityCollection as $productAttributeEntity) {
            $glossaryKeys[] = $this->glossaryKeyBuilder->buildGlossaryKey(
                $productAttributeEntity->getSpyProductAttributeKey()->getKey()
            );
        }

        return $glossaryKeys;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $attributeTransfer
     * @param \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttribute $productAttributeEntity
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer
     */
    protected function setAttributeValues(ProductManagementAttributeTransfer $attributeTransfer, SpyProductManagementAttribute $productAttributeEntity)
    {
        foreach ($productAttributeEntity->getSpyProductManagementAttributeValues() as $attributeValueEntity) {
            $attributeValueTransferData = $attributeValueEntity->toArray();
            $attributeValueTransferData[ProductManagementAttributeValueTransfer::LOCALIZED_VALUES] = $attributeValueEntity
                ->getSpyProductManagementAttributeValueTranslations()
                ->toArray();

            $attributeValueTransfer = (new ProductManagementAttributeValueTransfer())
                ->fromArray($attributeValueTransferData, true);

            $attributeTransfer->addValue($attributeValueTransfer);
        }

        return $attributeTransfer;
    }

    /**
     * @param \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValue $productAttributeValueEntity
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeValueTransfer
     */
    public function convertProductAttributeValue(SpyProductManagementAttributeValue $productAttributeValueEntity)
    {
        $productAttributeTransfer = (new ProductManagementAttributeValueTransfer())
            ->fromArray($productAttributeValueEntity->toArray(), true);

        return $productAttributeTransfer;
    }

    /**
     * @param \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValue[]|\Propel\Runtime\Collection\ObjectCollection $productAttributeValueEntityCollection
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeValueTransfer[]
     */
    public function convertProductAttributeValueCollection(ObjectCollection $productAttributeValueEntityCollection)
    {
        $transferList = [];
        foreach ($productAttributeValueEntityCollection as $productAttributeValueEntity) {
            $transferList[] = $this->convertProductAttributeValue($productAttributeValueEntity);
        }

        return $transferList;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $attributeTransfer
     * @param string[][] $translationsByLocaleNameAndGlossaryKey
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer
     */
    protected function setLocalizedAttributeKeys(ProductManagementAttributeTransfer $attributeTransfer, array $translationsByLocaleNameAndGlossaryKey = [])
    {
        $availableLocales = $this->localeFacade->getLocaleCollection();

        foreach ($availableLocales as $localeTransfer) {
            $glossaryKey = $this->glossaryKeyBuilder->buildGlossaryKey($attributeTransfer->getKey());
            $keyTranslation = $translationsByLocaleNameAndGlossaryKey[$glossaryKey][$localeTransfer->getLocaleName()] ?? $this->findTranslationByGlossaryKeyAndLocaleTransfer($glossaryKey, $localeTransfer);

            $localizedAttributeKeyTransfer = new LocalizedProductManagementAttributeKeyTransfer();
            $localizedAttributeKeyTransfer
                ->setLocaleName($localeTransfer->getLocaleName())
                ->setKeyTranslation($keyTranslation);

            $attributeTransfer->addLocalizedKey($localizedAttributeKeyTransfer);
        }

        return $attributeTransfer;
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
}
