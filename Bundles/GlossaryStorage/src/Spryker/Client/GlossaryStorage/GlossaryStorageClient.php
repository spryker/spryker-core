<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\GlossaryStorage;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\GlossaryStorage\GlossaryStorageFactory getFactory()
 */
class GlossaryStorageClient extends AbstractClient implements GlossaryStorageClientInterface
{

    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\StorageAvailabilityTransfer
     */
    public function getAvailabilityAbstractAsStorageTransfer($idProductAbstract)
    {
        return $this->getFactory()
            ->createAvailabilityKeyValueStorage()
            ->getAvailabilityAbstractAsStorageTransfer($idProductAbstract);
    }

    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\SpyAvailabilityAbstractTransfer
     */
    public function getAvailabilityAbstract($idProductAbstract)
    {
        return $this->getFactory()
            ->createAvailabilityKeyValueStorage()
            ->getAvailabilityAbstract($idProductAbstract);
    }

}
