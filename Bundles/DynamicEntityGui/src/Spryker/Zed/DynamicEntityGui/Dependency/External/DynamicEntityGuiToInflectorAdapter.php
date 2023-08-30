<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntityGui\Dependency\External;

use Doctrine\Inflector\InflectorFactory;

class DynamicEntityGuiToInflectorAdapter implements DynamicEntityGuiToInflectorInterface
{
    /**
     * @param string $word
     *
     * @return string
     */
    public function pluralize(string $word): string
    {
        $inflector = InflectorFactory::create()->build();

        return $inflector->pluralize($word);
    }
}
