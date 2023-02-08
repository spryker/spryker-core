<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseUserGui\Communication\Form\DataProvider;

interface WarehouseUserFormDataProviderInterface
{
    /**
     * @param string $userUuid
     *
     * @return array<string, mixed>
     */
    public function getData(string $userUuid): array;
}
