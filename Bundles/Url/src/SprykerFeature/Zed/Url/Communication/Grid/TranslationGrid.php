<?php

namespace SprykerFeature\Zed\Url\Communication\Grid;

use SprykerFeature\Zed\Ui\Dependency\Grid\AbstractGrid;

class TranslationGrid extends AbstractGrid
{
    const ID_LOCALE = 'id_locale';
    const IS_ACTIVE = 'is_active';
    const LOCALE_NAME = 'locale_name';

    public function definePlugins()
    {
        return [
            $this->createDefaultRowRenderer(),
            $this->createPagination(),

            $this->createDefaultColumn()
                ->setName(self::ID_LOCALE)
                ->filterable()
                ->sortable(),

            $this->createDefaultColumn()
                ->setName(self::LOCALE_NAME)
                ->filterable()
                ->sortable(),

            $this->createDefaultColumn()
                ->setName(self::IS_ACTIVE)
                ->filterable()
                ->sortable(),
        ];
    }
}
