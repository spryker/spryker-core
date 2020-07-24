<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Business\Installer\Index\Mapping;

use Spryker\Shared\SearchElasticsearch\MappingType\MappingTypeSupportDetectorInterface;

/**
 * @deprecated Will be removed once the support of Elasticsearch 6 and lower is dropped.
 */
class MappingBuilderFactory implements MappingBuilderFactoryInterface
{
    /**
     * @var \Spryker\Shared\SearchElasticsearch\MappingType\MappingTypeSupportDetectorInterface
     */
    protected $mappingTypeSupportDetector;

    /**
     * @param \Spryker\Shared\SearchElasticsearch\MappingType\MappingTypeSupportDetectorInterface $mappingTypeSupportDetector
     */
    public function __construct(MappingTypeSupportDetectorInterface $mappingTypeSupportDetector)
    {
        $this->mappingTypeSupportDetector = $mappingTypeSupportDetector;
    }

    /**
     * @return \Spryker\Zed\SearchElasticsearch\Business\Installer\Index\Mapping\MappingBuilderInterface
     */
    public function createMappingBuilder(): MappingBuilderInterface
    {
        if ($this->mappingTypeSupportDetector->isMappingTypeSupported()) {
            return new MappingTypeAwareMappingBuilder();
        }

        return new MappingBuilder();
    }
}
