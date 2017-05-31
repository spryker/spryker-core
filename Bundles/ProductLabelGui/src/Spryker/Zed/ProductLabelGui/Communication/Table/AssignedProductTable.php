<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui\Communication\Table;

use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class AssignedProductTable extends AbstractProductTable
{

    /**
     * @var string
     */
    protected $tableIdentifier = 'assigned-product-table';

    protected $defaultUrl = 'assigned-product-table';

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this->tableQueryBuilder->buildAssignedProductQuery($this->idProductLabel);

        /** @var \Orm\Zed\Product\Persistence\SpyProductAbstract[] $productAbstractEntities */
        $productAbstractEntities = $this->runQuery($query, $config, true);

        return $this->buildResultData($productAbstractEntities);
    }

}
