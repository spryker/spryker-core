<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Gui\Communication\Table;

use Generated\Shared\Transfer\DataTablesTransfer;
use Generated\Zed\Ide\AutoCompletion;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Map\TableMap;
use SprykerEngine\Zed\Kernel\Locator;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractTable
{

    const TABLE_CLASS = 'gui-table-data';
    const TABLE_CLASS_NO_SEARCH_SUFFIX = '-no-search';

    const BUTTON_CLASS = 'class';
    const BUTTON_HREF = 'href';
    const BUTTON_DEFAULT_CLASS = 'btn-default';
    const BUTTON_ICON = 'icon';
    const PARAMETER_VALUE = 'value';

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
     * @var string
     */
    protected $tableClass = self::TABLE_CLASS;

    /**
     * @var bool
     */
    private $initialized = false;

    /**
     * @var string
     */
    protected $tableIdentifier;

    /**
     * @var DataTablesTransfer
     */
    protected $dataTablesTransfer;

    /**
     * @param TableConfiguration $config
     *
     * @return mixed
     */
    abstract protected function configure(TableConfiguration $config);

    /**
     * @param TableConfiguration $config
     *
     * @return mixed
     */
    abstract protected function prepareData(TableConfiguration $config);

    /**
     * @return DataTablesTransfer
     */
    public function getDataTablesTransfer()
    {
        return $this->dataTablesTransfer;
    }

    /**
     * @param DataTablesTransfer $dataTablesTransfer
     */
    public function setDataTablesTransfer($dataTablesTransfer)
    {
        $this->dataTablesTransfer = $dataTablesTransfer;
    }

    /**
     * @return $this
     */
    private function init()
    {
        if (!$this->initialized) {
            $this->initialized = true;
            $this->locator = Locator::getInstance();
            $this->request = $this->locator->application()
                ->pluginPimple()
                ->getApplication()['request']
            ;
            $config = $this->newTableConfiguration();
            $config->setPageLength($this->getLimit());
            $config = $this->configure($config);
            $this->setConfiguration($config);
        }

        return $this;
    }

    public function disableSearch()
    {
        $this->tableClass .= self::TABLE_CLASS_NO_SEARCH_SUFFIX;
    }

    /**
     * @todo CD-412 find a better solution (remove it)
     *
     * @param string $name
     *
     * @return string
     *
     * @deprecated this method should not be needed.
     */
    public function buildAlias($name)
    {
        return str_replace(
            ['.', '(', ')'],
            '',
            $name
        );
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
     */
    public function setConfiguration(TableConfiguration $config)
    {
        $this->config = $config;
    }

    /**
     * @param array $data
     */
    public function loadData(array $data)
    {
        $tableData = [];

        $headers = $this->config->getHeader();
        $isArray = is_array($headers);
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
     * @param array $headers
     * @param array $row
     *
     * @return array
     */
    protected function reOrderByHeaders(array $headers, array $row)
    {
        $result = [];

        foreach ($headers as $key => $value) {
            $result[$key] = $row[$key];
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
     * @throws \LogicException
     *
     * @return \Twig_Environment
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
     * @param TableConfiguration $config
     *
     * @return array
     */
    public function getOrders(TableConfiguration $config)
    {
        return $this->request->query->get('order', [
            [
                'column' => $config->getDefaultSortColumnIndex(),
                'dir' => $config->getDefaultSortDirection(),
            ],
        ]);
    }

    /**
     * @return mixed
     */
    public function getSearchTerm()
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
    public function render()
    {
        $this->init();

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
                'class' => $this->tableClass,
                'header' => $this->config->getHeader(),
                'footer' => $this->config->getFooter(),
                'order' => $this->getOrders($this->config),
                'searchable' => $this->config->getSearchable(),
                'sortable' => $this->config->getSortable(),
                'pageLength' => $this->config->getPageLength(),
                'url' => (true === is_null($this->config->getUrl())) ? $this->defaultUrl : $this->config->getUrl(),
            ];
        } else {
            $configArray = [
                'tableId' => $this->getTableIdentifier(),
                'class' => $this->tableClass,
                'url' => $this->defaultUrl,
                'header' => [],
            ];
        }

        return $configArray;
    }

    /**
     * @todo CD-412 to be rafactored, does to many things and is hard to understand
     *
     * @param ModelCriteria $query
     * @param TableConfiguration $config
     * @param bool $returnRawResults
     *
     * @return array
     */
    protected function runQuery(ModelCriteria $query, TableConfiguration $config, $returnRawResults = false)
    {
        //$limit = $config->getPageLength();
        $limit = $this->getLimit();
        $offset = $this->getOffset();
        $order = $this->getOrders($config);
        // @todo CD-412 refactor this class to allow unspecified header columns and to add flexibility
        if (!empty($config->getHeader())) {
            $columns = array_keys($config->getHeader());
        } else {
            $columns = array_keys($query->getTableMap()->getColumns());
        }
        $orderColumn = $columns[$order[0]['column']];
        $this->total = $query->count();
        $query->orderBy($orderColumn, $order[0]['dir']);
        $searchTerm = $this->getSearchTerm();

        $isFirst = true;

        if (mb_strlen($searchTerm[self::PARAMETER_VALUE]) > 0) {
            $query->setIdentifierQuoting(true);

            foreach ($config->getSearchable() as $value) {
                if (!$isFirst) {
                    $query->_or();
                } else {
                    $isFirst = false;
                }

                // @todo fix this in CD-412
                $query->where(sprintf("LOWER(%s::TEXT) LIKE '%s'", $value, '%' . mb_strtolower($searchTerm[self::PARAMETER_VALUE]) . '%'));
            }

            $this->filtered = $query->count();
        } else {
            $this->filtered = $this->total;
        }

        if ($this->dataTablesTransfer !== null) {
            $searchColumns = $config->getSearchable();

            foreach ($this->dataTablesTransfer->getColumns() as $column) {
                $search = $column->getSearch();
                if (empty($search[self::PARAMETER_VALUE])) {
                    continue;
                }

                $this->addQueryCondition($query, $searchColumns, $column);
            }
        }

        $query->offset($offset)
            ->limit($limit)
        ;

        $data = $query->find();

        if ($returnRawResults === true) {
            return $data;
        }

        return $data->toArray(null, false, TableMap::TYPE_COLNAME);
    }

    /**
     * @param string $value
     *
     * @return string
     */
    protected function filterSearchValue($value)
    {
        $value = str_replace(['^', '$'], '', $value);
        $value = stripslashes($value);

        return $value;
    }

    /**
     * @return array
     */
    public function fetchData()
    {
        $this->init();

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

    /**
     * Drop table name from key
     *
     * @param string $key
     *
     * @return string
     */
    public function cutTablePrefix($key)
    {
        $position = mb_strpos($key, '.');

        return ($position !== false) ? mb_substr($key, $position + 1) : $key;
    }

    /**
     * @param string $str
     *
     * @return string
     */
    public function camelize($str)
    {
        return str_replace(' ', '', ucwords(mb_strtolower(str_replace('_', ' ', $str))));
    }

    /**
     * @param int $total
     */
    protected function setTotal($total)
    {
        $this->total = $total;
    }

    /**
     * @param bool $filtered
     */
    protected function setFiltered($filtered)
    {
        $this->filtered = $filtered;
    }

    /**
     * @param string $url
     * @param string $title
     * @param array|null $options
     *
     * @return string
     */
    protected function generateCreateButton($url, $title, array $options = null)
    {
        $defaultOptions = [
            'class' => 'btn-primary',
            'icon' => 'fa-plus',
        ];

        return $this->generateButton($url, $title, $defaultOptions, $options);
    }

    /**
     * @param string $url
     * @param string $title
     * @param array|null $options
     *
     * @return string
     */
    protected function generateEditButton($url, $title, array $options = null)
    {
        $defaultOptions = [
            'class' => 'btn-success',
            'icon' => 'fa-pencil-square-o',
        ];

        return $this->generateButton($url, $title, $defaultOptions, $options);
    }

    /**
     * @param string $url
     * @param string $title
     * @param array|null $options
     *
     * @return string
     */
    protected function generateViewButton($url, $title, array $options = null)
    {
        $defaultOptions = [
            'class' => 'btn-info',
            'icon' => 'fa-caret-right',
        ];

        return $this->generateButton($url, $title, $defaultOptions, $options);
    }

    /**
     * @param string $url
     * @param string $title
     * @param array|null $options
     *
     * @return string
     */
    protected function generateRemoveButton($url, $title, array $options = null)
    {
        $defaultOptions = [
            'class' => 'btn-danger',
            'icon' => 'fa-trash',
        ];

        return $this->generateButton($url, $title, $defaultOptions, $options);
    }

    /**
     * @param string $url
     * @param string $title
     * @param array $defaultOptions
     * @param array $customOptions
     *
     * @return string
     */
    private function generateButton($url, $title, array $defaultOptions, array $customOptions = null)
    {
        $buttonOptions = $this->generateButtonOptions($defaultOptions, $customOptions);

        $class = $this->getButtonClass($defaultOptions, $customOptions);
        $parameters = $this->getButtonParameters($buttonOptions);

        $html = '<a href="' . $url . '" class="btn btn-xs btn-outline ' . $class . '"' . $parameters . '>';

        if (true === array_key_exists(self::BUTTON_ICON, $buttonOptions) && null !== $buttonOptions[self::BUTTON_ICON]) {
            $html .= '<i class="fa ' . $buttonOptions[self::BUTTON_ICON] . '"></i> ';
        }

        $html .= $title;
        $html .= '</a>';

        return $html;
    }

    /**
     * @param array $defaultOptions
     *
     * @return string
     */
    private function getButtonClass(array $defaultOptions, array $options = null)
    {
        $class = '';

        if (array_key_exists(self::BUTTON_CLASS, $defaultOptions)) {
            $class .= ' ' . $defaultOptions[self::BUTTON_CLASS];
        }
        if (null !== $options && array_key_exists(self::BUTTON_CLASS, $options)) {
            $class .= ' ' . $options[self::BUTTON_CLASS];
        }

        if (true === empty($class)) {
            return self::BUTTON_DEFAULT_CLASS;
        }

        return $class;
    }

    /**
     * @param array $buttonOptions
     *
     * @return string
     */
    private function getButtonParameters(array $buttonOptions)
    {
        $parameters = '';
        foreach ($buttonOptions as $argument => $value) {
            if (true === in_array($argument, [self::BUTTON_CLASS, self::BUTTON_HREF, self::BUTTON_ICON])) {
                continue;
            }
            $parameters .= sprintf(' %s="%s"', $argument, $value);
        }

        return $parameters;
    }

    /**
     * @param array $defaultOptions
     * @param array $options
     *
     * @return array
     */
    private function generateButtonOptions(array $defaultOptions, array $options = null)
    {
        $buttonOptions = $defaultOptions;
        if (true === is_array($options)) {
            $buttonOptions = array_merge($defaultOptions, $options);
        }

        return $buttonOptions;
    }

    /**
     * @param ModelCriteria $query
     * @param array $searchColumns
     * @param \ArrayObject $column
     *
     * @return void
     */
    protected function addQueryCondition(ModelCriteria $query, array $searchColumns, \ArrayObject $column)
    {
        $search = $column->getSearch();
        if (preg_match('/created_at|updated_at/', $searchColumns[$column->getData()])) {
            $query->where(
                sprintf(
                    "(%s >= '%s' AND %s <= '%s')",
                    $searchColumns[$column->getData()],
                    $this->filterSearchValue($search[self::PARAMETER_VALUE]) . ' 00:00:00',
                    $searchColumns[$column->getData()],
                    $this->filterSearchValue($search[self::PARAMETER_VALUE]) . ' 23:59:59'
                )
            );

            return;
        }

        $value = $this->filterSearchValue($search[self::PARAMETER_VALUE]);
        if ($value === 'null') {
            return;
        }

        $query->where(sprintf(
            "%s = '%s'",
            $searchColumns[$column->getData()],
            $value)
        );

        return;
    }

}
