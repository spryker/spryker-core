<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Collector\Communication\Plugin;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Orm\Zed\Touch\Persistence\SpyTouchQuery;
use Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface;
use Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface;
use Spryker\Zed\Collector\Business\Model\BatchResultInterface;
use Spryker\Zed\Collector\Dependency\Plugin\CollectorPluginInterface;
use Spryker\Zed\Collector\Business\CollectorFacade;
use Spryker\Zed\Collector\Communication\CollectorCommunicationFactory;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method CollectorFacade getFacade()
 * @method CollectorCommunicationFactory getFactory()
 */
abstract class AbstractCollectorPlugin extends AbstractPlugin implements CollectorPluginInterface
{

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @var WriterInterface
     */
    protected $dataWriter;

    /**
     * @var TouchUpdaterInterface
     */
    protected $touchUpdater;

    /**
     * @param OutputInterface $output
     *
     * @return void
     */
    public function setOutput(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * @return WriterInterface
     */
    public function getDataWriter()
    {
        return $this->dataWriter;
    }

    /**
     * @param WriterInterface $dataWriter
     */
    public function setDataWriter(WriterInterface $dataWriter)
    {
        $this->dataWriter = $dataWriter;
    }

    /**
     * @return TouchUpdaterInterface
     */
    public function getTouchUpdater()
    {
        return $this->touchUpdater;
    }

    /**
     * @param TouchUpdaterInterface $touchUpdater
     */
    public function setTouchUpdater(TouchUpdaterInterface $touchUpdater)
    {
        $this->touchUpdater = $touchUpdater;
    }

    /**
     * @param SpyTouchQuery $baseQuery
     * @param LocaleTransfer $locale
     * @param BatchResultInterface $result
     *
     * @return void
     */
    abstract public function run(
        SpyTouchQuery $baseQuery,
        LocaleTransfer $locale,
        BatchResultInterface $result
    );

    /**
     * @param SpyTouchQuery $baseQuery
     * @param LocaleTransfer $locale
     * @param BatchResultInterface $result
     *
     * @return void
     */
    public function postRun(
        SpyTouchQuery $baseQuery,
        LocaleTransfer $locale,
        BatchResultInterface $result
    ) {
    }

}
