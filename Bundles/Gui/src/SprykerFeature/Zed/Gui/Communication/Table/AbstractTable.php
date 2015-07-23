<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Gui\Communication\Table;

use Generated\Zed\Ide\AutoCompletion;
use Propel\Runtime\ActiveQuery\ModelCriteria;
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

    /**
     * @var null
     */
    protected $tableIdentifier;

    public function init()
    {
        $this->locator = Locator::getInstance();
        $this->request = $this->locator->application()
            ->pluginPimple()
            ->getApplication()['request'];
        $config = $this->newTableConfiguration();
        $config->setPageLength($this->getLimit());
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

    /**
     * @param TableConfiguration $config
     *
     * @return mixed
     */
    abstract protected function configure(TableConfiguration $config);

    /**
     * @param TableConfiguration $config
     */
    public function setConfiguration(TableConfiguration $config)
    {
        $this->config = $config;
    }

    /**
     * @param TableConfiguration $config
     *
     * @return mixed
     */
    abstract protected function prepareData(TableConfiguration $config);

    /**
     * @param array $data
     */
    public function loadData(array $data)
    {
        $tableData = [];

        $headers = $this->config->getHeader();
        $isArray = (true === is_array($headers));
        foreach ($data as $row) {
            if ($isArray) {
                $row = array_intersect_key($row, $headers);

                $row = $this->reOrderByHeaders($headers, $row);
            }

            $tableData[] = array_values($row);
        }

        $this->setData($tableData);
    }

    /**
     * @param $order
     * @param $data
     *
     * @return array
     */
    protected function reOrderByHeaders($order, $data)
    {
        $result = [];

        foreach ($order as $key => $value) {
            $result[$key] = $data[$key];
        }

        return $result;
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
     * @return TableConfiguration
     */
    public function getConfiguration()
    {
        return $this->config;
    }

    /**
     * @return string
     */
    public function getTableIdentifier()
    {
        if (null === $this->tableIdentifier) {
            $this->generateTableIdentifier();
        }

        return $this->tableIdentifier;
    }

    /**
     * @param string $prefix
     *
     * @return $this
     */
    protected function generateTableIdentifier($prefix = 'table-')
    {
        $this->tableIdentifier = uniqid($prefix);

        return $this;
    }

    /**
     * @param null $tableIdentifier
     */
    public function setTableIdentifier($tableIdentifier)
    {
        $this->tableIdentifier = $tableIdentifier;
    }

    /**
     * @return \Twig_Environment
     * @throws \LogicException
     */
    private function getTwig()
    {
        /** @var \Twig_Environment $twig */
        $twig = $this->locator->application()
            ->pluginPimple()
            ->getApplication()['twig']
        ;

        if ($twig === null) {
            throw new \LogicException('Twig environment not set up.');
        }

        /** @var \Twig_Loader_Chain $loaderChain */
        $loaderChain = $twig->getLoader();
        $loaderChain->addLoader(new \Twig_Loader_Filesystem(__DIR__ . '/../../Presentation/Table/'));

        return $twig;
    }

    /**
     * @return mixed
     */
    public function getOffset()
    {
        return $this->request->query->get('start', 0);
    }

    /**
     * @return mixed
     */
    public function getOrders()
    {
        return $this->request->query->get('order', [
            [
                'column' => 0,
                'dir' => 'asc',
            ],
        ]);
    }

    /**
     * @return mixed
     */
    public function getSearchTherm()
    {
        return $this->request->query->get('search', null);
    }

    /**
     * @return mixed
     */
    public function getLimit()
    {
        return $this->request->query->get('length', $this->defaultLimit);
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
            'config' => $this->prepareConfig(),
        ];

        return $this->getTwig()
            ->render('index.twig', $twigVars)
        ;
    }

    /**
     * @return array
     */
    public function prepareConfig()
    {
        if ($this->getConfiguration() instanceof TableConfiguration) {
            $configArray = [
                'tableId' => $this->getTableIdentifier(),
                'header' => $this->config->getHeader(),
                'searchable' => $this->config->getSearchable(),
                'sortable' => $this->config->getSortable(),
                'pageLength' => $this->config->getPageLength(),
                'url' => (true === is_null($this->config->getUrl())) ? $this->defaultUrl : $this->config->getUrl(),
            ];
        } else {
            $configArray = [
                'tableId' => 'table-' . md5(time()),
                'url' => $this->defaultUrl,
            ];
        }

        return $configArray;
    }

    /**
     * @param ModelCriteria $query
     * @param TableConfiguration $config
     *
     * @return array
     */
    protected function runQuery(ModelCriteria $query, TableConfiguration $config)
    {
        $limit = $config->getPageLength();
        $offset = $this->getOffset();
        $order = $this->getOrders();
        $columns = array_keys($config->getHeader());
        $orderColumn = $columns[$order[0]['column']];
        $this->total = $query->count();
        $query->orderBy($orderColumn, $order[0]['dir']);
        $searchTherm = $this->getSearchTherm();

        if (mb_strlen($searchTherm['value']) > 0) {
            $isFirst = true;

            $query->setIdentifierQuoting(true);
            $tableName = $query->getTableMap()
                ->getName();

            foreach ($config->getSearchable() as $value) {
                if (!$isFirst) {
                    $query->_or();
                } else {
                    $isFirst = false;
                }

                $query->where(sprintf('LOWER(%s.%s) LIKE ?', $tableName, $query->getTableMap()
                    ->getColumnByPhpName($value)
                    ->getName()), '%' . mb_strtolower($searchTherm['value']) . '%');
            }
            $this->filtered = $query->count();
        } else {
            $this->filtered = $this->total;
        }

        $query->offset($offset)
            ->limit($limit)
        ;
        $data = $query->find();

        return $data->toArray();
    }

    /**
     * @return array
     */
    public function fetchData()
    {
        $data = $this->prepareData($this->config);
        $this->loadData($data);
        $wrapperArray = [
            'draw' => $this->request->query->get('draw', 1),
            'recordsTotal' => $this->total,
            'recordsFiltered' => $this->filtered,
            'data' => $this->data,
        ];

        return $wrapperArray;
    }

}
