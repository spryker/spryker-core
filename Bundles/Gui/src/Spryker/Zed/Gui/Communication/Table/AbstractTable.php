<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui\Communication\Table;

use DateTime;
use Generated\Shared\Transfer\DataTablesColumnTransfer;
use Generated\Shared\Transfer\NumberFormatFilterTransfer;
use Generated\Shared\Transfer\NumberFormatFloatRequestTransfer;
use Generated\Shared\Transfer\NumberFormatIntRequestTransfer;
use Laminas\Filter\FilterChain;
use Laminas\Filter\StringToLower;
use Laminas\Filter\Word\CamelCaseToDash;
use LogicException;
use PDO;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Formatter\OnDemandFormatter;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Propel;
use ReflectionClass;
use Spryker\Service\UtilNumber\UtilNumberServiceInterface;
use Spryker\Service\UtilSanitize\UtilSanitizeService;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Shared\Kernel\Container\GlobalContainer;
use Spryker\Shared\Kernel\Container\GlobalContainerInterface;
use Spryker\Zed\Gui\Communication\Exception\TableException;
use Spryker\Zed\Gui\Communication\Form\DeleteForm;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

abstract class AbstractTable
{
    /**
     * @var string
     */
    public const TABLE_CLASS = 'gui-table-data';

    /**
     * @var string
     */
    public const TABLE_CLASS_NO_SEARCH_SUFFIX = '-no-search';

    /**
     * @var string
     */
    public const BUTTON_CLASS = 'class';

    /**
     * @var string
     */
    public const BUTTON_HREF = 'href';

    /**
     * @var string
     */
    public const BUTTON_DEFAULT_CLASS = 'btn-default';

    /**
     * @var string
     */
    public const BUTTON_ICON = 'icon';

    /**
     * @var string
     */
    public const PARAMETER_VALUE = 'value';

    /**
     * @var string
     */
    public const SORT_BY_COLUMN = 'column';

    /**
     * @var string
     */
    public const SORT_BY_DIRECTION = 'dir';

    /**
     * @var string
     */
    public const URL_ANCHOR = '#';

    /**
     * @uses \Spryker\Zed\Twig\Communication\Plugin\Application\TwigApplicationPlugin::SERVICE_TWIG
     *
     * @var string
     */
    public const SERVICE_TWIG = 'twig';

    /**
     * @uses \Spryker\Zed\Translator\Communication\Plugin\Application\TranslatorApplicationPlugin::SERVICE_TRANSLATOR
     *
     * @var string
     */
    public const SERVICE_TRANSLATOR = 'translator';

    /**
     * @uses \Spryker\Zed\Form\Communication\Plugin\Application\FormApplicationPlugin::SERVICE_FORM_FACTORY
     *
     * @var string
     */
    public const SERVICE_FORM_FACTORY = 'form.factory';

    /**
     * @uses \Spryker\Zed\UtilNumber\Communication\Plugin\Application\NumberFormatterApplicationPlugin::SERVICE_UTIL_NUMBER
     *
     * @var string
     */
    public const SERVICE_UTIL_NUMBER = 'SERVICE_UTIL_NUMBER';

    /**
     * @uses \Spryker\Zed\Locale\Communication\Plugin\Application\LocaleApplicationPlugin::SERVICE_LOCALE
     *
     * @var string
     */
    public const SERVICE_LOCALE = 'locale';

    /**
     * Defines delete form name suffix allowing to avoid non-unique attributes (e.g. form name or id) for delete forms on one page.
     * It is recommended to fill parameter $options in AbstractTable:generateRemoveButton() to avoid non-unique id warning in browser console.
     *
     * $options parameter example:
     * [
     *    'name_suffix' => $id,
     * ]
     *
     * @var string
     */
    protected const DELETE_FORM_NAME_SUFFIX = 'name_suffix';

    /**
     * @var string
     */
    protected const DELETE_FORM_NAME = 'delete_form';

    /**
     * @var string
     */
    protected const DATE_TIME_FORMAT_FOR_CSV_FILENAME = 'Y-m-d-h-i-s';

    /**
     * @uses \Spryker\Zed\Propel\PropelConfig::DB_ENGINE_PGSQL
     *
     * @var string
     */
    protected const DRIVER_NAME_PGSQL = 'pgsql';

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

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
    protected $baseUrl;

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
     * @var string|null
     */
    protected $tableIdentifier;

    /**
     * @var \Generated\Shared\Transfer\DataTablesTransfer
     */
    protected $dataTablesTransfer;

    /**
     * @var \Twig\Environment
     */
    protected $twig;

    /**
     * @var string
     */
    protected const SEARCH_PATTERN_FOR_STRICT_SEARCH_MYSQL = '%s%s = BINARY %s';

    /**
     * @var string
     */
    protected const SEARCH_PATTERN_FOR_STRICT_SEARCH_POSTGRESQL = '%s%s = %s';

    /**
     * @var string
     */
    protected const SEARCH_PATTERN_FOR_FUZZY_SEARCH = 'LOWER(%s%s) LIKE %s';

    /**
     * @var string
     */
    protected const SEARCH = 'search';

    /**
     * @var string
     */
    protected const VALUE = 'value';

    /**
     * @var string
     */
    protected const COLUMNS = 'columns';

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    abstract protected function configure(TableConfiguration $config);

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
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
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function streamDownload(): StreamedResponse
    {
        $streamedResponse = new StreamedResponse();
        $streamedResponse->setCallback($this->getStreamCallback());
        $streamedResponse->setStatusCode(Response::HTTP_OK);
        $streamedResponse->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $streamedResponse->headers->set('Content-Disposition', sprintf('attachment; filename="%s"', $this->getCsvFileName()));

        return $streamedResponse;
    }

    /**
     * @return callable
     */
    protected function getStreamCallback(): callable
    {
        $csvHeaders = $this->getCsvHeaders();

        return function () use ($csvHeaders) {
            /** @var resource $csvHandle */
            $csvHandle = fopen('php://output', 'w+');
            $translatedHeaders = $this->translateCsvHeaders($csvHeaders);

            fputcsv($csvHandle, $translatedHeaders);

            foreach ($this->executeDownloadQuery() as $entity) {
                $formattedRow = $this->formatCsvRow($entity);
                $intersection = array_intersect_key($formattedRow, $csvHeaders);
                $orderedCsvData = array_replace($csvHeaders, $intersection);

                fputcsv($csvHandle, $orderedCsvData);
            }

            fclose($csvHandle);
        };
    }

    /**
     * @throws \Spryker\Zed\Gui\Communication\Exception\TableException
     *
     * @return array
     */
    protected function getCsvHeaders(): array
    {
        throw new TableException(sprintf('You need to implement `%s()` in your `%s`.', __METHOD__, static::class));
    }

    /**
     * @param array $csvHeaders
     *
     * @return array
     */
    protected function translateCsvHeaders(array $csvHeaders): array
    {
        $translator = $this->getTranslator();

        if (!$translator) {
            return $csvHeaders;
        }

        foreach ($csvHeaders as $key => $value) {
            $csvHeaders[$key] = $translator->trans($value);
        }

        return $csvHeaders;
    }

    /**
     * @return \Symfony\Contracts\Translation\TranslatorInterface|null
     */
    protected function getTranslator(): ?TranslatorInterface
    {
        $container = $this->getApplicationContainer();

        if (!$container->has(static::SERVICE_TRANSLATOR)) {
            return null;
        }

        return $container->get(static::SERVICE_TRANSLATOR);
    }

    /**
     * @return iterable
     */
    protected function executeDownloadQuery(): iterable
    {
        return $this->getDownloadQuery()
            ->setFormatter(OnDemandFormatter::class)
            ->find();
    }

    /**
     * @throws \Spryker\Zed\Gui\Communication\Exception\TableException
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function getDownloadQuery(): ModelCriteria
    {
        throw new TableException(sprintf('You need to implement `%s()` in your `%s`.', __METHOD__, static::class));
    }

    /**
     * @param \Propel\Runtime\ActiveRecord\ActiveRecordInterface $entity
     *
     * @throws \Spryker\Zed\Gui\Communication\Exception\TableException
     *
     * @return array
     */
    protected function formatCsvRow(ActiveRecordInterface $entity): array
    {
        if (!method_exists($entity, 'toArray')) {
            throw new TableException(sprintf('Missing method `%s::toArray()`.', get_class($entity)));
        }

        return $entity->toArray();
    }

    /**
     * @return string
     */
    protected function getCsvFileName(): string
    {
        return sprintf('%s-%s.csv', $this->getClassNameShort(), $this->getDatetimeString());
    }

    /**
     * @return string
     */
    protected function getClassNameShort(): string
    {
        $reflectionClass = new ReflectionClass($this);
        $classNameShort = $reflectionClass->getShortName();

        $filter = new FilterChain();
        $filter
            ->attach(new CamelCaseToDash())
            ->attach(new StringToLower());

        return $filter->filter($classNameShort);
    }

    /**
     * @return string
     */
    protected function getDatetimeString(): string
    {
        $dateTime = new DateTime('NOW');

        return $dateTime->format(static::DATE_TIME_FORMAT_FOR_CSV_FILENAME);
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
            $this->twig = $this->getTwig();

            if ($this->tableIdentifier === null) {
                $this->generateTableIdentifier();
            }
        }

        return $this;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function getRequest()
    {
        $container = $this->getApplicationContainer();

        if ($container->has('request')) {
            return $container->get('request');
        }

        return $container->get('request_stack')->getCurrentRequest();
    }

    /**
     * @return void
     */
    public function disableSearch()
    {
        $this->tableClass .= static::TABLE_CLASS_NO_SEARCH_SUFFIX;
    }

    /**
     * @deprecated this method should not be needed.
     *
     * @param string $name
     *
     * @return string
     */
    public function buildAlias($name)
    {
        return str_replace(
            ['.', '(', ')'],
            '',
            $name,
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
     * @param array<string, mixed> $data
     *
     * @return void
     */
    public function loadData(array $data)
    {
        $tableData = [];

        /** @var array|null $headers */
        $headers = $this->config->getHeader();
        $safeColumns = $this->config->getRawColumns();
        $extraColumns = $this->config->getExtraColumns();

        $isArray = is_array($headers);
        foreach ($data as $row) {
            $originalRow = $row;
            if ($isArray) {
                $row = array_intersect_key($row, $headers);

                $row = $this->reOrderByHeaders($headers, $row);
            }

            $row = $this->escapeColumns($row, $safeColumns);
            $row = array_values($row);

            if ($isArray) {
                $row = $this->addExtraColumns($row, $originalRow, $extraColumns);
            }

            $tableData[] = $row;
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
                $value = twig_escape_filter(new Environment(new FilesystemLoader()), $value);
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
     * @param array<mixed> $row
     * @param array<mixed> $originalRow
     * @param array<string> $extraColumns
     *
     * @return array
     */
    protected function addExtraColumns(array $row, array $originalRow, array $extraColumns)
    {
        foreach ($extraColumns as $extraColumnName) {
            if (array_key_exists($extraColumnName, $row)) {
                continue;
            }
            $row[$extraColumnName] = $originalRow[$extraColumnName];
        }

        return $row;
    }

    /**
     * @param array<mixed> $data
     *
     * @return void
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return array<mixed>
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
        $this->tableIdentifier = $prefix . md5(static::class);

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
     * @return \Twig\Environment
     */
    private function getTwig()
    {
        /** @var \Twig\Environment|null $twig */
        $twig = $this->getApplicationContainer()->get(static::SERVICE_TWIG);

        if ($twig === null) {
            throw new LogicException('Twig environment not set up.');
        }

        /** @var \Twig\Loader\ChainLoader $loaderChain */
        $loaderChain = $twig->getLoader();
        $loaderChain->addLoader(new FilesystemLoader(
            $this->getTwigPaths(),
            $this->getTwigRootPath(),
        ));

        return $twig;
    }

    /**
     * @return \Spryker\Shared\Kernel\Container\GlobalContainerInterface
     */
    protected function getApplicationContainer(): GlobalContainerInterface
    {
        return new GlobalContainer();
    }

    /**
     * @return array<string>
     */
    protected function getTwigPaths()
    {
        return [
            __DIR__ . '/../../Presentation/Table/',
        ];
    }

    /**
     * @return string|null
     */
    protected function getTwigRootPath()
    {
        return null;
    }

    /**
     * @return int
     */
    public function getOffset()
    {
        return $this->request->query->getInt('start', 0);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    public function getOrders(TableConfiguration $config)
    {
        $defaultSorting = [$this->getDefaultSorting($config)];

        $orderParameter = $this->getOrderParameter();

        if (!is_array($orderParameter)) {
            return $defaultSorting;
        }

        $sorting = $this->createSortingParameters($orderParameter);

        if (!$sorting) {
            return $defaultSorting;
        }

        return $sorting;
    }

    /**
     * @return array|null
     */
    protected function getOrderParameter(): ?array
    {
        return $this->request->query->all()['order'] ?? null;
    }

    /**
     * Retrieving non-string values using InputBag::get() was deprecated in symfony/http-foundation:5.1
     * Using the InputBag::all() method with an argument was introduced in symfony/http-foundation:5.0
     *
     * The method UploadedFile::getClientSize() was removed in symfony/http-foundation:5.0.
     *
     * To find which way to use we check for the existence of the UploadedFile::getClientSize(), when the method does
     * not exist we have symfony/http-foundation:5.0 or higher installed.
     *
     * @return bool
     */
    protected function isSymfonyHttpFoundationVersion5OrHigher(): bool
    {
        return !method_exists(UploadedFile::class, 'getClientSize');
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function getDefaultSorting(TableConfiguration $config)
    {
        $sort = [
            static::SORT_BY_COLUMN => $config->getDefaultSortColumnIndex(),
            static::SORT_BY_DIRECTION => $config->getDefaultSortDirection(),
        ];

        $defaultSortField = $config->getDefaultSortField();
        if (!$defaultSortField) {
            return $sort;
        }

        $field = key($defaultSortField);
        $direction = $defaultSortField[$field];

        $availableFields = array_keys($config->getHeader());
        $index = array_search($field, $availableFields, true);
        if ($index === false) {
            return $sort;
        }

        $sort = [
            static::SORT_BY_COLUMN => $index,
            static::SORT_BY_DIRECTION => $direction,
        ];

        return $sort;
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
                static::SORT_BY_COLUMN => $this->getParameter($sortingRules, static::SORT_BY_COLUMN, '0'),
                static::SORT_BY_DIRECTION => $this->getParameter($sortingRules, static::SORT_BY_DIRECTION, 'asc'),
            ];
        }

        return $sorting;
    }

    /**
     * @param array<string, mixed> $dataArray
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
     * @return mixed|null
     */
    public function getSearchTerm()
    {
        if (!$this->request->query->has('search')) {
            return null;
        }

        return $this->getSearchParameter();
    }

    /**
     * @return array
     */
    protected function getSearchParameter(): array
    {
        if ($this->isSymfonyHttpFoundationVersion5OrHigher()) {
            return $this->request->query->all('search');
        }

        return (array)$this->request->query->get('search');
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

        return $this->twig
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
            'baseUrl' => $this->baseUrl,
            'header' => [],
            'tableAttributes' => [],
            'headerAttributes' => [],
        ];

        if ($this->getConfiguration() instanceof TableConfiguration) {
            $configTableArray = [
                'url' => ($this->config->getUrl() === null) ? $this->defaultUrl : $this->config->getUrl(),
                'header' => $this->config->getHeader(),
                'footer' => $this->config->getFooter(),
                'order' => $this->getOrders($this->config),
                'searchable' => $this->config->getSearchable(),
                'searchableColumns' => $this->config->getSearchableColumns(),
                'sortable' => $this->config->getSortable(),
                'pageLength' => $this->config->getPageLength(),
                'processing' => $this->config->isProcessing(),
                'serverSide' => $this->config->isServerSide(),
                'stateSave' => $this->config->isStateSave(),
                'paging' => $this->config->isPaging(),
                'ordering' => $this->config->isOrdering(),
                'tableAttributes' => $this->config->getTableAttributes(),
                'headerAttributes' => $this->config->getHeaderAttributes(),
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
     *
     * @return string
     */
    protected function getOrderByColumn(ModelCriteria $query, TableConfiguration $config, array $order)
    {
        $columns = $this->getColumnsList($query, $config);

        if (isset($order[0]) && isset($order[0][static::SORT_BY_COLUMN]) && isset($columns[$order[0][static::SORT_BY_COLUMN]])) {
            $selectedColumn = $columns[$order[0][static::SORT_BY_COLUMN]];

            if (in_array($selectedColumn, $config->getSortable(), true)) {
                return $selectedColumn;
            }
        }

        return $this->getFirstAvailableColumnInQuery($query);
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
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
     * @todo CD-412 refactor this class to allow unspecified header columns and to add flexibility
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     * @param bool $returnRawResults
     *
     * @return \Propel\Runtime\Collection\ObjectCollection|array
     */
    protected function runQuery(ModelCriteria $query, TableConfiguration $config, $returnRawResults = false)
    {
        $this->total = $this->filtered = $this->countTotal($query);
        $limit = $this->getLimit();
        $offset = $this->getOffset();
        $order = $this->getOrders($config);
        $orderColumn = $this->getOrderByColumn($query, $config, $order);

        $query->orderBy($orderColumn, $order[0][static::SORT_BY_DIRECTION]);

        $searchTerm = $this->getSearchTerm();
        $searchValue = $searchTerm[static::PARAMETER_VALUE] ?? '';

        if (mb_strlen($searchValue) > 0 || $this->isStrictSearch($query, $config) === true) {
            $query->setIdentifierQuoting(true);

            $conditions = $this->resolveConditions($query, $config, $searchValue);

            if ($conditions !== []) {
                $query = $this->applyConditions($query, $config, $conditions);
            }

            $this->filtered = $query->count();
        }

        if ($this->dataTablesTransfer !== null) {
            $searchColumns = $config->getSearchable();

            $this->addFilteringConditions($query, $searchColumns);
        }

        $data = $query->offset($offset)
            ->limit($limit)
            ->find();

        if ($returnRawResults === true) {
            return $data;
        }

        return $data->toArray(null, false, TableMap::TYPE_COLNAME);
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array<string>
     */
    protected function getStrictConditionParameters(ModelCriteria $query, TableConfiguration $config): array
    {
        $conditionParameters = [];
        $searchTerms = $this->getSearchColumns();

        foreach ($this->getColumnsList($query, $config) as $index => $colName) {
            if ($searchTerms[$index][static::SEARCH][static::VALUE] !== '') {
                $conditionParameters[$config->getSearchableColumns()[$colName]] = $searchTerms[$index][static::SEARCH][static::VALUE];
            }
        }

        return $conditionParameters;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return bool
     */
    protected function isStrictSearch(ModelCriteria $query, TableConfiguration $config): bool
    {
        $searchTerms = $this->getSearchColumns();

        foreach ($this->getColumnsList($query, $config) as $index => $colName) {
            if (isset($searchTerms[$index]) && $searchTerms[$index][static::SEARCH][static::VALUE] !== '') {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array<mixed>
     */
    protected function getSearchColumns(): array
    {
        return $this->request->query->all()[static::COLUMNS] ?? [];
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     * @param string $searchValue
     *
     * @return array<string>
     */
    protected function resolveConditions(
        ModelCriteria $query,
        TableConfiguration $config,
        string $searchValue
    ): array {
        $conditions = [];
        $connection = Propel::getConnection();
        $driverName = $connection->getAttribute(PDO::ATTR_DRIVER_NAME);
        $filter = $driverName === static::DRIVER_NAME_PGSQL ? '::TEXT' : '';
        $searchPattern = $this->getSearchPattern($config, $driverName, $query);

        if ($this->isStrictSearch($query, $config) === true) {
            $strictConditionParameters = $this->getStrictConditionParameters($query, $config);
            foreach ($strictConditionParameters as $value => $conditionParameter) {
                $conditions[] = $this->buildCondition($searchPattern, $value, $filter, $connection->quote($conditionParameter));
            }

            return $conditions;
        }

        $conditionParameter = $connection->quote('%' . mb_strtolower($searchValue) . '%');
        foreach ($config->getSearchable() as $value) {
            $conditions[] = $this->buildCondition($searchPattern, $value, $filter, $conditionParameter);
        }

        return $conditions;
    }

    /**
     * @param string $searchPattern
     * @param string $value
     * @param string $filter
     * @param string $conditionParameter
     *
     * @return string
     */
    protected function buildCondition(
        string $searchPattern,
        string $value,
        string $filter,
        string $conditionParameter
    ): string {
        return sprintf($searchPattern, $value, $filter, $conditionParameter);
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     *
     * @return int
     */
    protected function countTotal(ModelCriteria $query): int
    {
        return $query->count();
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     * @param array<string> $conditions
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function applyConditions(ModelCriteria $query, TableConfiguration $config, array $conditions): ModelCriteria
    {
        $gluedCondition = implode(
            sprintf(' %s ', $this->isStrictSearch($query, $config) === true ? Criteria::LOGICAL_AND : Criteria::LOGICAL_OR),
            $conditions,
        );

        /** @var literal-string $gluedCondition */
        $gluedCondition = '(' . $gluedCondition . ')';

        if ($config->getHasSearchableFieldsWithAggregateFunctions()) {
            return $query->having($gluedCondition);
        }

        return $query->where($gluedCondition);
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
            'draw' => $this->request->query->getInt('draw', 1),
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
     * @param array<string, mixed> $options
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
     * @param array<string, mixed> $options
     *
     * @return string
     */
    protected function generateEditButton($url, $title, array $options = [])
    {
        $defaultOptions = [
            'class' => 'btn-edit',
            'icon' => 'fa-edit',
        ];

        return $this->generateButton($url, $title, $defaultOptions, $options);
    }

    /**
     * @param string $url
     * @param string $title
     * @param array<string, mixed> $options
     *
     * @return string
     */
    protected function generateViewButton($url, $title, array $options = [])
    {
        $defaultOptions = [
            'class' => 'btn-view',
            'icon' => 'fa-eye',
        ];

        return $this->generateButton($url, $title, $defaultOptions, $options);
    }

    /**
     * @param string $title
     * @param string $url
     * @param bool $separated
     * @param array<string, mixed> $options
     *
     * @return array
     */
    protected function createButtonGroupItem($title, $url, $separated = false, array $options = [])
    {
        return [
            'title' => $title,
            'url' => $url,
            'separated' => $separated,
            'options' => $options,
        ];
    }

    /**
     * @param array $buttonGroupItems
     * @param string $title
     * @param array<string, mixed> $options
     *
     * @return string
     */
    protected function generateButtonGroup(array $buttonGroupItems, $title, array $options = [])
    {
        $defaultOptions = [
            'class' => 'btn-view',
            'icon' => 'fa-eye',
        ];

        return $this->generateButtonGroupHtml($buttonGroupItems, $title, $defaultOptions, $options);
    }

    /**
     * @param string $url
     * @param string $title
     * @param array<string, mixed> $options
     * @param string $formClassName
     *
     * @return string
     */
    protected function generateRemoveButton($url, $title, array $options = [], string $formClassName = DeleteForm::class)
    {
        $name = isset($options[static::DELETE_FORM_NAME_SUFFIX]) ? static::DELETE_FORM_NAME . $options[static::DELETE_FORM_NAME_SUFFIX] : '';

        $options = [
            'fields' => $options,
            'action' => (string)$url,
        ];

        $form = $this->createForm($formClassName, $name, $options);
        $options['form'] = $form->createView();
        $options['title'] = $title;

        return $this->twig->render('delete-form.twig', $options);
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\Gui\Communication\Table\AbstractTable::createForm()} instead.
     *
     * @param array<string, mixed> $options
     * @param string $name
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function createDeleteForm(array $options, string $name = ''): FormInterface
    {
        if (!$name) {
            return $this->getFormFactory()->create(DeleteForm::class, [], $options);
        }

        return $this->getFormFactory()->createNamed($name, DeleteForm::class, [], $options);
    }

    /**
     * @return \Symfony\Component\Form\FormFactoryInterface
     */
    protected function getFormFactory()
    {
        return $this->getApplicationContainer()->get(static::SERVICE_FORM_FACTORY);
    }

    /**
     * @param \Spryker\Service\UtilText\Model\Url\Url|string $url
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
        $icon = '';

        if (array_key_exists(static::BUTTON_ICON, $buttonOptions) === true && $buttonOptions[static::BUTTON_ICON] !== null) {
            $icon = '<i class="fa ' . $buttonOptions[static::BUTTON_ICON] . '"></i> ';
        }

        return $this->getTwig()->render('button.twig', [
            'url' => $this->buildUrl($url),
            'class' => $class,
            'title' => $title,
            'icon' => $icon,
            'parameters' => $parameters,
        ]);
    }

    /**
     * @param \Spryker\Service\UtilText\Model\Url\Url|string $url
     * @param string $title
     * @param string $formClassName
     * @param array $buttonOptions
     * @param array $formOptions
     *
     * @return string
     */
    protected function generateFormButton($url, string $title, string $formClassName, array $buttonOptions = [], array $formOptions = [])
    {
        $buttonOptions = $this->generateButtonOptions([
            'class' => 'btn-view',
            'icon' => 'fa-caret-right',
        ], $buttonOptions);

        $buttonClass = $this->getButtonClass($buttonOptions);
        $buttonParameters = $this->getButtonParameters($buttonOptions);

        $formOptions = array_merge($formOptions, [
            'action' => $this->buildUrl($url),
            'attr' => ['class' => 'form-inline'],
        ]);

        $form = $this->createForm($formClassName, null, $formOptions);

        return $this->getTwig()->render('button-form.twig', [
            'class' => $buttonClass,
            'title' => $title,
            'icon' => $this->generateButtonIcon($buttonOptions),
            'parameters' => $buttonParameters,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param string $formClassName
     * @param string|null $formName
     * @param array $formOptions
     * @param array<string, mixed> $data
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function createForm(
        string $formClassName,
        ?string $formName = null,
        array $formOptions = [],
        array $data = []
    ): FormInterface {
        if (!$formName) {
            return $this->getFormFactory()->create($formClassName, $data, $formOptions);
        }

        return $this->getFormFactory()->createNamed($formName, $formClassName, $data, $formOptions);
    }

    /**
     * @param array $buttonOptions
     *
     * @return string
     */
    protected function generateButtonIcon(array $buttonOptions): string
    {
        if (array_key_exists(static::BUTTON_ICON, $buttonOptions) === true && $buttonOptions[static::BUTTON_ICON] !== null) {
            return '<i class="fa ' . $buttonOptions[static::BUTTON_ICON] . '"></i> ';
        }

        return '';
    }

    /**
     * @param \Spryker\Service\UtilText\Model\Url\Url|string $url
     *
     * @return string
     */
    protected function buildUrl($url): string
    {
        if ($url === static::URL_ANCHOR) {
            return static::URL_ANCHOR;
        }

        if (is_string($url)) {
            $url = Url::parse($url);
        }

        return $url->build();
    }

    /**
     * @param string $title
     * @param string|null $class
     *
     * @return string
     */
    protected function generateLabel(string $title, ?string $class): string
    {
        return $this->getTwig()->render('label.twig', [
            'title' => $title,
            'class' => $class,
        ]);
    }

    /**
     * @param array $buttons
     * @param string $title
     * @param array $defaultOptions
     * @param array $customOptions
     *
     * @return string
     */
    protected function generateButtonGroupHtml(array $buttons, $title, array $defaultOptions, array $customOptions = [])
    {
        $buttonOptions = $this->generateButtonOptions($defaultOptions, $customOptions);
        $class = $this->getButtonClass($defaultOptions, $customOptions);
        $parameters = $this->getButtonParameters($buttonOptions);

        $icon = '';
        if (array_key_exists(static::BUTTON_ICON, $buttonOptions) === true && $buttonOptions[static::BUTTON_ICON] !== null) {
            $icon .= '<i class="fa ' . $buttonOptions[static::BUTTON_ICON] . '"></i> ';
        }

        return $this->generateButtonDropdownHtml($buttons, $title, $icon, $class, $parameters);
    }

    /**
     * @param array $buttons
     * @param string $title
     * @param string $icon
     * @param string $class
     * @param string $parameters
     *
     * @return string
     */
    protected function generateButtonDropdownHtml(array $buttons, $title, $icon, $class, $parameters)
    {
        $nestedButtons = [];

        foreach ($buttons as $button) {
            if (is_string($button['url'])) {
                $utilSanitizeService = new UtilSanitizeService();
                $url = $utilSanitizeService->escapeHtml($button['url']);
            } else {
                /** @var \Spryker\Service\UtilText\Model\Url\Url $buttonUrl */
                $buttonUrl = $button['url'];
                $url = $buttonUrl->buildEscaped();
            }

            $buttonParameters = '';
            if (isset($button['options'])) {
                $buttonParameters = $this->getButtonParameters($button['options']);
            }

            $nestedButtons[] = [
                'needDivider' => !empty($button['separated']),
                'url' => $url,
                'params' => $buttonParameters,
                'title' => $button['title'],
            ];
        }

        return $this->getTwig()->render('button-dropdown.twig', [
            'class' => $class,
            'parameters' => $parameters,
            'icon' => $icon,
            'title' => $title,
            'nestedButtons' => $nestedButtons,
        ]);
    }

    /**
     * @param array $defaultOptions
     * @param array<string, mixed> $options
     *
     * @return string
     */
    protected function getButtonClass(array $defaultOptions, array $options = [])
    {
        $class = '';

        if (isset($defaultOptions[static::BUTTON_CLASS])) {
            $class .= ' ' . $defaultOptions[static::BUTTON_CLASS];
        }
        if (isset($options[static::BUTTON_CLASS])) {
            $class .= ' ' . $options[static::BUTTON_CLASS];
        }

        if (!$class) {
            return static::BUTTON_DEFAULT_CLASS;
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
            if (in_array($argument, [static::BUTTON_CLASS, static::BUTTON_HREF, static::BUTTON_ICON])) {
                continue;
            }
            $parameters .= sprintf(' %s=\'%s\'', $argument, $value);
        }

        return $parameters;
    }

    /**
     * @param array $defaultOptions
     * @param array<string, mixed> $options
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
            /** @var literal-string $where */
            $where = sprintf(
                '(%s >= %s AND %s <= %s)',
                $searchColumns[$column->getData()],
                Propel::getConnection()->quote($this->filterSearchValue($search[static::PARAMETER_VALUE]) . ' 00:00:00'),
                $searchColumns[$column->getData()],
                Propel::getConnection()->quote($this->filterSearchValue($search[static::PARAMETER_VALUE]) . ' 23:59:59'),
            );
            $query->where($where);

            return;
        }

        $value = $this->filterSearchValue($search[static::PARAMETER_VALUE]);
        if ($value === 'null') {
            return;
        }

        /** @var literal-string $where */
        $where = sprintf(
            '%s = %s',
            $searchColumns[$column->getData()],
            Propel::getConnection()->quote($value),
        );
        $query->where($where);
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
            if (empty($search[static::PARAMETER_VALUE])) {
                continue;
            }

            $this->addQueryCondition($query, $searchColumns, $column);
        }
    }

    /**
     * @return \Spryker\Service\UtilNumber\UtilNumberServiceInterface|null
     */
    protected function getUtilNumberService(): ?UtilNumberServiceInterface
    {
        $container = $this->getApplicationContainer();
        if (!$container->has(static::SERVICE_UTIL_NUMBER)) {
            return null;
        }

        return $this->getApplicationContainer()->get(static::SERVICE_UTIL_NUMBER);
    }

    /**
     * @return string|null
     */
    protected function getCurrentLocaleName(): ?string
    {
        $container = $this->getApplicationContainer();
        if (!$container->has(static::SERVICE_LOCALE)) {
            return null;
        }

        return $container->get(static::SERVICE_LOCALE);
    }

    /**
     * @param int $value
     *
     * @return string
     */
    protected function formatInt(int $value): string
    {
        $utilNumberService = $this->getUtilNumberService();
        if (!$utilNumberService) {
            return (string)$value;
        }

        $currentLocaleName = $this->getCurrentLocaleName();
        if (!$currentLocaleName) {
            return (string)$value;
        }

        $numberFormatIntRequestTransfer = (new NumberFormatIntRequestTransfer())
            ->setNumber($value)
            ->setNumberFormatFilter(
                (new NumberFormatFilterTransfer())->setLocale($currentLocaleName),
            );

        return $utilNumberService->formatInt($numberFormatIntRequestTransfer);
    }

    /**
     * @param float $value
     *
     * @return string
     */
    protected function formatFloat(float $value): string
    {
        $utilNumberService = $this->getUtilNumberService();
        if (!$utilNumberService) {
            return (string)$value;
        }

        $currentLocaleName = $this->getCurrentLocaleName();
        if (!$currentLocaleName) {
            return (string)$value;
        }

        $numberFormatFloatRequestTransfer = (new NumberFormatFloatRequestTransfer())
            ->setNumber($value)
            ->setNumberFormatFilter(
                (new NumberFormatFilterTransfer())->setLocale($currentLocaleName),
            );

        return $utilNumberService->formatFloat($numberFormatFloatRequestTransfer);
    }

    /**
     * @param string $formClassName
     * @param string $fieldName
     * @param array<string, mixed> $options
     * @param array<string, mixed> $data
     *
     * @return string
     */
    protected function generateFormField(string $formClassName, string $fieldName, array $options = [], array $data = []): string
    {
        $formView = $this->createForm($formClassName, null, $options, $data)->createView();
        if (!$formView->offsetExists($fieldName)) {
            return '';
        }

        $options['field'] = $formView->offsetGet($fieldName);

        return $this->twig->render('form-field.twig', $options);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     * @param string $driverName
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     *
     * @return string
     */
    protected function getSearchPattern(TableConfiguration $config, string $driverName, ModelCriteria $query): string
    {
        if ($this->isStrictSearch($query, $config) === true) {
            return $this->getStrictSearchPatternByDriverName($driverName);
        }

        return static::SEARCH_PATTERN_FOR_FUZZY_SEARCH;
    }

    /**
     * @param string $driverName
     *
     * @return string
     */
    protected function getStrictSearchPatternByDriverName(string $driverName): string
    {
        if ($driverName === static::DRIVER_NAME_PGSQL) {
            return static::SEARCH_PATTERN_FOR_STRICT_SEARCH_POSTGRESQL;
        }

        return static::SEARCH_PATTERN_FOR_STRICT_SEARCH_MYSQL;
    }
}
