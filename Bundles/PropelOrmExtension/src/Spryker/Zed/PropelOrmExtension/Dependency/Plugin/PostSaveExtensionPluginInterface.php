<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PropelOrmExtension\Dependency\Plugin;

/**
 * Executes after Propel postSave() method is generated
 */
interface PostSaveExtensionPluginInterface extends DeclareClassesToBeUsedInterface
{
    /**
     * Specification:
     * - Used to extend postSave() method in Propel's query objects.
     *
     * @api
     *
     * @param string $script
     *
     * @return string
     */
    public function extend(string $script): string;
}
