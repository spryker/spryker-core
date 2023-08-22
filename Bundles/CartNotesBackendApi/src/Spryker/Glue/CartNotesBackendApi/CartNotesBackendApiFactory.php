<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartNotesBackendApi;

use Spryker\Glue\CartNotesBackendApi\Processor\Mapper\CartNotesOrdersBackendApiAttributesMapper;
use Spryker\Glue\CartNotesBackendApi\Processor\Mapper\CartNotesOrdersBackendApiAttributesMapperInterface;
use Spryker\Glue\Kernel\Backend\AbstractFactory;

class CartNotesBackendApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\CartNotesBackendApi\Processor\Mapper\CartNotesOrdersBackendApiAttributesMapperInterface
     */
    public function createCartNotesOrdersBackendApiAttributesMapper(): CartNotesOrdersBackendApiAttributesMapperInterface
    {
        return new CartNotesOrdersBackendApiAttributesMapper();
    }
}
