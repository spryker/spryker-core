<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerUserConnectorGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CustomerUserConnectionUpdateTransfer;

class CustomerUserConnectorFormDataProvider
{
    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            'data_class' => CustomerUserConnectionUpdateTransfer::class,
        ];
    }
}
