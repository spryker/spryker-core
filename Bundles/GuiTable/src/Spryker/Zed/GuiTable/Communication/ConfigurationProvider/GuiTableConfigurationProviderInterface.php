<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GuiTable\Communication\ConfigurationProvider;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;

interface GuiTableConfigurationProviderInterface
{
    public const COLUMN_TYPE_TEXT = 'text';
    public const COLUMN_TYPE_IMAGE = 'image';
    public const COLUMN_TYPE_DATE = 'date';
    public const COLUMN_TYPE_CHIP = 'chip';
    public const COLUMN_TYPE_LIST = 'list';

    public const FILTER_TYPE_SELECT = 'select';
    public const FILTER_TYPE_DATE_RANGE = 'date-range';

    /**
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    public function getConfiguration(): GuiTableConfigurationTransfer;
}
