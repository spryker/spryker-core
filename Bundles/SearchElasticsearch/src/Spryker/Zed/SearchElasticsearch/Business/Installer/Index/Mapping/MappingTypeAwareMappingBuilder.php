<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Business\Installer\Index\Mapping;

use Elastica\Index;
use Elastica\Type\Mapping;

/**
 * @deprecated Will be removed once the support of Elasticsearch 6 and lower is dropped.
 */
class MappingTypeAwareMappingBuilder implements MappingBuilderInterface
{
    /**
     * @param array $mappings
     * @param \Elastica\Index $index
     *
     * @return \Elastica\Type\Mapping
     */
    public function buildMapping(array $mappings, Index $index)
    {
        $mappingTypeName = array_key_first($mappings);
        $mappingData = $mappingTypeName !== null ? $mappings[$mappingTypeName] : [];
        $mappingType = $index->getType($mappingTypeName);
        $mapping = new Mapping($mappingType);

        foreach ($mappingData as $key => $value) {
            $mapping->setParam($key, $value);
        }

        return $mapping;
    }
}
