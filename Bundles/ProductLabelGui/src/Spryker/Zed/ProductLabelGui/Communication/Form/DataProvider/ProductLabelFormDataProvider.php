<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductLabelTransfer;
use Spryker\Zed\ProductLabel\Business\ProductLabelFacadeInterface;
use Spryker\Zed\ProductLabelGui\Dependency\Facade\ProductLabelGuiToLocaleInterface;

class ProductLabelFormDataProvider
{

    /**
     * @var \Spryker\Zed\ProductLabelGui\Dependency\Facade\ProductLabelGuiToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\ProductLabel\Business\ProductLabelFacadeInterface
     */
    protected $productLabelFacade;

    /**
     * @param \Spryker\Zed\ProductLabelGui\Dependency\Facade\ProductLabelGuiToLocaleInterface $localeFacade
     * @param \Spryker\Zed\ProductLabel\Business\ProductLabelFacadeInterface $productLabelFacade
     */
    public function __construct(
        ProductLabelGuiToLocaleInterface $localeFacade,
        ProductLabelFacadeInterface $productLabelFacade
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

        return $this->readProductLabelTransfer($idProductLabel);
    }

    /**
     * @return \Generated\Shared\Transfer\ProductLabelTransfer
     */
    protected function createProductLabelTransfer()
    {
        $productLabelTransfer = new ProductLabelTransfer();
        $productLabelTransfer->setIsActive(true);

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
     * @return \Generated\Shared\Transfer\ProductLabelTransfer
     */
    protected function readProductLabelTransfer($idProductLabel)
    {
        $productLabelTransfer = $this->productLabelFacade->readLabel($idProductLabel);

        foreach ($productLabelTransfer->getLocalizedAttributesCollection() as $localizedAttributesTransfer) {
            $localeTransfer = $this->localeFacade->getLocaleById($localizedAttributesTransfer->getFkLocale());
            $localizedAttributesTransfer->setLocale($localeTransfer);
        }

        return $productLabelTransfer;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [];
    }

}
