<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAttributesBackendApi\Processor\Expander;

use ArrayObject;
use Generated\Shared\Transfer\ProductManagementAttributeValueTransfer;
use Spryker\Glue\ProductAttributesBackendApi\Dependency\Facade\ProductAttributesBackendApiToLocaleFacadeInterface;

class ProductAttributeExpander implements ProductAttributeExpanderInterface
{
    /**
     * @var \Spryker\Glue\ProductAttributesBackendApi\Dependency\Facade\ProductAttributesBackendApiToLocaleFacadeInterface
     */
    protected ProductAttributesBackendApiToLocaleFacadeInterface $localeFacade;

    /**
     * @param \Spryker\Glue\ProductAttributesBackendApi\Dependency\Facade\ProductAttributesBackendApiToLocaleFacadeInterface $localeFacade
     */
    public function __construct(ProductAttributesBackendApiToLocaleFacadeInterface $localeFacade)
    {
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductManagementAttributeValueTransfer> $productManagementAttributeValueTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ProductManagementAttributeValueTransfer>
     */
    public function expandProductManagementAttributeValueTransfersWithLocaleName(
        ArrayObject $productManagementAttributeValueTransfers
    ): ArrayObject {
        $localeTransfers = $this->localeFacade->getLocaleCollection();
        foreach ($productManagementAttributeValueTransfers as $productManagementAttributeValueTransfer) {
            $this->expandProductManagementAttributeValueTransferWithLocaleName($productManagementAttributeValueTransfer, $localeTransfers);
        }

        return $productManagementAttributeValueTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeValueTransfer $productManagementAttributeValueTransfer
     * @param array<string, \Generated\Shared\Transfer\LocaleTransfer> $localeTransfers
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeValueTransfer
     */
    protected function expandProductManagementAttributeValueTransferWithLocaleName(
        ProductManagementAttributeValueTransfer $productManagementAttributeValueTransfer,
        array $localeTransfers
    ): ProductManagementAttributeValueTransfer {
        foreach ($productManagementAttributeValueTransfer->getLocalizedValues() as $productManagementAttributeValueTranslationTransfer) {
            $localeName = $productManagementAttributeValueTranslationTransfer->getLocaleName();
            if (!$localeName) {
                continue;
            }

            $productManagementAttributeValueTranslationTransfer->setFkLocale($localeTransfers[$localeName]->getIdLocale());
        }

        return $productManagementAttributeValueTransfer;
    }
}
