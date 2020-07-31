<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Business\Installer\Index\Mapping;

use Elastica\Index;
use Elastica\Mapping;

class MappingBuilder implements MappingBuilderInterface
{
    /**
     * @param array $mappings
     * @param \Elastica\Index $index
     *
     * @return \Elastica\Mapping
     */
    public function buildMapping(array $mappings, Index $index)
    {
        $mappingData = $this->getMappingData($mappings);
        $mapping = new Mapping();

        foreach ($mappingData as $key => $value) {
            $mapping->setParam($key, $value);
        }

        return $mapping;
    }

    /**
     * @param array $mappings
     *
     * @return array
     */
    protected function getMappingData(array $mappings): array
    {
        return $mappings ? array_shift($mappings) : [];
    }
}
