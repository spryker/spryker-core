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

    /**
     * @var int
     */
    private $total;

    /**
     * @var int
     */
    private $filtered = 0;

    /**
     * @var int
     */
    protected $defaultLimit = 10;

    /**
     * @var string
     */
    protected $defaultUrl = 'table';

    public function init()
    {
        $this->locator = Locator::getInstance();
        $this->request = $this->locator->application()->pluginPimple()->getApplication()['request'];

        $config = $this->newTableConfiguration();

        $limit = $this->request->query->get('length', $this->defaultLimit);
        $config->setPageLength($limit);

        $config = $this->configure($config);
        $this->setConfiguration($config);
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
     * @param array $data
     */
    public function loadData(array $data)
    {
        $tableData = [];

        foreach ($data as $object) {
            if (false === is_array($object)) {
                $object = $object->toArray();
            }

            if (is_array($this->config->getHeaders()) === true) {
                $object = array_intersect_key(
                    $object,
                    $this->config->getHeaders()
                );
            }

            $tableData[] = array_values($object);
        }
        $this->setData($tableData);
    }

    /**
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
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
            'config' => $this->prepareConfig()
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
            'tableId' => 'table-' . md5(time()),
            'url' => $this->defaultUrl
        ];
        if ($this->getConfiguration() instanceof TableConfiguration) {
            $configArray += [
                'headers' => $this->config->getHeaders(),
                'sortable' => $this->config->getSortable(),
                'pageLength' => $this->config->getPageLength(),
                'url' => $this->config->getUrl()
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
        /** @var \Twig_Loader_Chain $loaderChain */
        $loaderChain = $twig->getLoader();
        $loaderChain->addLoader(
            new \Twig_Loader_Filesystem(
                __DIR__ . '/../../Presentation/Table/'
            )
        );

        if ($twig === null) {
            throw new \LogicException('Twig environment not set up.');
        }

        return $twig;
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
     * @return ObjectCollection
     */
    protected function runQuery(ModelCriteria $query, TableConfiguration $config)
    {
        $limit = $config->getPageLength();
        $offset = $this->request->query->get('start', 0);
        $order = $this->request->query->get('order', [['column' => 0, 'dir' => 'asc']]);
        $columns = array_keys($config->getHeaders());
        $orderColumn = $columns[$order[0]['column']];
        $this->total = $query->count();
        $query
            ->offset($offset)
            ->limit($limit)
            ->orderBy($orderColumn, $order[0]['dir']);
        $search = $this->request->query->get('search', null);

        if (strlen($search['value']) > 0) {
            $i = 0;
            foreach ($columns as $column) {
                if ($i !== 0) {
                    $query->_or();
                }
                $query->where(
                    'spy_country.' . $column . ' LIKE ?',
                    '%' . $search['value'] . '%'
                );
                ++$i;
            }
        }

        $data = $query->find();
        $this->filtered = $this->total;

        return $data->getArrayCopy();
    }

    public function fetchData()
    {
        $data = $this->prepareData($this->config);
        $this->loadData($data);
        $wrapperArray = [
            'draw' => $this->request->query->get('draw', 1),
            'recordsTotal' => $this->total,
            'recordsFiltered' => $this->filtered,
            'data' => $this->data
        ];
        return $wrapperArray;
    }
}
