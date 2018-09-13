<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThresholdGui\Communication\Form\DataProvider;

use Spryker\Zed\SalesOrderThresholdGui\Dependency\Facade\SalesOrderThresholdGuiToLocaleFacadeInterface;

class LocaleProvider
{
    /**
     * @var \Spryker\Zed\SalesOrderThresholdGui\Dependency\Facade\SalesOrderThresholdGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\SalesOrderThresholdGui\Dependency\Facade\SalesOrderThresholdGuiToLocaleFacadeInterface $localeFacade
     */
    public function __construct(SalesOrderThresholdGuiToLocaleFacadeInterface $localeFacade)
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
