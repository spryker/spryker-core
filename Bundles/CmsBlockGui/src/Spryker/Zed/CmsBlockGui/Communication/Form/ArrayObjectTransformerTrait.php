<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockGui\Communication\Form;

use ArrayObject;
use Symfony\Component\Form\CallbackTransformer;

trait ArrayObjectTransformerTrait
{
    /**
     * @return \Symfony\Component\Form\CallbackTransformer
     */
    protected function createArrayObjectModelTransformer()
    {
        return new CallbackTransformer(
            function ($value) {
                return (array)$value;
            },
            function ($value) {
                return new ArrayObject($value);
            }
        );
    }
}
