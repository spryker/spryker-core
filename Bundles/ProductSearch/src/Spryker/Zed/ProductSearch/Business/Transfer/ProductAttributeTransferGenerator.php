<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Business\Transfer;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedProductSearchAttributeKeyTransfer;
use Generated\Shared\Transfer\ProductSearchAttributeTransfer;
use Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttribute;
use Spryker\Shared\ProductSearch\ProductSearchConstants;
use Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToGlossaryInterface;
use Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToLocaleInterface;

class ProductAttributeTransferGenerator implements ProductAttributeTransferGeneratorInterface
{

    /**
     * @var \Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToGlossaryInterface
     */
    protected $glossaryFacade;

    /**
     * @param \Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToLocaleInterface $localeFacade
     * @param \Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToGlossaryInterface $glossaryFacade
     */
    public function __construct(
        ProductSearchToLocaleInterface $localeFacade,
        ProductSearchToGlossaryInterface $glossaryFacade
    ) {
        $this->localeFacade = $localeFacade;
        $this->glossaryFacade = $glossaryFacade;
    }

    /**
     * @param \Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttribute $productAttributeEntity
     *
     * @return \Generated\Shared\Transfer\ProductSearchAttributeTransfer
     */
    public function convertProductAttribute(SpyProductSearchAttribute $productAttributeEntity)
    {
        $attributeTransfer = (new ProductSearchAttributeTransfer())
            ->fromArray($productAttributeEntity->toArray(), true);

        $attributeTransfer->setKey($productAttributeEntity->getSpyProductAttributeKey()->getKey());

        $attributeTransfer = $this->setLocalizedAttributeKeys($attributeTransfer);

        return $attributeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSearchAttributeTransfer $attributeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSearchAttributeTransfer
     */
    protected function setLocalizedAttributeKeys(ProductSearchAttributeTransfer $attributeTransfer)
    {
        $availableLocales = $this->localeFacade->getLocaleCollection();

        foreach ($availableLocales as $localeTransfer) {

            $localizedAttributeKeyTransfer = new LocalizedProductSearchAttributeKeyTransfer();
            $localizedAttributeKeyTransfer
                ->setLocaleName($localeTransfer->getLocaleName())
                ->setKeyTranslation($this->getAttributeKeyTranslation($attributeTransfer->getKey(), $localeTransfer));

            $attributeTransfer->addLocalizedKey($localizedAttributeKeyTransfer);
        }

        return $attributeTransfer;
    }

    /**
     * @param string $attributeKey
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string
     */
    protected function getAttributeKeyTranslation($attributeKey, LocaleTransfer $localeTransfer)
    {
        $glossaryKey = ProductSearchConstants::PRODUCT_SEARCH_FILTER_GLOSSARY_PREFIX . $attributeKey;

        if ($this->glossaryFacade->hasTranslation($glossaryKey, $localeTransfer)) {
            return $this->glossaryFacade
                ->getTranslation($glossaryKey, $localeTransfer)
                ->getValue();
        }

        return null;
    }

}
