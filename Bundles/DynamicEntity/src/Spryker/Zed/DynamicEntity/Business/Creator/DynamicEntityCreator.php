<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Creator;

use Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer;
use Spryker\Zed\DynamicEntity\Business\Exception\DynamicEntityConfigurationNotFoundException;
use Spryker\Zed\DynamicEntity\Business\Reader\DynamicEntityReaderInterface;
use Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityConfigurationTreeValidatorInterface;
use Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidatorInterface;
use Spryker\Zed\DynamicEntity\Business\Writer\DynamicEntityWriterInterface;

class DynamicEntityCreator implements DynamicEntityCreatorInterface
{
    /**
     * @var \Spryker\Zed\DynamicEntity\Business\Reader\DynamicEntityReaderInterface
     */
    protected DynamicEntityReaderInterface $dynamicEntityReader;

    /**
     * @var \Spryker\Zed\DynamicEntity\Business\Writer\DynamicEntityWriterInterface
     */
    protected DynamicEntityWriterInterface $dynamicEntityWriter;

    /**
     * @var \Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidatorInterface
     */
    protected DynamicEntityValidatorInterface $dynamicEntityValidator;

    /**
     * @var \Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityConfigurationTreeValidatorInterface
     */
    protected DynamicEntityConfigurationTreeValidatorInterface $dynamicEntityConfigurationTreeValidator;

    /**
     * @param \Spryker\Zed\DynamicEntity\Business\Reader\DynamicEntityReaderInterface $dynamicEntityReader
     * @param \Spryker\Zed\DynamicEntity\Business\Writer\DynamicEntityWriterInterface $dynamicEntityWriter
     * @param \Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidatorInterface $dynamicEntityValidator
     * @param \Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityConfigurationTreeValidatorInterface $dynamicEntityConfigurationTreeValidator
     */
    public function __construct(
        DynamicEntityReaderInterface $dynamicEntityReader,
        DynamicEntityWriterInterface $dynamicEntityWriter,
        DynamicEntityValidatorInterface $dynamicEntityValidator,
        DynamicEntityConfigurationTreeValidatorInterface $dynamicEntityConfigurationTreeValidator
    ) {
        $this->dynamicEntityReader = $dynamicEntityReader;
        $this->dynamicEntityWriter = $dynamicEntityWriter;
        $this->dynamicEntityValidator = $dynamicEntityValidator;
        $this->dynamicEntityConfigurationTreeValidator = $dynamicEntityConfigurationTreeValidator;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     *
     * @throws \Spryker\Zed\DynamicEntity\Business\Exception\DynamicEntityConfigurationNotFoundException
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    public function create(DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer): DynamicEntityCollectionResponseTransfer
    {
        $dynamicEntityConfigurationCollectionTransfer = $this->dynamicEntityReader->getDynamicEntityConfigurationByDynamicEntityCollectionRequest(
            $dynamicEntityCollectionRequestTransfer,
        );

        $errorTransfer = $this->dynamicEntityConfigurationTreeValidator
            ->validateDynamicEntityConfigurationCollectionByDynamicEntityConfigurationCollection(
                $dynamicEntityCollectionRequestTransfer,
                $dynamicEntityConfigurationCollectionTransfer,
            );

        if ($errorTransfer !== null) {
            return (new DynamicEntityCollectionResponseTransfer())
                ->addError($errorTransfer);
        }

        $dynamicEntityConfigurationTransfer = $this->dynamicEntityReader->getDynamicEntityConfigurationTransferTreeByDynamicEntityConfigurationCollection(
            $dynamicEntityCollectionRequestTransfer,
            $dynamicEntityConfigurationCollectionTransfer,
        );

        if ($dynamicEntityConfigurationTransfer === null) {
            throw new DynamicEntityConfigurationNotFoundException();
        }

        $dynamicEntityCollectionResponseTransfer = $this->dynamicEntityValidator->validate(
            $dynamicEntityCollectionRequestTransfer,
            $dynamicEntityConfigurationTransfer,
            new DynamicEntityCollectionResponseTransfer(),
        );

        if ($dynamicEntityCollectionResponseTransfer->getErrors()->count()) {
            return $dynamicEntityCollectionResponseTransfer;
        }

        return $this->dynamicEntityWriter->executeCreateTransaction($dynamicEntityCollectionRequestTransfer, $dynamicEntityConfigurationTransfer);
    }
}
