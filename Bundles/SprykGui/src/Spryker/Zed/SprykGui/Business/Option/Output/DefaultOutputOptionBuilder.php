<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Business\Option\Output;

use Generated\Shared\Transfer\ModuleTransfer;
use Generated\Shared\Transfer\ReturnTypeCollectionTransfer;
use Generated\Shared\Transfer\ReturnTypeTransfer;
use Spryker\Zed\SprykGui\Business\Option\OptionBuilderInterface;

class DefaultOutputOptionBuilder implements OptionBuilderInterface
{
    /**
     * @var array
     */
    protected $types = [
        'void',
        'string',
        'bool',
        'array',
        'int',
        'float',
        'mixed',
        'object',
        'callback',
        'iterable',
    ];

    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnTypeCollectionTransfer
     */
    public function build(ModuleTransfer $moduleTransfer): ModuleTransfer
    {
        $optionsTransfer = $moduleTransfer->requireOptions()->getOptions();
        $returnTypeCollectionTransfer = $optionsTransfer->getReturnTypeCollection();
        if (!$returnTypeCollectionTransfer) {
            $returnTypeCollectionTransfer = new ReturnTypeCollectionTransfer();
        }

        foreach ($this->types as $type) {
            $returnTypeTransfer = new ReturnTypeTransfer();
            $returnTypeTransfer
                ->setType($type)
                ->setIsPhpSeven(true);

            $returnTypeCollectionTransfer->addReturnType($returnTypeTransfer);
        }

        $optionsTransfer->setReturnTypeCollection($returnTypeCollectionTransfer);
        $moduleTransfer->setOptions($optionsTransfer);

        return $moduleTransfer;
    }
}
