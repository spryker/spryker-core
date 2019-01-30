<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ContentProductsRestApi;

use Spryker\Glue\ContentProductsRestApi\Processor\ContentProductReader;
use Spryker\Glue\ContentProductsRestApi\Processor\ContentProductReaderInterface;
use Spryker\Glue\Kernel\AbstractFactory;

class ContentProductsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ContentProductsRestApi\Processor\ContentProductReaderInterface
     */
    public function createContentProductReader(): ContentProductReaderInterface
    {
        return new ContentProductReader(
            $this->getResourceBuilder()
        );
    }
}
