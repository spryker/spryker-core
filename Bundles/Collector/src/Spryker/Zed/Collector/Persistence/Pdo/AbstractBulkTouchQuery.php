<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Persistence\Pdo;

abstract class AbstractBulkTouchQuery
{
    /**
     * @var array
     */
    protected $queries = [];

    /**
     * @return string
     */
    abstract protected function getQueryTemplate();

    /**
     * @return string
     */
    public function getRawSqlString()
    {
        return implode($this->getQueryGlue(), $this->queries);
    }

    /**
     * @return void
     */
    public function flushQueries()
    {
        $this->queries = [];
    }

    /**
     * @return string
     */
    protected function getQueryGlue()
    {
        return "; \n";
    }

    /**
     * @param array $data
     * @param string $separator
     *
     * @return string
     */
    protected function arrayToSqlValueString(array $data, $separator = ',')
    {
        return rtrim(
            implode($separator, $data),
            $separator
        );
    }
}
