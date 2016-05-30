<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Persistence\Pdo;

class AbstractBulkTouchQuery
{

    protected $queryTemplate;

    protected $queryGlue = "; \n";

    /**
     * @var array
     */
    protected $queries = [];

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
    protected function getQueryTemplate()
    {
        return $this->queryTemplate;
    }

    /**
     * @return string
     */
    protected function getQueryGlue()
    {
        return $this->queryGlue;
    }

}
