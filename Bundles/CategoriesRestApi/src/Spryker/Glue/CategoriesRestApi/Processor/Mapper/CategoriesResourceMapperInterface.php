<?php
/**
 * Created by PhpStorm.
 * User: poidenko
 * Date: 7/24/18
 * Time: 12:55 PM
 */

namespace Spryker\Glue\CategoriesRestApi\Processor\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\RestCategoriesTreeTransfer;

interface CategoriesResourceMapperInterface
{
    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\CategoryNodeStorageTransfer[] $categoriesResource
     *
     * @return \Generated\Shared\Transfer\RestCategoriesTreeTransfer
     */
    public function mapCategoriesResourceToRestCategoriesTransfer(ArrayObject $categoriesResource): RestCategoriesTreeTransfer;
}
