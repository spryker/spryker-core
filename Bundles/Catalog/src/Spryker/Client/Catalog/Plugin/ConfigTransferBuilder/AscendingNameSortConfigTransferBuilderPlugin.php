<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog\Plugin\ConfigTransferBuilder;

use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\SortConfigTransfer;
use Spryker\Client\Catalog\Dependency\Plugin\SortConfigTransferBuilderPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

class AscendingNameSortConfigTransferBuilderPlugin extends AbstractPlugin implements SortConfigTransferBuilderPluginInterface
{
    public const NAME = 'name';
    public const PARAMETER_NAME = 'name_asc';

    /**
     * @return \Generated\Shared\Transfer\SortConfigTransfer
     */
    public function build()
    {
        return (new SortConfigTransfer())
            ->setName(static::NAME)
            ->setParameterName(static::PARAMETER_NAME)
            ->setFieldName(PageIndexMap::STRING_SORT);
    }
}
