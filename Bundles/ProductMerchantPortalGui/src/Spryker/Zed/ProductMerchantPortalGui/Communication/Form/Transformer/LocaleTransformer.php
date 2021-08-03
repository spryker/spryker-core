<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Transformer;

use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface;
use Symfony\Component\Form\DataTransformerInterface;

class LocaleTransformer implements DataTransformerInterface
{
    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface $localeFacade
     */
    public function __construct(ProductMerchantPortalGuiToLocaleFacadeInterface $localeFacade)
    {
        $this->localeFacade = $localeFacade;
    }

    /**
     * {@inheritDoc}
     *
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $value The value in the original representation
     *
     * @return int|null The value in the transformed representation
     */
    public function transform($value)
    {
        if (!$value) {
            return null;
        }

        return $value->getIdLocale();
    }

    /**
     * {@inheritDoc}
     *
     * @param int|null $value The value in the transformed representation
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer|null The value in the original representation
     */
    public function reverseTransform($value)
    {
        if (!$value) {
            return null;
        }

        return $this->localeFacade->getLocaleById($value);
    }
}
