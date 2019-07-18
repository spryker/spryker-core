<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\DependencyTree\DependencyGraph;

interface GraphBuilderInterface
{
    public const ENGINE_BUNDLE_FONT_COLOR = 'grey';
    public const ENGINE_BUNDLE_INFO_TEXT = '<br/><font point-size="8">(engine)</font>';

    /**
     * @param array $dependencyTree
     *
     * @return string
     */
    public function build(array $dependencyTree);
}
