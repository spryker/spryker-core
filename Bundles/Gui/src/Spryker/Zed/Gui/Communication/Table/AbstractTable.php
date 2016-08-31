<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui\Communication\Table;

use Generated\Shared\Transfer\DataTablesColumnTransfer;
use LogicException;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Propel;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Config\Config;
use Spryker\Zed\Application\Communication\Plugin\Pimple;
use Spryker\Zed\Gui\Communication\Form\DeleteForm;
use Spryker\Zed\Library\Generator\StringGenerator;
use Spryker\Zed\Library\Sanitize\Html;
use Twig_Environment;
use Twig_Loader_Filesystem;

abstract class AbstractTable
{

    const TABLE_CLASS = 'gui-table-data';
    const TABLE_CLASS_NO_SEARCH_SUFFIX = '-no-search';

    const BUTTON_CLASS = 'class';
    const BUTTON_HREF = 'href';
    const BUTTON_DEFAULT_CLASS = 'btn-default';
    const BUTTON_ICON = 'icon';
    const PARAMETER_VALUE = 'value';
    const SORT_BY_COLUMN = 'column';
    const SORT_BY_DIRECTION = 'dir';

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * @var \Generated\Zed\Ide\AutoCompletion
     */
    protected $locator;

    /**
     * @var array
     */
    protected $data;

    /**
     * @var \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected $config;

    /**
     * @var int
     */
    protected $total = 0;

    /**
     * @var int
     */
    protected $limit = 0;

    /**
     * @var int
     */
    protected $filtered = 0;

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
    protected $initialized = false;

    /**
     * @var string
     */
    protected $tableIdentifier;

    /**
     * @var \Generated\Shared\Transfer\DataTablesTransfer
     */
    protected $dataTablesTransfer;

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return mixed
     */
    abstract protected function configure(TableConfiguration $config);

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return mixed
     */
    abstract protected function prepareData(TableConfiguration $config);

    /**
     * @return \Generated\Shared\Transfer\DataTablesTransfer
     */
    public function getDataTablesTransfer()
    {
        return $this->dataTablesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DataTablesTransfer $dataTablesTransfer
     *
     * @return void
     */
    public function setDataTablesTransfer($dataTablesTransfer)
    {
        $this->dataTablesTransfer = $dataTablesTransfer;
    }

    /**
     * @return $this
     */
    protected function init()
    {
        if (!$this->initialized) {
            $this->initialized = true;
            $this->request = $this->getRequest();
            $config = $this->newTableConfiguration();
            $config->setPageLength($this->getLimit());
            $config = $this->configure($config);
            $this->setConfiguration($config);
        }

        return $this;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function getRequest()
    {
        return (new Pimple())
            ->getApplication()['request'];
    }

    /**
     * @return void
     */
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
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function newTableConfiguration()
    {
        return new TableConfiguration();
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    public function setConfiguration(TableConfiguration $config)
    {
        $this->config = $config;
    }

    /**
     * @param array $data
     *
     * @return void
     */
    public function loadData(array $data)
    {
        $tableData = [];

        $headers = $this->config->getHeader();
        $safeColumns = $this->config->getRawColumns();

        $isArray = is_array($headers);
        foreach ($data as $row) {
            if ($isArray) {
                $row = array_intersect_key($row, $headers);

                $row = $this->reOrderByHeaders($headers, $row);
            }

            $row = $this->escapeColumns($row, $safeColumns);

            $tableData[] = array_values($row);
        }

        $this->setData($tableData);
    }

    /**
     * @param array $row
     * @param array $safeColumns
     *
     * @return mixed
     */
    protected function escapeColumns(array $row, array $safeColumns)
    {
        $callback = function (&$value, $key) use ($safeColumns) {
            if (!in_array($key, $safeColumns)) {
                $value = twig_escape_filter(new Twig_Environment(), $value);
            }

            return $value;
        };

        array_walk($row, $callback);

        return $row;
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
            if (!array_key_exists($key, $row)) {
                continue;
            }
            $result[$key] = $row[$key];
        }

        return $result;
    }

    /**
     * @param array $data
     *
     * @return void
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
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
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
        if ($this->tableIdentifier === null) {
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
        $generator = new StringGenerator();
        $this->tableIdentifier = $prefix . $generator->generateRandomString();

        return $this;
    }

    /**
     * @param string|null $tableIdentifier
     *
     * @return void
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
        $twig = (new Pimple())
            ->getApplication()['twig'];

        if ($twig === null) {
            throw new LogicException('Twig environment not set up.');
        }

        /** @var \Twig_Loader_Chain $loaderChain */
        $loaderChain = $twig->getLoader();
        $loaderChain->addLoader(new Twig_Loader_Filesystem(__DIR__ . '/../../Presentation/Table/'));

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
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    public function getOrders(TableConfiguration $config)
    {
        $defaultSorting = [
            [
                self::SORT_BY_COLUMN => $config->getDefaultSortColumnIndex(),
                self::SORT_BY_DIRECTION => $config->getDefaultSortDirection(),
            ],
        ];

        $orderParameter = $this->request->query->get('order');

        if (!is_array($orderParameter)) {
            return $defaultSorting;
        }

        $sorting = $this->createSortingParameters($orderParameter);

        if (empty($sorting)) {
            return $defaultSorting;
        }

        return $sorting;
    }

    /**
     * @param array $orderParameter
     *
     * @return array
     */
    protected function createSortingParameters(array $orderParameter)
    {
        $sorting = [];
        foreach ($orderParameter as $sortingRules) {
            if (!is_array($sortingRules)) {
                continue;
            }
            $sorting[] = [
                self::SORT_BY_COLUMN => $this->getParameter($sortingRules, self::SORT_BY_COLUMN, 0),
                self::SORT_BY_DIRECTION => $this->getParameter($sortingRules, self::SORT_BY_DIRECTION, 'asc'),
            ];
        }

        return $sorting;
    }

    /**
     * @param array $dataArray
     * @param string $key
     * @param string $defaultValue
     *
     * @return string
     */
    protected function getParameter(array $dataArray, $key, $defaultValue)
    {
        if (array_key_exists($key, $dataArray)) {
            return $dataArray[$key];
        }

        return $defaultValue;
    }

    /**
     * @return mixed
     */
    public function getSearchTerm()
    {
        return $this->request->query->get('search', null);
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        if (!$this->limit) {
            $this->limit = $this->request->query->getInt('length', $this->defaultLimit);
        }

        return $this->limit;
    }

    /**
     * @param int $limit
     *
     * @return $this
     */
    public function setLimit($limit)
    {
        $this->limit = (int)$limit;
        return $this;
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
            ->render('index.twig', $twigVars);
    }

    /**
     * @return array
     */
    public function prepareConfig()
    {
        $configArray = [
            'tableId' => $this->getTableIdentifier(),
            'class' => $this->tableClass,
            'url' => $this->defaultUrl,
            'header' => [],
        ];

        if ($this->getConfiguration() instanceof TableConfiguration) {
            $configTableArray = [
                'url' => ($this->config->getUrl() === null) ? $this->defaultUrl : $this->config->getUrl(),
                'header' => $this->config->getHeader(),
                'footer' => $this->config->getFooter(),
                'order' => $this->getOrders($this->config),
                'searchable' => $this->config->getSearchable(),
                'sortable' => $this->config->getSortable(),
                'pageLength' => $this->config->getPageLength(),
            ];

            $configArray = array_merge($configArray, $configTableArray);
        }

        return $configArray;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $criteria
     *
     * @return string
     */
    protected function getFirstAvailableColumnInQuery(ModelCriteria $criteria)
    {
        $tableMap = $criteria->getTableMap();
        $columns = array_keys($tableMap->getColumns());

        $firstColumnName = $tableMap->getColumn($columns[0])->getName();

        return $tableMap->getName() . '.' . $firstColumnName;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     * @param array $order
     * @return string
     */
    protected function getOrderByColumn(ModelCriteria $query, TableConfiguration $config, array $order)
    {
        $columns = $this->getColumnsList($query, $config);

        if (isset($order[0]) && isset($order[0][self::SORT_BY_COLUMN]) && isset($columns[$order[0][self::SORT_BY_COLUMN]])) {

            $selectedColumn = $columns[$order[0][self::SORT_BY_COLUMN]];

            if (in_array($selectedColumn, $config->getSortable(), true)) {
                return $selectedColumn;
            }
        }

        return $this->getFirstAvailableColumnInQuery($query);
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     * @return array
     */
    protected function getColumnsList(ModelCriteria $query, TableConfiguration $config)
    {
        if ($config->getHeader()) {
            return array_keys($config->getHeader());
        }

        return array_keys($query->getTableMap()->getColumns());
    }

    /**
     * @todo CD-412 to be rafactored, does to many things and is hard to understand
     * @todo CD-412 refactor this class to allow unspecified header columns and to add flexibility
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     * @param bool $returnRawResults
     *
     * @return array|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function runQuery(ModelCriteria $query, TableConfiguration $config, $returnRawResults = false)
    {
        $limit = $this->getLimit();
        $offset = $this->getOffset();
        $order = $this->getOrders($config);

        $orderColumn = $this->getOrderByColumn($query, $config, $order);

        $this->total = $query->count();
        $query->orderBy($orderColumn, $order[0][self::SORT_BY_DIRECTION]);
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

                $filter = '';
                $sqlDriver = Config::getInstance()->get(ApplicationConstants::ZED_DB_ENGINE);
                // @todo fix this in CD-412
                if ($sqlDriver === 'pgsql') {
                    $filter = '::TEXT';
                }

                $conditionParameter = '%' . mb_strtolower($searchTerm[self::PARAMETER_VALUE]) . '%';
                $condition = sprintf(
                    'LOWER(%s%s) LIKE %s',
                    $value,
                    $filter,
                    Propel::getConnection()->quote($conditionParameter)
                );
                $query->where($condition);
            }

            $this->filtered = $query->count();
        } else {
            $this->filtered = $this->total;
        }

        if ($this->dataTablesTransfer !== null) {
            $searchColumns = $config->getSearchable();

            $this->addFilteringConditions($query, $searchColumns);
        }

        $query->offset($offset)
            ->limit($limit);

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
     *
     * @return void
     */
    protected function setTotal($total)
    {
        $this->total = $total;
    }

    /**
     * @param int $filtered
     *
     * @return void
     */
    protected function setFiltered($filtered)
    {
        $this->filtered = $filtered;
    }

    /**
     * @param string $url
     * @param string $title
     * @param array $options
     *
     * @return string
     */
    protected function generateCreateButton($url, $title, array $options = [])
    {
        $defaultOptions = [
            'class' => 'btn-create',
            'icon' => 'fa-plus',
        ];

        return $this->generateButton($url, $title, $defaultOptions, $options);
    }

    /**
     * @param string $url
     * @param string $title
     * @param array $options
     *
     * @return string
     */
    protected function generateEditButton($url, $title, array $options = [])
    {
        $defaultOptions = [
            'class' => 'btn-edit',
            'icon' => 'fa-pencil-square-o',
        ];

        return $this->generateButton($url, $title, $defaultOptions, $options);
    }

    /**
     * @param string $url
     * @param string $title
     * @param array $options
     *
     * @return string
     */
    protected function generateViewButton($url, $title, array $options = [])
    {
        $defaultOptions = [
            'class' => 'btn-view',
            'icon' => 'fa-caret-right',
        ];

        return $this->generateButton($url, $title, $defaultOptions, $options);
    }

    /**
     * @param string $url
     * @param string $title
     * @param array $options
     *
     * @return string
     */
    protected function generateRemoveButton($url, $title, array $options = [])
    {
        $formFactory = $this->getFormFactory();

        $deleteForm = new DeleteForm();

        $options = [
            'fields' => $options,
            'action' => $url
        ];

        $form = $formFactory->create($deleteForm, [], $options);

        $options['form'] = $form->createView();
        $options['title'] = $title;

        return $this->getTwig()->render('delete-form.twig', $options);
    }

    /**
     * @return \Symfony\Component\Form\FormFactoryInterface
     */
    protected function getFormFactory()
    {
        return (new Pimple())->getApplication()['form.factory'];
    }

    /**
     * @param string|\Spryker\Shared\Url\Url $url
     * @param string $title
     * @param array $defaultOptions
     * @param array $customOptions
     *
     * @return string
     */
    protected function generateButton($url, $title, array $defaultOptions, array $customOptions = [])
    {
        $buttonOptions = $this->generateButtonOptions($defaultOptions, $customOptions);

        $class = $this->getButtonClass($defaultOptions, $customOptions);
        $parameters = $this->getButtonParameters($buttonOptions);

        if (is_string($url)) {
            $url = Html::escape($url);
        } else {
            $url = $url->buildEscaped();
        }

        $html = '<a href="' . $url . '" class="btn btn-xs btn-outline ' . $class . '"' . $parameters . '>';

        if (array_key_exists(self::BUTTON_ICON, $buttonOptions) === true && $buttonOptions[self::BUTTON_ICON] !== null) {
            $html .= '<i class="fa ' . $buttonOptions[self::BUTTON_ICON] . '"></i> ';
        }

        $html .= $title;
        $html .= '</a>';

        return $html;
    }

    /**
     * @param array $defaultOptions
     * @param array $options
     *
     * @return string
     */
    protected function getButtonClass(array $defaultOptions, array $options = [])
    {
        $class = '';

        if (isset($defaultOptions[self::BUTTON_CLASS])) {
            $class .= ' ' . $defaultOptions[self::BUTTON_CLASS];
        }
        if (isset($options[self::BUTTON_CLASS])) {
            $class .= ' ' . $options[self::BUTTON_CLASS];
        }

        if (empty($class)) {
            return self::BUTTON_DEFAULT_CLASS;
        }

        return $class;
    }

    /**
     * @param array $buttonOptions
     *
     * @return string
     */
    protected function getButtonParameters(array $buttonOptions)
    {
        $parameters = '';
        foreach ($buttonOptions as $argument => $value) {
            if (in_array($argument, [self::BUTTON_CLASS, self::BUTTON_HREF, self::BUTTON_ICON])) {
                continue;
            }
            $parameters .= sprintf(' %s=\'%s\'', $argument, $value);
        }

        return $parameters;
    }

    /**
     * @param array $defaultOptions
     * @param array $options
     *
     * @return array
     */
    protected function generateButtonOptions(array $defaultOptions, array $options = [])
    {
        $buttonOptions = $defaultOptions;
        if (is_array($options)) {
            $buttonOptions = array_merge($defaultOptions, $options);
        }

        return $buttonOptions;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param array $searchColumns
     * @param \Generated\Shared\Transfer\DataTablesColumnTransfer $column
     *
     * @return void
     */
    protected function addQueryCondition(ModelCriteria $query, array $searchColumns, DataTablesColumnTransfer $column)
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
            $value
        ));
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param array $searchColumns
     *
     * @return void
     */
    protected function addFilteringConditions(ModelCriteria $query, array $searchColumns)
    {
        foreach ($this->dataTablesTransfer->getColumns() as $column) {
            $search = $column->getSearch();
            if (empty($search[self::PARAMETER_VALUE])) {
                continue;
            }

            $this->addQueryCondition($query, $searchColumns, $column);
        }
    }

}
