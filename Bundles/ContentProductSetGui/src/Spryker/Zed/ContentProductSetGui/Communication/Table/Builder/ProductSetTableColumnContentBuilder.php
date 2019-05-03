<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductSetGui\Communication\Table\Builder;

use Orm\Zed\ProductSet\Persistence\SpyProductSet;

class ProductSetTableColumnContentBuilder implements ProductSetTableColumnContentBuilderInterface
{
    public const BUTTON_DELETE_LABEL = 'Delete';
    public const BUTTON_SELECT_LABEL = 'Select';

    /**
     * @param \Orm\Zed\ProductSet\Persistence\SpyProductSet $productSetEntity
     *
     * @return string
     */
    public function getDeleteButton(SpyProductSet $productSetEntity): string
    {
        return sprintf(
            '<button type="button" data-id="%s" class="js-delete-product-set btn btn-sm btn-outline btn-danger"><i class="fa fa-trash"></i> %s</button>',
            $productSetEntity->getIdProductSet(),
            static::BUTTON_DELETE_LABEL
        );
    }

    /**
     * @param \Orm\Zed\ProductSet\Persistence\SpyProductSet $productSetEntity
     *
     * @return string
     */
    public function getAddButtonField(SpyProductSet $productSetEntity): string
    {
        return sprintf(
            '<button type="button" data-id="%s" class="btn btn-sm btn-outline btn-create js-add-product-set"><i class="fa fa-plus"></i> %s</button>',
            $productSetEntity->getIdProductSet(),
            static::BUTTON_SELECT_LABEL
        );
    }
}
