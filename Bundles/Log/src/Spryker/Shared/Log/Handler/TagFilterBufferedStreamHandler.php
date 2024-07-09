<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Log\Handler;

use Monolog\Handler\HandlerInterface;
use Monolog\Handler\HandlerWrapper;
use Spryker\Shared\Log\Exception\InvalidLogRecordTagsTypeException;

class TagFilterBufferedStreamHandler extends HandlerWrapper
{
    /**
     * @var string
     */
    protected const RECORD_KEY_CONTEXT = 'context';

    /**
     * @var string
     */
    protected const RECORD_KEY_CONTEXT_TAGS = 'tags';

    /**
     * @var list<string>
     */
    protected array $tagDisallowList;

    /**
     * @param \Monolog\Handler\HandlerInterface $handler
     * @param list<string> $tagDisallowList
     */
    public function __construct(HandlerInterface $handler, array $tagDisallowList)
    {
        $this->tagDisallowList = $tagDisallowList;

        parent::__construct($handler);
    }

    /**
     * @param array<mixed> $record
     *
     * @return bool
     */
    public function handle(array $record): bool
    {
        if ($this->isRecordDisallowedByTags($record)) {
            return false;
        }

        return $this->handler->handle($record);
    }

    /**
     * @param array<mixed> $record
     *
     * @return bool
     */
    protected function isRecordDisallowedByTags(array $record): bool
    {
        if (!isset($record[static::RECORD_KEY_CONTEXT][static::RECORD_KEY_CONTEXT_TAGS])) {
            return false;
        }

        $this->assertRecordTagsType($record);

        if (array_intersect($record[static::RECORD_KEY_CONTEXT][static::RECORD_KEY_CONTEXT_TAGS], $this->tagDisallowList)) {
            return true;
        }

        return false;
    }

    /**
     * @param array<mixed> $record
     *
     * @throws \Spryker\Shared\Log\Exception\InvalidLogRecordTagsTypeException
     *
     * @return void
     */
    protected function assertRecordTagsType(array $record): void
    {
        if (!is_array($record[static::RECORD_KEY_CONTEXT][static::RECORD_KEY_CONTEXT_TAGS])) {
            throw new InvalidLogRecordTagsTypeException(
                sprintf('Invalid log record context: The value of the "%s" key must be an array.', static::RECORD_KEY_CONTEXT_TAGS),
            );
        }
    }
}
