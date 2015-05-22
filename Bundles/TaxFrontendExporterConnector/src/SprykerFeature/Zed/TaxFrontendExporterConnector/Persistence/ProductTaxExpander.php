<?php

namespace SprykerFeature\Zed\TaxFrontendExporterConnector\Persistence;

use SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyAbstractProductTableMap;
use SprykerFeature\Zed\Tax\Persistence\Propel\Map\SpyTaxSetTableMap;
use SprykerFeature\Zed\Tax\Persistence\TaxQueryContainer;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;

class ProductTaxExpander implements ProductTaxExpanderInterface
{
    /**
     * @var TaxQueryContainer
     */
    protected $taxQueryContainer;

    /**
     * @param TaxQueryContainer $taxQueryContainer
     */
    public function __construct(TaxQueryContainer $taxQueryContainer)
    {
        $this->taxQueryContainer = $taxQueryContainer;
    }

    /**
     * @param ModelCriteria $expandableQuery
     *
     * @return ModelCriteria
     */
    public function expandQuery(ModelCriteria $expandableQuery)
    {
        $expandableQuery
            ->addJoin(
                SpyAbstractProductTableMap::COL_FK_TAX_SET,
                SpyTaxSetTableMap::COL_ID_TAX_SET,
                Criteria::LEFT_JOIN // @TODO Check workflow of Criteria::INNER_JOIN should be used instead
            )
        ;

        $expandableQuery->withColumn(
            SpyTaxSetTableMap::COL_NAME,
            'tax_set_name'
        );

        $this->taxQueryContainer->joinTaxRates($expandableQuery);

        return $expandableQuery;
    }
}
