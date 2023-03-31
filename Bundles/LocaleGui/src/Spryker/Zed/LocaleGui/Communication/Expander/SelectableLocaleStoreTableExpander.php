<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\LocaleGui\Communication\Expander;

use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class SelectableLocaleStoreTableExpander implements LocaleStoreTableExpanderInterface
{
    /**
     * @var string
     */
    protected const COLUMN_SELECT_CHECKBOX = 'select-checkbox';

    /**
     * @var string
     */
    protected const URL_PATH_PREFIX_SELECTABLE = '-selectable';

    /**
     * @var string
     */
    protected const COLUMN_TITLE_SELECT_CHECKBOX = 'Select';

    /**
     * @var string
     */
    protected const CHECKBOX_CLASS = 'js-locale-checkbox';

    /**
     * @uses \Spryker\Zed\LocaleGui\Communication\Table\LocaleStoreTable::COLUMN_NAME
     *
     * @var string
     */
    protected const COLUMN_NAME = 'locale_name';

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    public function expandConfiguration(TableConfiguration $config): TableConfiguration
    {
        $config->setHeader(
            $config->getHeader() + [
                static::COLUMN_SELECT_CHECKBOX => static::COLUMN_TITLE_SELECT_CHECKBOX,
            ],
        );

        $config->addRawColumn(static::COLUMN_SELECT_CHECKBOX);

        return $config;
    }

    /**
     * @param string $urlPath
     *
     * @return string
     */
    public function expandUrlPath(string $urlPath): string
    {
        return $urlPath . static::URL_PATH_PREFIX_SELECTABLE;
    }

    /**
     * @param array<string> $row
     *
     * @return array<string>
     */
    public function expandRow(array $row): array
    {
        $row[static::COLUMN_SELECT_CHECKBOX] = $this->getSelectCheckboxColumn($row);

        return $row;
    }

    /**
     * @param array<string> $row
     *
     * @return string
     */
    protected function getSelectCheckboxColumn(array $row): string
    {
        return sprintf(
            '<input class="%s" type="checkbox" value="%s" data-info="%s"/>',
            static::CHECKBOX_CLASS,
            $row[static::COLUMN_NAME],
            htmlspecialchars(json_encode($row, JSON_THROW_ON_ERROR)),
        );
    }
}
