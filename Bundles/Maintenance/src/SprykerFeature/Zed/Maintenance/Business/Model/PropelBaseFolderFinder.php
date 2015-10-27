<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Maintenance\Business\Model;

use Symfony\Component\Finder\Finder;

class PropelBaseFolderFinder implements PropelBaseFolderFinderInterface
{

    const NAME = 'Persistence/Propel/Base';
    const TESTS = 'tests';

    /**
     * @var string
     */
    protected $bundlePath;

    public function __construct($bundlePath)
    {
        $this->bundlePath = $bundlePath;
    }

    /**
     * @inheritDoc
     */
    public function getBaseFolders()
    {
        $finder = new Finder();

        $iterator = $finder
            ->directories()
            ->exclude(self::TESTS)
            ->path('#' . preg_quote(self::NAME, '/') . '#')
            ->in($this->bundlePath)
            ->sortByName()
        ;

        $result = [];
        foreach ($iterator as $folder) {
            $result[] = $folder->getRealpath();
        }

        return $result;
    }

}
