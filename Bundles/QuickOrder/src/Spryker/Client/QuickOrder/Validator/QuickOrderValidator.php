<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuickOrder\Validator;

use Generated\Shared\Transfer\QuickOrderTransfer;

class QuickOrderValidator implements QuickOrderValidatorInterface
{
    /**
     * @var \Spryker\Client\QuickOrderExtension\Dependency\Plugin\QuickOrderValidatorPluginInterface[]
     */
    protected $quickOrderValidatorPlugins;

    /**
     * @param \Spryker\Client\QuickOrderExtension\Dependency\Plugin\QuickOrderValidatorPluginInterface[] $quickOrderValidatorPlugins
     */
    public function __construct(array $quickOrderValidatorPlugins)
    {
        $this->quickOrderValidatorPlugins = $quickOrderValidatorPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrderTransfer
     *
     * @return \Generated\Shared\Transfer\QuickOrderTransfer
     */
    public function validate(QuickOrderTransfer $quickOrderTransfer): QuickOrderTransfer
    {
        foreach ($this->quickOrderValidatorPlugins as $quickOrderValidatorPlugin) {
            $quickOrderTransfer = $quickOrderValidatorPlugin->validate($quickOrderTransfer);
        }

        return $quickOrderTransfer;
    }
}
