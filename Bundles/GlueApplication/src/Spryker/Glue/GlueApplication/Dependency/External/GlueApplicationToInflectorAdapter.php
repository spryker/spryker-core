<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Dependency\External;

use Doctrine\Inflector\InflectorFactory;

class GlueApplicationToInflectorAdapter implements GlueApplicationToInflectorInterface
{
    /**
     * @param string $word
     *
     * @return string
     */
    public function singularize(string $word): string
    {
        $inflector = InflectorFactory::create()->build();

        return $inflector->singularize($word);
    }
}
