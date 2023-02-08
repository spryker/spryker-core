<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseUserGui\Communication\Form\DataProvider;

class WarehouseUserFormDataProvider implements WarehouseUserFormDataProviderInterface
{
    /**
     * @uses \Spryker\Zed\WarehouseUserGui\Communication\Form\WarehouseUserForm::FIELD_USER_UUID
     *
     * @var string
     */
    protected const WAREHOUSE_USER_FORM_FIELD_USER_UUID = 'userUuid';

    /**
     * @uses \Spryker\Zed\WarehouseUserGui\Communication\Form\WarehouseUserForm::FIELD_UUIDS_WAREHOUSES_TO_ASSIGN
     *
     * @var string
     */
    protected const WAREHOUSE_USER_FORM_FIELD_UUIDS_WAREHOUSES_TO_ASSIGN = 'uuidsWarehousesToAssign';

    /**
     * @uses \Spryker\Zed\WarehouseUserGui\Communication\Form\WarehouseUserForm::FIELD_UUIDS_WAREHOUSES_TO_DEASSIGN
     *
     * @var string
     */
    protected const WAREHOUSE_USER_FORM_FIELD_UUIDS_WAREHOUSES_TO_DEASSIGN = 'uuidsWarehousesToDeassign';

    /**
     * @param string $userUuid
     *
     * @return array<string, mixed>
     */
    public function getData(string $userUuid): array
    {
        return [
            static::WAREHOUSE_USER_FORM_FIELD_USER_UUID => $userUuid,
            static::WAREHOUSE_USER_FORM_FIELD_UUIDS_WAREHOUSES_TO_ASSIGN => [],
            static::WAREHOUSE_USER_FORM_FIELD_UUIDS_WAREHOUSES_TO_DEASSIGN => [],
        ];
    }
}
