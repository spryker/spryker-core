<?php

/**
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace Spryker\Shared\EventJournal\Model\Writer;

abstract class AbstractWriter implements WriterInterface
{

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->options = $options;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return static::TYPE;
    }

}
