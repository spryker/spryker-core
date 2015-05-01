<?php

/*
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerFeature\Zed\Glossary\Communication\Grid;

use SprykerFeature\Zed\Ui\Dependency\Grid\AbstractGrid;

class TranslationGrid extends AbstractGrid
{
    const LOCALE = 'locale';
    const TRANSLATION = 'translation';
    const TRANSLATION_IS_ACTIVE = 'translation_is_active';
    const GLOSSARY_KEY = 'glossary_key';
    const GLOSSARY_KEY_IS_ACTIVE = 'glossary_key_is_active';

    /**
     * @return array
     */
    public function definePlugins()
    {
        $plugins = [
            $this->createDefaultRowRenderer(),
            $this->createPagination(),
            $this->createDefaultColumn()
                ->setName(self::LOCALE)
                ->filterable()
                ->sortable(),
            $this->createDefaultColumn()
                ->setName(self::TRANSLATION)
                ->filterable()
                ->sortable(),
            $this->createDefaultColumn()
                ->setName(self::TRANSLATION_IS_ACTIVE)
                ->filterable()
                ->sortable(),
            $this->createDefaultColumn()
                ->setName(self::GLOSSARY_KEY)
                ->filterable()
                ->sortable(),
            $this->createDefaultColumn()
                ->setName(self::GLOSSARY_KEY_IS_ACTIVE)
                ->filterable()
                ->sortable()
        ];

        return $plugins;
    }

}