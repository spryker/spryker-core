<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductSearch\Code\KeyBuilder;

interface GlossaryKeyBuilderInterface
{
    /**
     * @param string $attributeKey
     *
     * @return string
     */
    public function buildGlossaryKey($attributeKey);
}
