<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer\Business\Model\Generator;

/**
 * @method string getName()
 * @method string getTransferName()
 */
interface DataBuilderDefinitionInterface extends DefinitionInterface
{
    /**
     * @return array
     */
    public function getRules();

    /**
     * @return array
     */
    public function getDependencies();
}
