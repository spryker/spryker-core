<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Cms\Dependency\Facade;

use Generated\Shared\Transfer\LocaleTransfer;

interface CmsToLocaleInterface
{

    /**
     * @return LocaleTransfer
     */
    public function getCurrentLocale();

}
