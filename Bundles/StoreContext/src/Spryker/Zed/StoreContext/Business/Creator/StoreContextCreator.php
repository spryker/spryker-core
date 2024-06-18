<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContext\Business\Creator;

use Generated\Shared\Transfer\StoreContextCollectionRequestTransfer;
use Generated\Shared\Transfer\StoreContextCollectionResponseTransfer;
use Spryker\Zed\StoreContext\Business\Validator\StoreContextValidatorInterface;
use Spryker\Zed\StoreContext\Business\Writer\StoreContextWriterInterface;

class StoreContextCreator implements StoreContextCreatorInterface
{
    /**
     * @var \Spryker\Zed\StoreContext\Business\Writer\StoreContextWriterInterface
     */
    protected StoreContextWriterInterface $storeContextWriter;

    /**
     * @var \Spryker\Zed\StoreContext\Business\Validator\StoreContextValidatorInterface
     */
    protected StoreContextValidatorInterface $storeContextValidator;

    /**
     * @param \Spryker\Zed\StoreContext\Business\Writer\StoreContextWriterInterface $storeContextWriter
     * @param \Spryker\Zed\StoreContext\Business\Validator\StoreContextValidatorInterface $storeContextValidator
     */
    public function __construct(
        StoreContextWriterInterface $storeContextWriter,
        StoreContextValidatorInterface $storeContextValidator
    ) {
        $this->storeContextWriter = $storeContextWriter;
        $this->storeContextValidator = $storeContextValidator;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreContextCollectionRequestTransfer $storeContextCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\StoreContextCollectionResponseTransfer
     */
    public function createStoreContextCollection(
        StoreContextCollectionRequestTransfer $storeContextCollectionRequestTransfer
    ): StoreContextCollectionResponseTransfer {
        $storeContextCollectionResponseTransfer = $this->storeContextValidator->validateStoreContextCollection($storeContextCollectionRequestTransfer);

        if (count($storeContextCollectionResponseTransfer->getErrors()) > 0) {
            return $storeContextCollectionResponseTransfer;
        }

        return $this->storeContextWriter->createStoreContextCollection($storeContextCollectionRequestTransfer);
    }
}
