<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Locale\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\LocaleBuilder;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class LocaleDataHelper extends Module
{
    use LocatorHelperTrait;

    const LOCALE_NAME_LENGTH_LIMIT = 5;

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function haveLocale(array $seedData = [])
    {
        $localeTransfer = $this->generateLocaleTransfer($seedData);

        if ($this->getLocaleFacade()->hasLocale($localeTransfer->getLocaleName())) {
            return $this->getLocaleFacade()->getLocale($localeTransfer->getLocaleName());
        }

        return $this->getLocaleFacade()->createLocale($localeTransfer->getLocaleName());
    }

    /**
     * @return \Spryker\Zed\Locale\Business\LocaleFacadeInterface
     */
    protected function getLocaleFacade()
    {
        return $this->getLocator()->locale()->facade();
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function generateLocaleTransfer(array $seedData = [])
    {
        $localeTransfer = (new LocaleBuilder($seedData))->build();

        if (strlen($localeTransfer->getLocaleName()) > static::LOCALE_NAME_LENGTH_LIMIT) {
            return $this->generateLocaleTransfer($seedData);
        }

        return $localeTransfer;
    }
}
