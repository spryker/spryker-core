<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Locale\Persistence\Base\SpyLocale;
use Orm\Zed\Touch\Persistence\SpyTouchQuery;
use Spryker\Zed\Collector\Business\Exporter\Reader\ReaderInterface;
use Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface;
use Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface;
use Spryker\Zed\Collector\Business\Model\BatchResultInterface;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\FactFinder\Business\FactFinderBusinessFactory getFactory()
 */
class FactFinderFacade extends AbstractFacade implements FactFinderFacadeInterface
{

    /**
     * @api
     *
     * @param SpyLocale $locale
     *
     * @return mixed
     */
    public function createFactFinderCsv(SpyLocale $locale)
    {
        $this->getFactory()
            ->createCsvFile($locale);
    }

    /**
     * @return \Orm\Zed\Locale\Persistence\SpyLocaleQuery
     */
    public function getLocaleQuery()
    {
        return $this->getFactory()
            ->getLocaleQuery();
    }

    /**
     * @param $idLocale int
     * @param $rootCategoryNodeId int
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryQuery
     */
    public function getParentCategoryQuery($idLocale, $rootCategoryNodeId)
    {
        return $this->getFactory()
            ->getFactFinderQueryContainer()
            ->getParentCategoryQuery($idLocale, $rootCategoryNodeId);
    }

}
