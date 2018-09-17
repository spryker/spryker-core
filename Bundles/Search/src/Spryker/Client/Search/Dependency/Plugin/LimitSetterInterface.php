<?php

namespace Spryker\Client\Search\Dependency\Plugin;

interface LimitSetterInterface
{
    /**
     * @api
     *
     * @param int $limit
     *
     * @return void
     */
    public function setLimit(int $limit): void;
}