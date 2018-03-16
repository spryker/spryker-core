<?php


namespace Spryker\Zed\CompanyBusinessUnitGui\Communication\Table;


use Orm\Zed\CompanyBusinessUnit\Persistence\Map\SpyCompanyBusinessUnitTableMap;
use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class CompanyBusinessUnitTable extends AbstractTable
{

    public const COL_ID_COMPANY_BUSINESS_UNIT = SpyCompanyBusinessUnitTableMap::COL_ID_COMPANY_BUSINESS_UNIT;
    public const COL_NAME = SpyCompanyBusinessUnitTableMap::COL_NAME;
    public const COL_IBAN = SpyCompanyBusinessUnitTableMap::COL_IBAN;
    public const COL_BIC = SpyCompanyBusinessUnitTableMap::COL_BIC;
    public const COL_ACTIONS = 'Actions';

    public const REQUEST_ID_COMPANY_BUSINESS_UNIT = 'id-company-business-unit';

    public const URL_COMPANY_BUSINESS_UNIT_EDIT = '/company-business-unit-gui/edit-company-business-unit/index?%s=%d';

    /**
     * @var SpyCompanyBusinessUnitQuery
     */
    protected $companyBusinessUnitQuery;

    /**
     * @param SpyCompanyBusinessUnitQuery $companyBusinessUnitQuery
     */
    public function __construct(SpyCompanyBusinessUnitQuery $companyBusinessUnitQuery)
    {
        $this->companyBusinessUnitQuery = $companyBusinessUnitQuery;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            static::COL_ID_COMPANY_BUSINESS_UNIT => 'Id',
            static::COL_NAME => 'Name',
            static::COL_IBAN => 'IBAN',
            static::COL_BIC => 'BIC',
            static::COL_ACTIONS => static::COL_ACTIONS,
        ]);

        $config->addRawColumn(static::COL_ACTIONS);

        $config->setSortable([
            static::COL_ID_COMPANY_BUSINESS_UNIT,
            static::COL_NAME,
        ]);


        $config->setSearchable([
            static::COL_NAME,
        ]);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $queryResults = $this->runQuery($this->companyBusinessUnitQuery, $config);
        $results = [];

        foreach ($queryResults as $item) {
            $results[] = [
                static::COL_ID_COMPANY_BUSINESS_UNIT => $item[SpyCompanyBusinessUnitTableMap::COL_ID_COMPANY_BUSINESS_UNIT],
                static::COL_NAME => $item[SpyCompanyBusinessUnitTableMap::COL_NAME],
                static::COL_IBAN => $item[SpyCompanyBusinessUnitTableMap::COL_IBAN],
                static::COL_BIC => $item[SpyCompanyBusinessUnitTableMap::COL_BIC],
                static::COL_ACTIONS => $this->buildLinks($item),
            ];
        }
        unset($queryResults);

        return $results;
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function buildLinks(array $item)
    {
        $buttons = [];

        $buttons[] = $this->generateEditButton(
            sprintf(static::URL_COMPANY_BUSINESS_UNIT_EDIT, static::REQUEST_ID_COMPANY_BUSINESS_UNIT, $item[static::COL_ID_COMPANY_BUSINESS_UNIT]),
            'Edit'
        );

        return implode(' ', $buttons);
    }
}