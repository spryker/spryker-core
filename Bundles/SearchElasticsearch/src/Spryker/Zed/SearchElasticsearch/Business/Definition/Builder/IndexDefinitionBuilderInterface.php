<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Business\Definition\Builder;

interface IndexDefinitionBuilderInterface
{
    /**
     * @param string|null $storeName
     *
     * @return array<\Generated\Shared\Transfer\IndexDefinitionTransfer>
     */
    public function build(?string $storeName = null): array;
}
