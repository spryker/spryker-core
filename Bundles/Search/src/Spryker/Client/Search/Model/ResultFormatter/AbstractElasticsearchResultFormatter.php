<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\ResultFormatter;

use Elastica\ResultSet;

abstract class AbstractElasticsearchResultFormatter implements ResultFormatterInterface
{

    /**
     * @param \Elastica\ResultSet $searchResult
     *
     * @return mixed
     */
    public function formatResult($searchResult)
    {
        $this->assertResultType($searchResult);

        return $this->process($searchResult);
    }

    /**
     * @param $searchResult
     *
     * @return void
     */
    protected function assertResultType($searchResult)
    {
        if (!$searchResult instanceof ResultSet) {
            throw new \InvalidArgumentException(sprintf(
                'Expected search result type was "%s", got "%s" instead.',
                ResultSet::class,
                get_class($searchResult)
            ));
        }
    }

    /**
     * @param \Elastica\ResultSet $searchResult
     *
     * @return mixed
     */
    abstract protected function process(ResultSet $searchResult);

}
