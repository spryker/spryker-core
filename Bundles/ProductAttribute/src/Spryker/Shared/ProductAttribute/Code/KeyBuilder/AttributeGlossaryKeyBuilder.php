<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductAttribute\Code\KeyBuilder;

class AttributeGlossaryKeyBuilder implements GlossaryKeyBuilderInterface
{

    /**
     * @param string $attributeKey
     *
     * @return string
     */
    public function buildGlossaryKey($attributeKey)
    {
        return 'product.attribute.' . $attributeKey;
    }

}
