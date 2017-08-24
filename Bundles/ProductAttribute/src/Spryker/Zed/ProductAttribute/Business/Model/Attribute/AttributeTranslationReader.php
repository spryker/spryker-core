<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute\Business\Model\Attribute;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedProductManagementAttributeKeyTransfer;
use Spryker\Shared\ProductAttribute\Code\KeyBuilder\GlossaryKeyBuilderInterface;
use Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToGlossaryInterface;

class AttributeTranslationReader implements AttributeTranslationReaderInterface
{

    /**
     * @var \Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToGlossaryInterface
     */
    protected $glossaryFacade;

    /**
     * @var \Spryker\Shared\ProductAttribute\Code\KeyBuilder\GlossaryKeyBuilderInterface
     */
    protected $glossaryKeyBuilder;

    /**
     * @param \Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToGlossaryInterface $glossaryFacade
     * @param \Spryker\Shared\ProductAttribute\Code\KeyBuilder\GlossaryKeyBuilderInterface $glossaryKeyBuilder
     */
    public function __construct(
        ProductAttributeToGlossaryInterface $glossaryFacade,
        GlossaryKeyBuilderInterface $glossaryKeyBuilder
    ) {
        $this->glossaryFacade = $glossaryFacade;
        $this->glossaryKeyBuilder = $glossaryKeyBuilder;
    }

    /**
     * @param string $key
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\LocalizedProductManagementAttributeKeyTransfer|null
     */
    public function findAttributeTranslationByKey($key, LocaleTransfer $localeTransfer)
    {
        $glossaryKey = $this->glossaryKeyBuilder->buildGlossaryKey($key);

        if (!$this->glossaryFacade->hasTranslation($glossaryKey, $localeTransfer)) {
            return null;
        }

        $translationTransfer = $this->glossaryFacade->getTranslation($glossaryKey, $localeTransfer);

        $localizedProductAttributeKeyTransfer = (new LocalizedProductManagementAttributeKeyTransfer())
            ->setLocaleName($localeTransfer->getLocaleName())
            ->setKeyTranslation($translationTransfer->getValue());

        return $localizedProductAttributeKeyTransfer;
    }

}
