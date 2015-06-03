<?php

namespace SprykerEngine\Zed\Propel\Business\Model;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class PropelSchemaFinder implements PropelSchemaFinderInterface
{

    /**
     * @var array
     */
    protected $pathPattern;

    /**
     * @var array
     */
    protected $fileNamePattern;

    /**
     * @param array $pathPattern
     * @param $fileNamePattern
     */
    public function __construct(array $pathPattern, $fileNamePattern)
    {
        $this->pathPattern = $pathPattern;
        $this->fileNamePattern = $fileNamePattern;
    }

    /**
     * @return Finder|SplFileInfo[]
     */
    public function getSchemaFiles()
    {
        $finder = new Finder();
        $finder
            ->in($this->pathPattern)
            ->name($this->fileNamePattern)
        ;

        return $finder;
    }
}
