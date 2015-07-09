<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\TaxFrontendExporterConnector\Persistence;

use SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyAbstractProductTableMap;
use SprykerFeature\Zed\Tax\Persistence\Propel\Map\SpyTaxSetTableMap;
use SprykerFeature\Zed\Tax\Persistence\TaxQueryContainerInterface;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;

class ProductTaxExpander implements ProductTaxExpanderInterface
{

    /**
     * @var TaxQueryContainerInterface
     */
    protected $taxQueryContainer;

    /**
     * @param TaxQueryContainerInterface $taxQueryContainer
     */
    public function __construct(TaxQueryContainerInterface $taxQueryContainer)
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
                Criteria::LEFT_JOIN // @TODO Change to Criteria::INNER_JOIN as soon as there is a Tax GUI/Importer in Zed
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
