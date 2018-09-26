<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Application\Log\Processor;

use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Spryker\Shared\Log\Sanitizer\SanitizerInterface;

/**
 * @deprecated Use `EntityProcessorPlugin` from Propel module instead.
 */
class EntitySanitizerProcessor
{
    public const EXTRA = 'entity';
    public const CONTEXT_KEY = 'entity';
    public const RECORD_CONTEXT = 'context';
    public const RECORD_EXTRA = 'extra';

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
        $entity = $this->findEntity((array)$record[static::RECORD_CONTEXT]);
        if (!($entity instanceof ActiveRecordInterface)) {
            return $record;
        }

        $contextData = $entity->toArray();
        $contextData['class'] = get_class($entity);
        $sanitizedData = $this->sanitizer->sanitize($contextData);

        $record[static::RECORD_EXTRA][static::EXTRA] = $sanitizedData;

        return $record;
    }

    /**
     * @param array $context
     *
     * @return \Propel\Runtime\ActiveRecord\ActiveRecordInterface|null
     */
    protected function findEntity(array $context)
    {
        if (!empty($context[static::CONTEXT_KEY])) {
            return $context[static::CONTEXT_KEY];
        }
        if (current($context) instanceof ActiveRecordInterface) {
            return current($context);
        }

        return null;
    }
}
