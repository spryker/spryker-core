<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsBackendApi\Processor\Reader;

use ArrayObject;
use Generated\Shared\Transfer\GlueErrorTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\PickingListsBackendApi\Dependency\Client\PickingListsBackendApiToGlossaryStorageClientInterface;

class GlossaryReader implements GlossaryReaderInterface
{
    /**
     * @var \Spryker\Glue\PickingListsBackendApi\Dependency\Client\PickingListsBackendApiToGlossaryStorageClientInterface
     */
    protected PickingListsBackendApiToGlossaryStorageClientInterface $glossaryStorageClient;

    /**
     * @param \Spryker\Glue\PickingListsBackendApi\Dependency\Client\PickingListsBackendApiToGlossaryStorageClientInterface $glossaryStorageClient
     */
    public function __construct(
        PickingListsBackendApiToGlossaryStorageClientInterface $glossaryStorageClient
    ) {
        $this->glossaryStorageClient = $glossaryStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponseTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function translateGlueResponseTransfer(
        GlueResponseTransfer $glueResponseTransfer,
        string $localeName
    ): GlueResponseTransfer {
        $glossaryKeyCollection = $this->getGlossaryKeyCollection($glueResponseTransfer);

        $translatedGlossaryKeyCollection = $this->glossaryStorageClient
            ->translateBulk(
                $glossaryKeyCollection,
                $localeName,
                [],
            );

        return $this->translateErrorTransferCollection(
            $glueResponseTransfer,
            $translatedGlossaryKeyCollection,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponseTransfer
     * @param array<string, string> $translatedGlossaryKeyCollection
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    protected function translateErrorTransferCollection(
        GlueResponseTransfer $glueResponseTransfer,
        array $translatedGlossaryKeyCollection
    ): GlueResponseTransfer {
        $glueErrorTransferCollection = $glueResponseTransfer->getErrors();
        $glueResponseTransfer->setErrors(new ArrayObject());

        foreach ($glueErrorTransferCollection as $glueErrorTransfer) {
            $glueErrorTransfer = $this->translateGlueErrorTransfer(
                $glueErrorTransfer,
                $translatedGlossaryKeyCollection,
            );

            $glueResponseTransfer->addError($glueErrorTransfer);
        }

        return $glueResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueErrorTransfer $glueErrorTransfer
     * @param array<string, string> $translatedGlossaryKeyCollection
     *
     * @return \Generated\Shared\Transfer\GlueErrorTransfer
     */
    protected function translateGlueErrorTransfer(
        GlueErrorTransfer $glueErrorTransfer,
        array $translatedGlossaryKeyCollection
    ): GlueErrorTransfer {
        $glossaryKey = $glueErrorTransfer->getMessage();
        if (!$glossaryKey || !isset($translatedGlossaryKeyCollection[$glossaryKey])) {
            return $glueErrorTransfer;
        }

        return $glueErrorTransfer->setMessage($translatedGlossaryKeyCollection[$glossaryKey]);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponseTransfer
     *
     * @return list<string>
     */
    protected function getGlossaryKeyCollection(GlueResponseTransfer $glueResponseTransfer): array
    {
        $glossaryKeyCollection = [];
        foreach ($glueResponseTransfer->getErrors() as $errorTransfer) {
            $glossaryKey = $errorTransfer->getMessage();
            if (!$glossaryKey) {
                continue;
            }

            $glossaryKeyCollection[] = $glossaryKey;
        }

        return array_values(array_unique($glossaryKeyCollection));
    }
}
