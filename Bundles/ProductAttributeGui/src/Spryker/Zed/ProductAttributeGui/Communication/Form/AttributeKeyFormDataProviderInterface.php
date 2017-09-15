<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeGui\Communication\Form;

interface AttributeKeyFormDataProviderInterface
{

    /**
     * @return array
     */
    public function getData();

    /**
     * @return array
     */
    public function getOptions();

}
