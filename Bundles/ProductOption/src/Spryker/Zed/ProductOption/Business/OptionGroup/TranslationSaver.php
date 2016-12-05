<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Business\OptionGroup;

use Generated\Shared\Transfer\ProductOptionGroupTransfer;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToGlossaryInterface;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToLocaleInterface;
use Spryker\Zed\ProductOption\ProductOptionConfig;

class TranslationSaver implements TranslationSaverInterface
{

    /**
     * @var \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToGlossaryInterface
     */
    protected $glossaryFacade;

    /**
     * @var \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToGlossaryInterface $glossaryFacade
     * @param \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToLocaleInterface $localeFacade
     */
    public function __construct(
        ProductOptionToGlossaryInterface $glossaryFacade,
        ProductOptionToLocaleInterface $localeFacade
    ) {
        $this->glossaryFacade = $glossaryFacade;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer $productOptionGroupTransfer
     *
     * @return void
     */
    public function addValueTranslations(ProductOptionGroupTransfer $productOptionGroupTransfer)
    {
        foreach ($productOptionGroupTransfer->getProductOptionValueTranslations() as $productOptionTranslationTransfer) {

            $value = $productOptionTranslationTransfer->getName();
            $key = $productOptionTranslationTransfer->getKey();

            if (!$value) {
                $value = $key;
            }

            if (strpos($key, ProductOptionConfig::PRODUCT_OPTION_TRANSLATION_PREFIX) === false) {
                $key = ProductOptionConfig::PRODUCT_OPTION_TRANSLATION_PREFIX . $key;
            }

            $this->saveTranslation($key, $value, $productOptionTranslationTransfer->getLocaleCode());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer $productOptionGroupTransfer
     *
     * @return void
     */
    public function addGroupNameTranslations(ProductOptionGroupTransfer $productOptionGroupTransfer)
    {
        if (!$productOptionGroupTransfer->getName()) {
            return;
        }

        foreach ($productOptionGroupTransfer->getGroupNameTranslations() as $groupNameTranslationTransfer) {

            $value = $groupNameTranslationTransfer->getName();
            $key = $productOptionGroupTransfer->getName();

            if (!$value) {
                $value = $key;
            }

            $this->saveTranslation($key, $value, $groupNameTranslationTransfer->getLocaleCode());
        }
    }

    /**
     * @param string $translationKey
     *
     * @return bool
     */
    public function deleteTranslation($translationKey)
    {
        return $this->glossaryFacade->deleteKey($translationKey);
    }

    /**
     * @param string $key
     * @param string $value
     * @param string $localeCode
     *
     * @return void
     */
    protected function saveTranslation($key, $value, $localeCode)
    {
        if (!$this->glossaryFacade->hasKey($key)) {
            $this->glossaryFacade->createKey($key);
        }

        $localeTransfer = $this->localeFacade->getLocaleByCode($localeCode);
        if (!$this->glossaryFacade->hasTranslation($key, $localeTransfer)) {
            $this->glossaryFacade->createAndTouchTranslation($key, $localeTransfer, $value);
        } else {
            $this->glossaryFacade->updateAndTouchTranslation($key, $localeTransfer, $value);
        }
    }

}
