<?php

/*
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerFeature\Zed\Glossary\Business\Key;

class FileKeySource implements KeySourceInterface
{
    /**
     * @var string
     */
    protected $filePath;

    /**
     * @param string $filePath
     */
    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * @return array
     */
    public function retrieveKeyArray()
    {
        $result = include $this->filePath;
        return array_keys($result);
    }
}
