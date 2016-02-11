<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Locale\Persistence;

use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface LocaleQueryContainerInterface extends QueryContainerInterface
{

    /**
     * @param string $localeName
     *
     * @return \Orm\Zed\Locale\Persistence\SpyLocaleQuery
     */
    public function queryLocaleByName($localeName);

    /**
     * @return \Orm\Zed\Locale\Persistence\SpyLocaleQuery
     */
    public function queryLocales();

}
