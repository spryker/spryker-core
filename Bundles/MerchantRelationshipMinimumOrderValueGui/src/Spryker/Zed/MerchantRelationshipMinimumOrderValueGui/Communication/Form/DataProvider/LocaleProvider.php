<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Communication\Form\DataProvider;

use Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Dependency\Facade\MerchantRelationshipMinimumOrderValueGuiToLocaleFacadeInterface;

class LocaleProvider
{
    /**
     * @var \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Dependency\Facade\MerchantRelationshipMinimumOrderValueGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\MerchantRelationshipMinimumOrderValueGui\Dependency\Facade\MerchantRelationshipMinimumOrderValueGuiToLocaleFacadeInterface $localeFacade
     */
    public function __construct(MerchantRelationshipMinimumOrderValueGuiToLocaleFacadeInterface $localeFacade)
    {
        $this->localeFacade = $localeFacade;
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer[]
     */
    public function getLocaleCollection()
    {
        return $this->localeFacade->getLocaleCollection();
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
