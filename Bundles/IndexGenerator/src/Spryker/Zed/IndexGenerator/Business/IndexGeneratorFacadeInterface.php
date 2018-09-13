<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\IndexGenerator\Business;

interface IndexGeneratorFacadeInterface
{
    /**
     * Specification:
     * - Loads all merged schema files from src/Propel/{STORE}/Schema on a project level.
     * - Checks all loaded files for foreign key references and if they have an index defined.
     * - Generates new schema files in the projects IndexGenerator module with all missing foreign key indexes.
     *
     * @api
     *
     * @return void
     */
    public function generateIndexSchemaFiles(): void;

    /**
     * Specification:
     * - Remove all generated index schema files.
     *
     * @api
     *
     * @return void
     */
    public function removeIndexSchemaFiles(): void;
}
