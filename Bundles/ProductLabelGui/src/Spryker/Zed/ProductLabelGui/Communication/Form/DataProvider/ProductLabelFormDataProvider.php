<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductLabelTransfer;
use Spryker\Zed\ProductLabelGui\Dependency\Facade\ProductLabelGuiToLocaleInterface;

class ProductLabelFormDataProvider
{

    /**
     * @var \Spryker\Zed\ProductLabelGui\Dependency\Facade\ProductLabelGuiToLocaleInterface
     */
    protected $localeFacade;

    /**
     * ProductLabelFormDataProvider constructor.
     *
     * @param \Spryker\Zed\ProductLabelGui\Dependency\Facade\ProductLabelGuiToLocaleInterface $localeFacade
     */
    public function __construct(ProductLabelGuiToLocaleInterface $localeFacade)
    {
        $this->localeFacade = $localeFacade;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductLabelTransfer
     */
    public function getData()
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
     * @return array
     */
    public function getOptions()
    {
        return [];
    }

}
