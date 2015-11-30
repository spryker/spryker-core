<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Propel\Business\Model;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class PropelSchemaFinder implements PropelSchemaFinderInterface
{

    const FILE_NAME_PATTERN = '*_*.schema.xml';

    /**
     * @var array
     */
    protected $pathPatterns;

    /**
     * @param array $pathPatterns
     */
    public function __construct(array $pathPatterns)
    {
        $this->pathPatterns = $pathPatterns;
    }

    /**
     * @return Finder|SplFileInfo[]
     */
    public function getSchemaFiles()
    {
        $finder = new Finder();
        $finder
            ->in($this->pathPatterns)
            ->name(self::FILE_NAME_PATTERN)
            ->depth(0);

        return $finder;
    }

}
