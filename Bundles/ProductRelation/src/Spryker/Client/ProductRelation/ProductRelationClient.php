<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductRelation;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ProductRelation\ProductRelationFactory getFactory()
 */
class ProductRelationClient extends AbstractClient implements ProductRelationClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\StorageProductRelationsTransfer[]
     */
    public function getProductRelationsByIdProductAbstract($idProductAbstract)
    {
         $localeName = $this->getFactory()
             ->getLocaleClient()
             ->getCurrentLocale();

         return $this->getFactory()
             ->createProductRelationStorage($localeName)
             ->getAll($idProductAbstract);
    }
}
