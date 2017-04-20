<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Locale\Persistence\Base\SpyLocale;
use Orm\Zed\Touch\Persistence\SpyTouchQuery;
use Spryker\Zed\Collector\Business\Exporter\Reader\ReaderInterface;
use Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface;
use Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface;
use Spryker\Zed\Collector\Business\Model\BatchResultInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface FactFinderFacadeInterface
{

    /**
     * @api
     *
     * @param SpyLocale $locale
     *
     * @return mixed
     */
    public function createFactFinderCsv(SpyLocale $locale);

    /**
     * @api
     *
     * @return \Orm\Zed\Locale\Persistence\SpyLocaleQuery
     */
    public function getLocaleQuery();

    /**
     * @api
     *
     * @param $idLocale int
     * @param $rootCategoryNodeId int
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryQuery
     */
    public function getParentCategoryQuery($idLocale, $rootCategoryNodeId);

}
