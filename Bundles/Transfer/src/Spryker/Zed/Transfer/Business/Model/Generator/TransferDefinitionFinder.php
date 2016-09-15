<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer\Business\Model\Generator;

use Symfony\Component\Finder\Finder;

class TransferDefinitionFinder implements FinderInterface
{

    const KEY_BUNDLE = 'bundle';
    const KEY_CONTAINING_BUNDLE = 'containing bundle';
    const KEY_TRANSFER = 'transfer';
    const TRANSFER_SCHEMA_SUFFIX = '.transfer.xml';

    /**
     * @var array
     */
    private $sourceDirectories;

    /**
     * @var array
     */
    private $transferDefinitions = [];

    /**
     * @param array $sourceDirectories
     */
    public function __construct(array $sourceDirectories)
    {
        $this->sourceDirectories = $sourceDirectories;
    }

    /**
     * @return \Symfony\Component\Finder\Finder|\Symfony\Component\Finder\SplFileInfo[]
     */
    public function getXmlTransferDefinitionFiles()
    {
        $finder = new Finder();
        $finder->in($this->sourceDirectories)->name('*.transfer.xml')->depth('< 1');

        return $finder;
    }

}
