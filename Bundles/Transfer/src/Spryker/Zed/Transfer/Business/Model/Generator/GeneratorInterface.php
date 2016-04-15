<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer\Business\Model\Generator;

interface GeneratorInterface
{

    /**
     * @param \Spryker\Zed\Transfer\Business\Model\Generator\DefinitionInterface $definition
     *
     * @return string
     */
    public function generate(DefinitionInterface $definition);

}
