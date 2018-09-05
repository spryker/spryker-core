<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Dependency\Plugin;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;

interface CategoryRelationReadPluginInterface
{
    /**
     * Specification:
     *  - Returns a descriptive name for the relations
     *
     * @api
     *
     * @return string
     */
    public function getRelationName();

    /**
     * Specification:
     *  - Finds related entities
     *  - Returns a list of string representations for the entities in the given language
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string[]
     */
    public function getRelations(CategoryTransfer $categoryTransfer, LocaleTransfer $localeTransfer);
}
