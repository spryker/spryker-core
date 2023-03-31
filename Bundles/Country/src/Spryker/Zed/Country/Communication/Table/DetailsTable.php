<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Country\Communication\Table;

use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class DetailsTable extends AbstractTable
{
    /**
     * @var \Orm\Zed\Country\Persistence\SpyCountryQuery<mixed>
     */
    protected $countryQuery;

    /**
     * @param \Orm\Zed\Country\Persistence\SpyCountryQuery<mixed> $countryQuery
     */
    public function __construct(SpyCountryQuery $countryQuery)
    {
        $this->countryQuery = $countryQuery;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config->setHeader(
            [
                'header1' => 'First header',
            ],
        );

        $config->setSortable(['header1']);

        return $config;
    }

    /**
     * @phpstan-return \Propel\Runtime\Collection\ObjectCollection<mixed>|array<mixed>
     *
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Propel\Runtime\Collection\ObjectCollection|array
     */
    protected function prepareData(TableConfiguration $config)
    {
        return $this->runQuery($this->countryQuery, $config);
    }
}
