<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PropelOrmExtension\Dependency\Plugin;

/**
 * This plugin is created for extension of Propel's postDelete() method generation
 */
interface PostDeleteExtensionPluginInterface extends DeclareClassesToBeUsedInterface
{
    /**
     * Specification:
     * - Used to extend postDelete() method in Propel's objects.
     *
     * @api
     *
     * @param string $script
     *
     * @return string
     */
    public function extend(string $script): string;
}
