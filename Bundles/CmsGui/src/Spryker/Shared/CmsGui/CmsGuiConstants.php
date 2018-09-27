<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\CmsGui;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface CmsGuiConstants
{
    /**
     * Specification:
     * - Defines a custom URI where the CMS pages can be reviewed on Yves side.
     * - Optional: a numerical placeholder for idCmsPage can be inserted in sprintf format
     *
     * @api
     */
    public const CMS_PAGE_PREVIEW_URI = 'CMS_PAGE_PREVIEW_URI';

    /**
     * Specification:
     * - Defines the path of CMS templates.
     *
     * @api
     */
    public const CMS_FOLDER_PATH = 'CMS_FOLDER_PATH';
}
