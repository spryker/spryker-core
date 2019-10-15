<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Search\Business\Definition\Merger;

/**
 * @deprecated Use `\Spryker\Zed\SearchElasticsearch\Business\Definition\Merger\IndexDefinitionMergeInterface` instead.
 */
interface IndexDefinitionMergerInterface
{
    /**
     * @param array $definition1
     * @param array $definition2
     *
     * @return array
     */
    public function merge(array $definition1, array $definition2);
}
