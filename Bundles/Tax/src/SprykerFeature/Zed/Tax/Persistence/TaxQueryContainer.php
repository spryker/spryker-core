<?php

namespace SprykerFeature\Zed\Tax\Persistence;

use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Zed\Tax\Persistence\Propel\Map\SpyTaxRateTableMap;
use SprykerFeature\Zed\Tax\Persistence\Propel\Map\SpyTaxSetTableMap;
use SprykerFeature\Zed\Tax\Persistence\Propel\SpyTaxRateQuery;
use SprykerFeature\Zed\Tax\Persistence\Propel\SpyTaxSetQuery;
use Generated\Shared\Transfer\TaxRateTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;

class TaxQueryContainer extends AbstractQueryContainer {

}