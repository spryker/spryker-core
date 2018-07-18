<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Communication\Plugin\ProductAlternative;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductAlternativeExtension\Dependency\Plugin\ProductApplicableLabelAlternativePluginInterface;

/**
 * @method \Spryker\Zed\Availability\Business\AvailabilityBusinessFactory getFactory()
 * @method \Spryker\Zed\Availability\Business\AvailabilityFacadeInterface getFacade()
 */
class AvailableProductCheckLabelAlternativePlugin extends AbstractPlugin implements ProductApplicableLabelAlternativePluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProduct
     *
     * @return bool
     */
    public function check(int $idProduct): bool
    {
        return $this->getFacade()->isProductConcreteUnAvailable($idProduct);
    }
}
