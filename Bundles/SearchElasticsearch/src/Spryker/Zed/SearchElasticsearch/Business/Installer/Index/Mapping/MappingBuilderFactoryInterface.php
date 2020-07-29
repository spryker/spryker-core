<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Business\Installer\Index\Mapping;

/**
 * @deprecated Will be removed once the support of Elasticsearch 6 and lower is dropped.
 */
interface MappingBuilderFactoryInterface
{
    /**
     * @return \Spryker\Zed\SearchElasticsearch\Business\Installer\Index\Mapping\MappingBuilderInterface
     */
    public function createMappingBuilder(): MappingBuilderInterface;
}
