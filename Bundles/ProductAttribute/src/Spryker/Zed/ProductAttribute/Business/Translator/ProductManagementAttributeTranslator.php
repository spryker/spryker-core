<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute\Business\Translator;

use ArrayObject;
use Generated\Shared\Transfer\LocalizedProductManagementAttributeKeyTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Spryker\Shared\ProductAttribute\Code\KeyBuilder\GlossaryKeyBuilderInterface;
use Spryker\Zed\ProductAttribute\Business\Mapper\TranslationMapperInterface;
use Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToGlossaryInterface;
use Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToLocaleInterface;

class ProductManagementAttributeTranslator implements ProductManagementAttributeTranslatorInterface
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
        GlossaryKeyBuilderInterface $glossaryKeyBuilder,
        TranslationMapperInterface $translationMapper
    ) {
        $this->localeFacade = $localeFacade;
        $this->glossaryFacade = $glossaryFacade;
        $this->glossaryKeyBuilder = $glossaryKeyBuilder;
        $this->translationMapper = $translationMapper;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ProductManagementAttributeTransfer[] $productManagementAttributeTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ProductManagementAttributeTransfer[]
     */
    public function translateProductManagementAttributes(ArrayObject $productManagementAttributeTransfers): ArrayObject
    {
        $localeTransfers = $this->localeFacade->getLocaleCollection();
        $glossaryKeys = $this->prepareGlossaryKeys($productManagementAttributeTransfers);

        $glossaryKeyTransfers = $this->glossaryFacade->getGlossaryKeyTransfersByGlossaryKeys($glossaryKeys);
        $translationTransfers = $this->glossaryFacade->getTranslationsByGlossaryKeysAndLocaleTransfers(
            $glossaryKeys,
            $localeTransfers
        );

        $mappedTranslationTransfers = $this->translationMapper->mapTranslationsByKeyNameAndLocaleName(
            $translationTransfers,
            $localeTransfers,
            $glossaryKeyTransfers
        );

        foreach ($productManagementAttributeTransfers as $productManagementAttributeTransfer) {
            $this->setLocalizedAttributeKeys($productManagementAttributeTransfer, $localeTransfers, $mappedTranslationTransfers);
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
}
