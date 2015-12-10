<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Collector\Dependency\Plugin;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Touch\Persistence\SpyTouchQuery;
use Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface;
use Spryker\Zed\Collector\Business\Model\BatchResultInterface;
use SprykerFeature\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface CollectorPluginInterface
{

    /**
     * @param OutputInterface $output
     *
     * @return void
     */
    public function setOutput(OutputInterface $output);

    /**
     * @param WriterInterface $dataWriter
     *
     * @return void
     */
    public function setDataWriter(WriterInterface $dataWriter);

    /**
     * @param TouchUpdaterInterface $touchUpdater
     *
     * @return void
     */
    public function setTouchUpdater(TouchUpdaterInterface $touchUpdater);

    /**
     * @param SpyTouchQuery $baseQuery
     * @param LocaleTransfer $locale
     * @param BatchResultInterface $result
     *
     * @return void
     */
    public function run(SpyTouchQuery $baseQuery, LocaleTransfer $locale, BatchResultInterface $result);

    /**
     * @param SpyTouchQuery $baseQuery
     * @param LocaleTransfer $locale
     * @param BatchResultInterface $result
     *
     * @return void
     */
    public function postRun(SpyTouchQuery $baseQuery, LocaleTransfer $locale, BatchResultInterface $result);

}
