<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Search\Helper;

trait SearchHelperTrait
{
    /**
     * @return \SprykerTest\Client\Search\Helper\SearchHelper
     */
    protected function getSearchHelper(): SearchHelper
    {
        /** @var \SprykerTest\Client\Search\Helper\SearchHelper $searchHelper */
        $searchHelper = $this->getModule('\\' . SearchHelper::class);

        return $searchHelper;
    }

    /**
     * @param string $name
     *
     * @return \Codeception\Module
     */
    abstract protected function getModule($name);
}
