<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Form\DataProvider;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Shared\ProductManagement\ProductManagementConstants;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToLocaleInterface;

class LocaleProvider
{
    /**
     * @var \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToLocaleInterface $localeFacade
     */
    public function __construct(ProductManagementToLocaleInterface $localeFacade)
    {
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param bool $includeDefault
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer[]
     *
     * TODO: Check usages and fix to transfers
     */
    public function getLocaleCollection($includeDefault = false)
    {
        $result = [];

        if ($includeDefault) {
            $result[] = (new LocaleTransfer())
                ->setLocaleName(ProductManagementConstants::PRODUCT_MANAGEMENT_DEFAULT_LOCALE);
        }

        foreach ($this->localeFacade->getLocaleCollection() as $localeCode => $localeTransfer) {
            $result[] = $localeTransfer;
        }

        return $result;
    }

    /**
     * @param string $localeCode
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getLocaleTransfer($localeCode)
    {
        return $this->localeFacade->getLocale($localeCode);
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getCurrentLocale()
    {
        return $this->localeFacade->getCurrentLocale();
    }
}
