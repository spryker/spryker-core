<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductLabelTransfer;
use Spryker\Zed\ProductLabelGui\Communication\Form\ProductLabelFormType;
use Spryker\Zed\ProductLabelGui\Dependency\Facade\ProductLabelGuiToLocaleInterface;
use Spryker\Zed\ProductLabelGui\Dependency\Facade\ProductLabelGuiToProductLabelInterface;

class ProductLabelFormDataProvider
{
    /**
     * @var \Spryker\Zed\ProductLabelGui\Dependency\Facade\ProductLabelGuiToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\ProductLabelGui\Dependency\Facade\ProductLabelGuiToProductLabelInterface
     */
    protected $productLabelFacade;

    /**
     * @param \Spryker\Zed\ProductLabelGui\Dependency\Facade\ProductLabelGuiToLocaleInterface $localeFacade
     * @param \Spryker\Zed\ProductLabelGui\Dependency\Facade\ProductLabelGuiToProductLabelInterface $productLabelFacade
     */
    public function __construct(
        ProductLabelGuiToLocaleInterface $localeFacade,
        ProductLabelGuiToProductLabelInterface $productLabelFacade
    ) {
        $this->localeFacade = $localeFacade;
        $this->productLabelFacade = $productLabelFacade;
    }

    /**
     * @param int|null $idProductLabel
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer
     */
    public function getData($idProductLabel = null)
    {
        if ($idProductLabel === null) {
            return $this->createProductLabelTransfer();
        }

        return $this->findProductLabelTransfer($idProductLabel);
    }

    /**
     * @return \Generated\Shared\Transfer\ProductLabelTransfer
     */
    protected function createProductLabelTransfer()
    {
        $productLabelTransfer = new ProductLabelTransfer();

        foreach ($this->localeFacade->getLocaleCollection() as $localeTransfer) {
            $localizedAttributesTransfer = new ProductLabelLocalizedAttributesTransfer();
            $localizedAttributesTransfer->setFkLocale($localeTransfer->getIdLocale());
            $localizedAttributesTransfer->setLocale($localeTransfer);

            $productLabelTransfer->addLocalizedAttributes($localizedAttributesTransfer);
        }

        return $productLabelTransfer;
    }

    /**
     * @param int $idProductLabel
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer|null
     */
    protected function findProductLabelTransfer($idProductLabel)
    {
        $productLabelTransfer = $this->productLabelFacade->findLabelById($idProductLabel);

        foreach ($productLabelTransfer->getLocalizedAttributesCollection() as $localizedAttributesTransfer) {
            $localeTransfer = $this->localeFacade->getLocaleById($localizedAttributesTransfer->getFkLocale());
            $localizedAttributesTransfer->setLocale($localeTransfer);
        }

        return $productLabelTransfer;
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions()
    {
        return [
            ProductLabelFormType::OPTION_LOCALE => $this->localeFacade->getCurrentLocaleName(),
        ];
    }
}
