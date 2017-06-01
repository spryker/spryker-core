<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business\Label;

use Generated\Shared\Transfer\ProductLabelTransfer;
use Spryker\Zed\ProductLabel\Business\Touch\LabelDictionaryTouchManagerInterface;

class ValidityUpdater implements ValidityUpdaterInterface
{

    /**
     * @var \Spryker\Zed\ProductLabel\Business\Label\LabelReaderInterface
     */
    protected $labelReader;

    /**
     * @var \Spryker\Zed\ProductLabel\Business\Label\LabelUpdaterInterface
     */
    protected $labelWriter;

    /**
     * @var \Spryker\Zed\ProductLabel\Business\Label\DateRangeValidatorInterface
     */
    protected $dateRangeValidator;

    /**
     * @var \Spryker\Zed\ProductLabel\Business\Touch\LabelDictionaryTouchManagerInterface
     */
    protected $dictionaryTouchManager;

    /**
     * @param \Spryker\Zed\ProductLabel\Business\Label\LabelReaderInterface $labelReader
     * @param \Spryker\Zed\ProductLabel\Business\Label\LabelUpdaterInterface $labelWriter
     * @param \Spryker\Zed\ProductLabel\Business\Label\DateRangeValidatorInterface $dateRangeValidator
     * @param \Spryker\Zed\ProductLabel\Business\Touch\LabelDictionaryTouchManagerInterface $dictionaryTouchManager
     */
    public function __construct(
        LabelReaderInterface $labelReader,
        LabelUpdaterInterface $labelWriter,
        DateRangeValidatorInterface $dateRangeValidator,
        LabelDictionaryTouchManagerInterface $dictionaryTouchManager
    ) {
        $this->labelReader = $labelReader;
        $this->labelWriter = $labelWriter;
        $this->dateRangeValidator = $dateRangeValidator;
        $this->dictionaryTouchManager = $dictionaryTouchManager;
    }

    /**
     * @return void
     */
    public function checkAndTouchAllLabels()
    {
        $hasValidityChange = false;

        foreach ($this->labelReader->readAll() as $productLabelTransfer) {
            if ($this->dateRangeValidator->isBecomingValid($productLabelTransfer)) {
                $this->setPublished($productLabelTransfer);
                $hasValidityChange = true;
            } elseif ($this->dateRangeValidator->isBecomingInvalid($productLabelTransfer)) {
                $this->setUnpublished($productLabelTransfer);
                $hasValidityChange = true;
            }
        }

        if ($hasValidityChange) {
            $this->dictionaryTouchManager->touchActive();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return void
     */
    protected function setPublished(ProductLabelTransfer $productLabelTransfer)
    {
        $productLabelTransfer->setIsPublished(true);
        $this->labelWriter->update($productLabelTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return void
     */
    protected function setUnpublished(ProductLabelTransfer $productLabelTransfer)
    {
        $productLabelTransfer->setIsPublished(false);
        $this->labelWriter->update($productLabelTransfer);
    }

}
