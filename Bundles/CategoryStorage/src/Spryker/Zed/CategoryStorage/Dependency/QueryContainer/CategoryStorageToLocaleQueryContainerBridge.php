<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Dependency\QueryContainer;

class CategoryStorageToLocaleQueryContainerBridge implements CategoryStorageToLocaleQueryContainerInterface
{
    /**
     * @var \Spryker\Zed\Locale\Persistence\LocaleQueryContainerInterface
     */
    protected $localeQueryContainer;

    /**
     * @param \Spryker\Zed\Locale\Persistence\LocaleQueryContainerInterface $localeQueryContainer
     */
    public function __construct($localeQueryContainer)
    {
        $this->localeQueryContainer = $localeQueryContainer;
    }

    /**
     * @return \Orm\Zed\Locale\Persistence\SpyLocaleQuery
     */
    public function queryLocales()
    {
        return $this->localeQueryContainer->queryLocales();
    }
}
