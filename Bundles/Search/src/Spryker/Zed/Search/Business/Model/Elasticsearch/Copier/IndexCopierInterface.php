<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\Model\Elasticsearch\Copier;

/**
 * @deprecated Use {@link \Spryker\Zed\SearchElasticsearch\Business\Index\Copier\IndexCopierInterface} instead.
 */
interface IndexCopierInterface
{
    /**
     * @param string $source
     * @param string $target
     *
     * @return bool
     */
    public function copyIndex($source, $target);
}
