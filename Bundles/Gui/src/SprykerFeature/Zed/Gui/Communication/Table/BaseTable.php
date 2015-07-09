<?php

namespace SprykerFeature\Zed\Gui\Communication\Table;

use Generated\Zed\Ide\AutoCompletion;
use Propel\Runtime\Collection\ObjectCollection;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsOrderItemState;

class BaseTable
{

    /**
     * @var AutoCompletion
     */
    private $locator;

    /**
     * @var array
     */
    private $data;

    /**
     * @var BaseTableConfiguration
     */
    private $config;

    public function __construct()
    {

        $this->locator = Locator::getInstance();

        $this->locator->application()->pluginPimple()->getApplication()['request'];

    }

    /**
     * @param array $data
     */
    public function loadData(array $data)
    {
        $this->data = $data;
    }

    /**
     *
     */
    public function loadObjectCollection(ObjectCollection $collection) {
        $objects = $collection->getArrayCopy();
        $tableData = [];
        /** @var SpyOmsOrderItemState $object */
        foreach($objects as $object) {
            $tableData[] = $object->toArray();
        }
        $this->loadData($tableData);
    }

    /**
     * @return string
     */
    public function render()
    {

        $twigVars = [
            'data' => $this->data,
            'config' => $this->prepareConfig()
        ];

        return $this->getTwig()->render(
            'index.twig',
            $twigVars
        );
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * @param BaseTableConfiguration $config
     */
    public function setConfiguration(BaseTableConfiguration  $config)
    {
        $this->config = $config;
    }

    /**
     * @return BaseTableConfiguration
     */
    public function getConfiguration()
    {
        return $this->config;
    }

    /**
     * @return array
     */
    public function prepareConfig()
    {
        $configArray = [
            'tableId' => 'table-' . md5(serialize($this->data)),
            'columnCount' => count($this->data[0]),
            ];
        if ($this->getConfiguration() instanceof BaseTableConfiguration) {
            $configArray += [
                'headers' => $this->config->getHeaders(),
                'sortable' => $this->config->getSortable(),
                'emptyHeaders' => $configArray['columnCount']
                    - count($this->config->getHeaders()),
                'pageLength' => $this->config->getPageLength()
            ];
        }

        return $configArray;
    }

    /**
     * @return \Twig_Environment
     * @throws \LogicException
     */
    private function getTwig()
    {
        /** @var \Twig_Environment $twig */
        $twig = $this
            ->locator
            ->application()
            ->pluginPimple()
            ->getApplication()['twig'];
        $twig
            ->getLoader()
            ->addLoader(
                new \Twig_Loader_Filesystem(
                    __DIR__ . '/../../Presentation/Table/'
                )
            );

        if ($twig === null) {
            throw new \LogicException('Twig environment not set up.');
        }

        return $twig;
    }

    public function getDataFromQuery()
    {

    }

    public function getJS()
    {
        return [
            'plugins/dataTables/jquery.dataTables.js',
            'plugins/dataTables/dataTables.bootstrap.js',
            'plugins/dataTables/dataTables.responsive.js',
            'plugins/dataTables/dataTables.tableTools.min.js',
        ];
    }

}
