<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer\Business\Model\Generator;

interface ClassDefinitionInterface extends DefinitionInterface
{

    /**
     * @return array
     */
    public function getConstants();

    /**
     * @return array
     */
    public function getProperties();

    /**
     * @return array
     */
    public function getConstructorDefinition();

    /**
     * @return array
     */
    public function getMethods();

    /**
     * @return array
     */
    public function getNormalizedProperties();

}
