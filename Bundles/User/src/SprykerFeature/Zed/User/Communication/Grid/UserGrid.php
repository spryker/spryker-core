<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\User\Communication\Grid;

use SprykerFeature\Zed\Ui\Dependency\Grid\AbstractGrid;

class UserGrid extends AbstractGrid
{

    const ID = 'id_user';
    const FIRST_NAME = 'first_name';
    const LAST_NAME = 'last_name';
    const USERNAME = 'username';
    const PASSWORD = 'password';
    const STATUS = 'status';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function definePlugins()
    {
        return [
            $this->createDefaultRowRenderer(),
            $this->createPagination(),

            $this->createDefaultColumn()
                ->setName(self::ID)
                ->filterable()
                ->sortable(),

            $this->createDefaultColumn()
                ->setName(self::FIRST_NAME)
                ->filterable()
                ->sortable(),

            $this->createDefaultColumn()
                ->setName(self::LAST_NAME)
                ->filterable()
                ->sortable(),

            $this->createDefaultColumn()
                ->setName(self::USERNAME)
                ->filterable()
                ->sortable(),

            $this->createDefaultColumn()
                ->setName(self::PASSWORD)
                ->filterable()
                ->sortable(),

            $this->createDefaultColumn()
                ->setName(self::STATUS)
                ->filterable()
                ->sortable(),

            $this->createDefaultColumn()
                ->setName(self::CREATED_AT)
                ->filterable()
                ->sortable(),

            $this->createDefaultColumn()
                ->setName(self::UPDATED_AT)
                ->filterable()
                ->sortable(),

        ];
    }

}
