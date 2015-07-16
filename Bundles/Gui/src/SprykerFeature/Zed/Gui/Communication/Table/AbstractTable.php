<?php

namespace SprykerFeature\Zed\Gui\Communication\Table;

use Generated\Zed\Ide\AutoCompletion;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use SprykerEngine\Zed\Kernel\Locator;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractTable
{

    /**
     * @var Request
     */
    protected $request;
    /**
     * @var AutoCompletion
     */
    private $locator;
    /**
     * @var array
     */
    private $data;
    /**
     * @var TableConfiguration
     */
    private $config;

    protected $defaultLimit = 25;

    public function init()
    {
        $this->locator = Locator::getInstance();
        $this->request = $this->locator->application()->pluginPimple()->getApplication()['request'];

        $config = $this->newTableConfiguration();

        $limit = $this->request->query->get('limit', $this->defaultLimit);
        $config->setPageLength($limit);

        $config = $this->configure($config);
        $this->setConfiguration($config);

        $data = $this->prepareData($config);
        $this->loadObjectCollection($data);
    }

    /**
     * @return TableConfiguration
     */
    protected function newTableConfiguration()
    {
        return new TableConfiguration();
    }

    abstract protected function configure(TableConfiguration $config);

    /**
     * @param TableConfiguration $config
     */
    public function setConfiguration(TableConfiguration $config)
    {
        $this->config = $config;
    }

    abstract protected function prepareData(TableConfiguration $config);

    /**
     *
     */
    public function loadObjectCollection($objects)
    {
        $tableData = [];
        foreach ($objects as $object) {

            // TODO HACK
            if(false === is_array($object)){
                $object = $object->toArray();
            }

            $tableData[] = $object;
        }
        $this->loadData($tableData);
    }

    /**
     * @param array $data
     */
    public function loadData(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * @return string
     */
    public function render()
    {

        $twigVars = [
            'data' => $this->data,
            'config' => $this->prepareConfig(),
        ];

        return $this->getTwig()->render(
            'index.twig',
            $twigVars
        );
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
        if ($this->getConfiguration() instanceof TableConfiguration) {
            $configArray += [
                'headers' => $this->config->getHeaders(),
                'sortable' => $this->config->getSortable(),
                'emptyHeaders' => $configArray['columnCount']
                    - count($this->config->getHeaders()),
                'pageLength' => $this->config->getPageLength(),
            ];
        }

        return $configArray;
    }

    /**
     * @return TableConfiguration
     */
    public function getConfiguration()
    {
        return $this->config;
    }

    /**
     * @throws \LogicException
     * @return \Twig_Environment
     *
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

    /**
     * @param ModelCriteria $query
     * @param TableConfiguration $config
     *
     * @return ObjectCollection
     */
    protected function runQuery(ModelCriteria $query, TableConfiguration $config)
    {
        $limit = $config->getPageLength();

        $offset = $this->request->query->get('offset', 0);

        $data = $query
            ->offset($offset)
            ->limit($limit)
            ->find();

        return $data->getArrayCopy();
    }

}
