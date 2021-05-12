<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityStorage\Business\Storage;

interface AvailabilityStorageInterface
{
    /**
     * @param array $availabilityIds
     *
     * @return void
     */
    public function publish(array $availabilityIds);

    /**
     * @param array $availabilityIds
     *
     * @return void
     */
    public function unpublish(array $availabilityIds);

    /**
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function publishByProductAbstractIds(array $productAbstractIds): void;

    /**
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function unpublishByProductAbstractIds(array $productAbstractIds): void;
}
