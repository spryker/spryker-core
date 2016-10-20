<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UtilText;

use Spryker\Shared\UtilText\UtilTextConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class UtilTextConfig extends AbstractBundleConfig
{

    /**
     * @return array
     */
    public function getAttributeTypeChoices()
    {
        return [
            'text' => 'text',
            'textarea' => 'textarea',
            'number' => 'number',
            'float' => 'float',
            'date' => 'date',
            'time' => 'time',
            'datetime' => 'datetime',
            'select' => 'select',
        ];
    }

    /**
     * @return string
     */
    public function getImageUrlPrefix()
    {
        return $this->get(UtilTextConstants::PRODUCT_MANAGEMENT_URL_PREFIX);
    }

    /**
     * @return string
     */
    public function getHostYves()
    {
        return $this->get(UtilTextConstants::PRODUCT_MANAGEMENT_URL_PREFIX);
    }

}
