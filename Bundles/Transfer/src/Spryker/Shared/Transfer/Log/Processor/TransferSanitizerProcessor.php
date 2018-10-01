<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Transfer\Log\Processor;

use ArrayObject;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Shared\Log\Sanitizer\SanitizerInterface;

class TransferSanitizerProcessor
{
    public const EXTRA = 'transfer';
    public const CONTEXT_KEY = 'transfer';
    public const RECORD_EXTRA = 'extra';
    public const RECORD_CONTEXT = 'context';

    /**
     * @var \Spryker\Shared\Log\Sanitizer\SanitizerInterface
     */
    protected $sanitizer;

    /**
     * @param \Spryker\Shared\Log\Sanitizer\SanitizerInterface $sanitizer
     */
    public function __construct(SanitizerInterface $sanitizer)
    {
        $this->sanitizer = $sanitizer;
    }

    /**
     * @param array $record
     *
     * @return array
     */
    public function __invoke(array $record)
    {
        $transfer = $this->findTransfer((array)$record[static::RECORD_CONTEXT]);
        if (!($transfer instanceof TransferInterface)) {
            return $record;
        }
        unset($record[static::RECORD_CONTEXT][static::CONTEXT_KEY]);

        $contextData = $this->transferToArray($transfer);
        $contextData['class'] = get_class($transfer);
        $sanitizedData = $this->sanitizer->sanitize($contextData);
        $record[static::RECORD_EXTRA][static::EXTRA] = $sanitizedData;

        return $record;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $transfer
     *
     * @return array
     */
    protected function transferToArray(TransferInterface $transfer)
    {
        $transferArray = $transfer->toArray();

        foreach ($transferArray as $key => $value) {
            if ($value instanceof ArrayObject) {
                $data[$key] = [];
            }

            if (is_array($value) && (current($value) instanceof TransferInterface)) {
                foreach ($value as $position => $transfer) {
                    $value[$position] = $this->transferToArray($transfer);
                }
            }
        }

        return $transferArray;
    }

    /**
     * @param array $context
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface|null
     */
    protected function findTransfer(array $context)
    {
        if (!empty($context[static::CONTEXT_KEY])) {
            return $context[static::CONTEXT_KEY];
        }
        if (current($context) instanceof TransferInterface) {
            return current($context);
        }

        return null;
    }
}
