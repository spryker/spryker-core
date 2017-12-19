<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductStorage;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ProductStorage\ProductStorageFactory getFactory()
 */
class ProductStorageClient extends AbstractClient implements ProductStorageClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $data
     * @param array $selectedAttributes
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function mapProductStorageDataForCurrentLocale(array $data, array $selectedAttributes = [])
    {
        $locale = $this->getFactory()
            ->getLocaleClient()
            ->getCurrentLocale();

        return $this->getFactory()
            ->createProductStorageDataMapper()
            ->mapProductStorageData($locale, $data, $selectedAttributes);
    }
}
