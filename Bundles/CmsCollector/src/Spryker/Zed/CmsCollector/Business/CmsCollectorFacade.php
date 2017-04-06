<?php

namespace Spryker\Zed\CmsCollector\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Touch\Persistence\SpyTouchQuery;
use Spryker\Zed\Collector\Business\Exporter\Reader\ReaderInterface;
use Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface;
use Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface;
use Spryker\Zed\Collector\Business\Model\BatchResultInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CmsCollector\Business\CmsCollectorBusinessFactory getFactory()
 */
class CmsCollectorFacade extends AbstractFacade implements CmsCollectorFacadeInterface
{

    public function runStorageCmsPageCollector(
        SpyTouchQuery $baseQuery,
        LocaleTransfer $localeTransfer,
        BatchResultInterface $result,
        ReaderInterface $dataReader,
        WriterInterface $dataWriter,
        TouchUpdaterInterface $touchUpdater,
        OutputInterface $output
    ) {

        $collector = $this->getFactory()
            ->createStorageCmsPageCollector();

        $this->getFactory()->getCollectorFacade()->runCollector(
            $collector,
            $baseQuery,
            $localeTransfer,
            $result,
            $dataReader,
            $dataWriter,
            $touchUpdater,
            $output
        );
    }

}
