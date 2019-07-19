<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Business\Installer\Index\Mapping;

use Elastica\Index;
use Elastica\Type\Mapping;

interface MappingBuilderInterface
{
    /**
     * @param \Elastica\Index $index
     * @param string $mappingType
     * @param array $mappingData
     *
     * @return \Elastica\Type\Mapping
     */
    public function buildMapping(Index $index, string $mappingType, array $mappingData): Mapping;
}
