<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui\Communication\Table;

class TableConfiguration
{
    /**
     * @var string
     */
    public const SORT_ASC = 'asc';

    /**
     * @var string
     */
    public const SORT_DESC = 'desc';

    /**
     * @var string|null
     */
    protected $url;

    /**
     * @var array
     */
    protected $header = [];

    /**
     * @var array
     */
    protected $footer = [];

    /**
     * @var array<string>
     */
    protected $extraColumns = [];

    /**
     * @var int
     */
    protected $pageLength = 0;

    /**
     * If null it will use all fields defined in $header.
     *
     * @var array<string>|null
     */
    protected $searchableFields;

    /**
     * @var array<string, string>
     */
    protected $searchableColumns = [];

    /**
     * @var array<string>
     */
    protected $sortableFields = [];

    /**
     * @var array<string, string>|null
     */
    protected $defaultSortField;

    /**
     * @deprecated Use $defaultSortField instead.
     *
     * @var int
     */
    protected $defaultSortColumnIndex = 0;

    /**
     * @deprecated Use $defaultSortField instead.
     *
     * @var string
     */
    protected $defaultSortDirection = self::SORT_ASC;

    /**
     * @var array
     */
    protected $rawColumns = [];

    /**
     * @var bool
     */
    protected $stateSave = true;

    /**
     * @var bool
     */
    protected $processing = true;

    /**
     * @var bool
     */
    protected $serverSide = true;

    /**
     * @var bool
     */
    protected $paging = true;

    /**
     * @var bool
     */
    protected $ordering = true;

    /**
     * @var bool
     */
    protected $hasSearchableFieldsWithAggregateFunctions = false;

    /**
     * @var array<string, mixed>
     */
    protected array $tableAttributes = [];

    /**
     * @var array<string, mixed>
     */
    protected array $headerAttributes = [];

    /**
     * @return array
     */
    public function getRawColumns()
    {
        return $this->rawColumns;
    }

    /**
     * @return bool
     */
    public function getHasSearchableFieldsWithAggregateFunctions(): bool
    {
        return $this->hasSearchableFieldsWithAggregateFunctions;
    }

    /**
     * @param bool $hasSearchableFieldsWithAggregateFunctions
     *
     * @return $this
     */
    public function setHasSearchableFieldsWithAggregateFunctions(bool $hasSearchableFieldsWithAggregateFunctions)
    {
        $this->hasSearchableFieldsWithAggregateFunctions = $hasSearchableFieldsWithAggregateFunctions;

        return $this;
    }

    /**
     * @return array<string, string>
     */
    public function getTableAttributes(): array
    {
        return $this->tableAttributes;
    }

    /**
     * @param array<string, mixed> $tableAttributes
     *
     * @return void
     */
    public function setTableAttributes(array $tableAttributes): void
    {
        $this->tableAttributes = $tableAttributes;
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function getHeaderAttributes(): array
    {
        return $this->headerAttributes;
    }

    /**
     * @param array<string, mixed> $headerAttributes
     *
     * @return void
     */
    public function setHeaderAttributes(array $headerAttributes): void
    {
        $this->headerAttributes = $headerAttributes;
    }

    /**
     * @param array $rawColumns
     *
     * @return $this
     */
    public function setRawColumns(array $rawColumns)
    {
        $this->rawColumns = $rawColumns;

        return $this;
    }

    /**
     * @param string $column
     *
     * @return $this
     */
    public function addRawColumn($column)
    {
        $this->rawColumns[] = $column;

        return $this;
    }

    /**
     * @return array
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @param array $header Provide php names for table columns
     *   if you are going to user Propel Query as data population
     *
     * @return void
     */
    public function setHeader(array $header)
    {
        if ($this->isAssoc($header)) {
            $this->header = $header;
        }
    }

    /**
     * @return array
     */
    public function getFooter()
    {
        return $this->footer;
    }

    /**
     * @param array $footer
     *
     * @return void
     */
    public function setFooter(array $footer)
    {
        $this->footer = $footer;
    }

    /**
     * @return $this
     */
    public function setFooterFromHeader()
    {
        if (!$this->getHeader()) {
            return $this;
        }

        $headerKeys = array_keys($this->getHeader());
        $this->setFooter($headerKeys);

        return $this;
    }

    /**
     * @param array<string> $extraColumns
     *
     * @return $this
     */
    public function setExtraColumns(array $extraColumns)
    {
        $this->extraColumns = $extraColumns;

        return $this;
    }

    /**
     * @return array<string>
     */
    public function getExtraColumns()
    {
        return $this->extraColumns;
    }

    /**
     * @return array<string>
     */
    public function getSortable()
    {
        return $this->sortableFields;
    }

    /**
     * @param array<string> $sortable
     *
     * @return void
     */
    public function setSortable(array $sortable)
    {
        $this->sortableFields = array_intersect($sortable, array_keys($this->header));
    }

    /**
     * @return array<string>
     */
    public function getSearchable()
    {
        return $this->searchableFields ?: array_keys($this->header);
    }

    /**
     * @param array<string> $searchable
     *
     * @return void
     */
    public function setSearchable(array $searchable)
    {
        $this->searchableFields = $searchable;
    }

    /**
     * @return int
     */
    public function getPageLength()
    {
        return $this->pageLength;
    }

    /**
     * @param int $length
     *
     * @return void
     */
    public function setPageLength($length)
    {
        $this->pageLength = $length;
    }

    /**
     * @return string|null
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return void
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @param string $field
     * @param string $direction
     *
     * @return void
     */
    public function setDefaultSortField($field, $direction = self::SORT_ASC)
    {
        $this->defaultSortField = [$field => $direction];
    }

    /**
     * @return array<string, string>
     */
    public function getDefaultSortField()
    {
        return $this->defaultSortField;
    }

    /**
     * @deprecated Use {@link setDefaultSortField()} instead.
     *
     * @param int $columnIndex
     *
     * @return void
     */
    public function setDefaultSortColumnIndex($columnIndex)
    {
        $this->defaultSortColumnIndex = $columnIndex;
    }

    /**
     * @deprecated Use {@link getDefaultSortField()} instead.
     *
     * @return int
     */
    public function getDefaultSortColumnIndex()
    {
        return $this->defaultSortColumnIndex;
    }

    /**
     * @deprecated Use {@link setDefaultSortField()} instead.
     *
     * @param string $direction
     *
     * @return void
     */
    public function setDefaultSortDirection($direction)
    {
        $this->defaultSortDirection = $direction;
    }

    /**
     * @deprecated Use {@link getDefaultSortField()} instead.
     *
     * @return string
     */
    public function getDefaultSortDirection()
    {
        return $this->defaultSortDirection;
    }

    /**
     * @param array $arr
     *
     * @return bool
     */
    protected function isAssoc(array $arr)
    {
        return (array_values($arr) !== $arr);
    }

    /**
     * @return bool
     */
    public function isStateSave()
    {
        return $this->stateSave;
    }

    /**
     * @param bool $stateSave
     *
     * @return void
     */
    public function setStateSave($stateSave)
    {
        $this->stateSave = $stateSave;
    }

    /**
     * @return bool|null
     */
    public function isProcessing(): ?bool
    {
        return $this->processing;
    }

    /**
     * @param bool $processing
     *
     * @return void
     */
    public function setProcessing(bool $processing)
    {
        $this->processing = $processing;
    }

    /**
     * @return bool|null
     */
    public function isServerSide(): ?bool
    {
        return $this->serverSide;
    }

    /**
     * @param bool $serverSide
     *
     * @return void
     */
    public function setServerSide(bool $serverSide)
    {
        $this->serverSide = $serverSide;
    }

    /**
     * @return bool|null
     */
    public function isPaging(): ?bool
    {
        return $this->paging;
    }

    /**
     * @param bool $paging
     *
     * @return void
     */
    public function setPaging(bool $paging)
    {
        $this->paging = $paging;
    }

    /**
     * @return bool|null
     */
    public function isOrdering(): ?bool
    {
        return $this->ordering;
    }

    /**
     * @param bool $ordering
     *
     * @return void
     */
    public function setOrdering(bool $ordering)
    {
        $this->ordering = $ordering;
    }

    /**
     * @return array<string, string>
     */
    public function getSearchableColumns(): array
    {
        return $this->searchableColumns;
    }

    /**
     * @param array<string, string> $searchableColumns
     *
     * @return void
     */
    public function setSearchableColumns(array $searchableColumns): void
    {
        $this->searchableColumns = $searchableColumns;
    }
}
