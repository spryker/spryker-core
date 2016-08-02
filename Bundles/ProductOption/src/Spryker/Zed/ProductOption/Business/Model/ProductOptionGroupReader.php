<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Business\Model;

use Generated\Shared\Transfer\ProductOptionGroupTransfer;
use Generated\Shared\Transfer\ProductOptionValueTransfer;
use Generated\Shared\Transfer\ProductOptionValueTranslationTransfer;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToGlossaryInterface;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToLocaleInterface;
use Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface;
use Spryker\Zed\ProductOption\Business\Exception\ProductOptionGroupNotFoundException;
use Spryker\Zed\ProductOption\ProductOptionConfig;

class ProductOptionGroupReader
{
    /**
     * @var \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface
     */
    protected $productOptionQueryContainer;

    /**
     * @var \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToGlossaryInterface
     */
    protected $glossaryFacade;

    /**
     * @var \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface $productOptionQueryContainer
     * @param \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToGlossaryInterface $glossaryFacade
     * @param \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToLocaleInterface $localeFacade
     */
    public function __construct(
        ProductOptionQueryContainerInterface $productOptionQueryContainer,
        ProductOptionToGlossaryInterface $glossaryFacade,
        ProductOptionToLocaleInterface $localeFacade
    ) {
        $this->productOptionQueryContainer = $productOptionQueryContainer;
        $this->glossaryFacade = $glossaryFacade;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param int $idProductOptionGroup
     *
     * @throws \Spryker\Zed\ProductOption\Business\Exception\ProductOptionGroupNotFoundException
     *
     * @return \Generated\Shared\Transfer\ProductOptionGroupTransfer
     */
    public function getProductOptionGroupById($idProductOptionGroup)
    {
        $productOptionGroupEntity = $this->productOptionQueryContainer
            ->queryProductOptionGroupById($idProductOptionGroup)
            ->findOne();

        if (!$productOptionGroupEntity) {
            throw new ProductOptionGroupNotFoundException(
                sprintf(
                    'Product option group with id %d not found',
                    $idProductOptionGroup
                )
            );
        }

        return $this->hydrateProductOptionGroupTransfer($productOptionGroupEntity);

    }

    /**
     * @param SpyProductOptionGroup $productOptionGroupEntity
     *
     * @return \Generated\Shared\Transfer\ProductOptionGroupTransfer
     */
    protected function hydrateProductOptionGroupTransfer(SpyProductOptionGroup $productOptionGroupEntity)
    {
        $productOptionGroupTransfer = new ProductOptionGroupTransfer();
        $productOptionGroupTransfer->fromArray($productOptionGroupEntity->toArray(), true);

        $availableLocales = $this->localeFacade->getLocaleCollection();
        foreach ($productOptionGroupEntity->getSpyProductOptionValues() as $productOptionValueEntity) {
            $productOptionValueTransfer = new ProductOptionValueTransfer();
            $productOptionValueTransfer->setOptionHash(
                $this->createOptionHash($productOptionValueEntity->getIdProductOptionValue())
            );
            $translationKeys[] = $productOptionValueEntity->getValue();
            $productOptionValueTransfer->fromArray($productOptionValueEntity->toArray(), true);

            $this->hydrateValueTranslations($availableLocales, $productOptionValueTransfer, $productOptionGroupTransfer);

            $productOptionGroupTransfer->addProductOptionValue($productOptionValueTransfer);
        }

        return $productOptionGroupTransfer;
    }

    /**
     * @param array $availableLocales
     * @param \Generated\Shared\Transfer\ProductOptionValueTransfer $productOptionValueTransfer
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer $productOptionGroupTransfer
     */
    protected function hydrateValueTranslations(
        array $availableLocales,
        ProductOptionValueTransfer $productOptionValueTransfer,
        ProductOptionGroupTransfer $productOptionGroupTransfer
    ) {
        foreach ($availableLocales as $localeTransfer) {
            $translationKeyWithPrefix = ProductOptionConfig::PRODUCT_OPTION_TRANSLATION_PREFIX . $productOptionValueTransfer->getValue();
            if (!$this->glossaryFacade->hasTranslation($translationKeyWithPrefix, $localeTransfer)) {
                continue;
            }

            $translationTransfer = $this->glossaryFacade->getTranslation(
                $translationKeyWithPrefix,
                $localeTransfer
            );

            $productOptionValueTranslationTransfer = new ProductOptionValueTranslationTransfer();
            $productOptionValueTranslationTransfer->setName($translationTransfer->getValue());
            $productOptionValueTranslationTransfer->setKey($productOptionValueTransfer->getValue());
            $productOptionValueTranslationTransfer->setLocaleCode($localeTransfer->getLocaleName());
            $productOptionValueTranslationTransfer->setRelatedOptionHash(
                $this->createOptionHash($productOptionValueTransfer->getIdProductOptionValue())
            );

            $productOptionGroupTransfer->addProductOptionValueTranslation($productOptionValueTranslationTransfer);

        }
    }

    /**
     * @param int $identifier
     *
     * @return string
     */
    protected function createOptionHash($identifier)
    {
        return hash('sha256', $identifier);
    }
}
