<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AvailabilityStorage\Storage;

use Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer;

interface AvailabilityStorageReaderInterface
{
    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\StorageAvailabilityTransfer
     */
    public function getAvailabilityAbstractAsStorageTransfer($idProductAbstract);

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer|null
     */
    public function findAbstractProductAvailability(int $idProductAbstract): ?ProductAbstractAvailabilityTransfer;

    /**
     * @deprecated Use {@link \Spryker\Client\AvailabilityStorage\AvailabilityStorageClientInterface::findProductAbstractAvailability()} instead.
     *
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\SpyAvailabilityAbstractEntityTransfer
     */
    public function getAvailabilityAbstract($idProductAbstract);
}
