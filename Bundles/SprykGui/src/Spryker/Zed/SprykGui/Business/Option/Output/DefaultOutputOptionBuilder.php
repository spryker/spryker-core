<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Business\Option\Output;

use Generated\Shared\Transfer\ModuleTransfer;
use Generated\Shared\Transfer\ReturnTypeCollectionTransfer;
use Generated\Shared\Transfer\ReturnTypeTransfer;
use Spryker\Zed\SprykGui\Business\Finder\AccessibleTransfer\AccessibleTransferFinderInterface;
use Spryker\Zed\SprykGui\Business\Option\OptionBuilderInterface;
use Spryker\Zed\SprykGui\Business\PhpInternal\Type\TypeInterface;

class DefaultOutputOptionBuilder implements OptionBuilderInterface
{
    /**
     * @var \Spryker\Zed\SprykGui\Business\Finder\AccessibleTransfer\AccessibleTransferFinderInterface
     */
    protected $accessibleTransferFinder;

    /**
     * @var \Spryker\Zed\SprykGui\Business\PhpInternal\Type\TypeInterface
     */
    protected $types;

    /**
     * @param \Spryker\Zed\SprykGui\Business\Finder\AccessibleTransfer\AccessibleTransferFinderInterface $accessibleTransferFinder
     * @param \Spryker\Zed\SprykGui\Business\PhpInternal\Type\TypeInterface $types
     */
    public function __construct(AccessibleTransferFinderInterface $accessibleTransferFinder, TypeInterface $types)
    {
        $this->accessibleTransferFinder = $accessibleTransferFinder;
        $this->types = $types;
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return \Generated\Shared\Transfer\ModuleTransfer
     */
    public function build(ModuleTransfer $moduleTransfer): ModuleTransfer
    {
        $optionsTransfer = $moduleTransfer->requireOptions()->getOptions();
        $returnTypeCollectionTransfer = $optionsTransfer->getReturnTypeCollection();

        if (!$returnTypeCollectionTransfer) {
            $returnTypeCollectionTransfer = new ReturnTypeCollectionTransfer();
        }

        $accessibleTransferCollection = $this->accessibleTransferFinder->findAccessibleTransfers($moduleTransfer->getName());

        foreach ($accessibleTransferCollection->getTransferClassNames() as $transferClassName) {
            $classNameFragments = explode('\\', $transferClassName);
            $classNameShort = array_pop($classNameFragments);
            $returnTypeTransfer = new ReturnTypeTransfer();
            $returnTypeTransfer
                ->setName($classNameShort)
                ->setType($transferClassName)
                ->setIsPhpSeven(false);

            $returnTypeCollectionTransfer->addReturnType($returnTypeTransfer);
        }

        foreach ($this->types->getTypes() as $type) {
            $returnTypeTransfer = new ReturnTypeTransfer();
            $returnTypeTransfer
                ->setName($type)
                ->setType($type)
                ->setIsPhpSeven(true);

            $returnTypeCollectionTransfer->addReturnType($returnTypeTransfer);
        }

        $optionsTransfer->setReturnTypeCollection($returnTypeCollectionTransfer);
        $moduleTransfer->setOptions($optionsTransfer);

        return $moduleTransfer;
    }
}
