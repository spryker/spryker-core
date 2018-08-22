<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestRequestValidator\Business\Collector\SchemaFinder;

use Symfony\Component\Finder\Finder;

interface RestRequestValidatorSchemaFinderInterface
{
    /**
     * @return \Symfony\Component\Finder\Finder
     */
    public function findSchemas(): Finder;
}
