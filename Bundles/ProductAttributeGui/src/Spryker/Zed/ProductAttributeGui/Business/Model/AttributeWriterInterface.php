<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeGui\Business\Model;

interface AttributeWriterInterface
{

    public function saveProductAbstractAttributes(array $attributes);

    public function saveProductAbstractLocalizedAttributes(array $attributes);

}
