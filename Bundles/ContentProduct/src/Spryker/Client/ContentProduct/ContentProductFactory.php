<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentProduct;

use Spryker\Client\Kernel\AbstractFactory;

class ContentProductFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ContentProduct\Dependency\Client\ContentProductToContentStorageClientInterface
     */
    public function getContentStorageClient(): ContentProductToContentStorageClientInterface
    {
        return $this->getProvidedDependency(ContentProductDependencyProvider::CLIENT_CONTENT_STORAGE);
    }
}
