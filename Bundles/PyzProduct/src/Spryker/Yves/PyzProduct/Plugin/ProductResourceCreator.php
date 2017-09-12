<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Yves\PyzProduct\Plugin;

use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Yves\PyzProduct\PyzProductFactory getFactory()
 */
class ProductResourceCreator extends AbstractPlugin
{

    /**
     * @return \Spryker\Yves\PyzProduct\Plugin\ProductResourceCreator
     */
    public function createProductResourceCreator()
    {
        return $this->getFactory()->createProductResourceCreator();
    }

}
