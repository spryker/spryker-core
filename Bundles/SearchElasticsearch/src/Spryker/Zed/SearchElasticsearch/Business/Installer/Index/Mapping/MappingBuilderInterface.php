<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Business\Installer\Index\Mapping;

use Elastica\Index;

interface MappingBuilderInterface
{
    /**
     * @param array $mappings
     * @param \Elastica\Index $index
     *
     * @return \Elastica\Mapping|\Elastica\Type\Mapping
     */
    public function buildMapping(array $mappings, Index $index);
}
