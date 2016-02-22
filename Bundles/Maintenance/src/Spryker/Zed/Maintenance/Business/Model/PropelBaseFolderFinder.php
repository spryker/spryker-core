<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Maintenance\Business\Model;

use Symfony\Component\Finder\Finder;

/**
 * @deprecated Bundles do not contain base or map directories of propel anymore
 */
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
            ->sortByName();

        $result = [];
        foreach ($iterator as $folder) {
            $result[] = $folder->getRealpath();
        }

        return $result;
    }

}
