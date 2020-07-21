<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Business\Installer\Index\Mapping;

use Elastica\Index;
use Elastica\Mapping;
use Generated\Shared\Transfer\IndexDefinitionTransfer;

class MappingBuilder implements MappingBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\IndexDefinitionTransfer $indexDefinitionTransfer
     * @param \Elastica\Index $index
     *
     * @return \Elastica\Mapping
     */
    public function buildMapping(IndexDefinitionTransfer $indexDefinitionTransfer, Index $index)
    {
        $mappingData = $this->getMappingData($indexDefinitionTransfer);
        $mapping = new Mapping();

        foreach ($mappingData as $key => $value) {
            $mapping->setParam($key, $value);
        }

        return $mapping;
    }

    /**
     * @param \Generated\Shared\Transfer\IndexDefinitionTransfer $indexDefinitionTransfer
     *
     * @return array
     */
    protected function getMappingData(IndexDefinitionTransfer $indexDefinitionTransfer): array
    {
        $mappings = $indexDefinitionTransfer->getMappings();

        return array_shift($mappings) ?: [];
    }
}
