<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Dataset\Business\Model;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DatasetFilenameTransfer;
use Spryker\Zed\Dataset\Business\Resolver\ResolverPath;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Dataset
 * @group Business
 * @group Model
 * @group ResolverPathTest
 * Add your own group annotations below this line
 */
class ResolverPathTest extends Unit
{
    const DEFAULT_FILENAME = 'dataset';

    /**
     * @return void
     */
    public function testGetFilenameByDatasetNameWillReturnDefaultFilename()
    {
        $resolverPath = $this->createDatasetResolverPath();

        $this->assertEquals($resolverPath->getFilenameByDatasetName(null)->getFilename(), static::DEFAULT_FILENAME);
        $this->assertEquals($resolverPath->getFilenameByDatasetName('')->getFilename(), static::DEFAULT_FILENAME);
        $this->assertEquals($resolverPath->getFilenameByDatasetName('.')->getFilename(), static::DEFAULT_FILENAME);
        $this->assertEquals($resolverPath->getFilenameByDatasetName('.!+*.')->getFilename(), static::DEFAULT_FILENAME);
        $this->assertEquals($resolverPath->getFilenameByDatasetName('%    % .. ... ?=')->getFilename(), static::DEFAULT_FILENAME);
    }

    /**
     * @return void
     */
    public function testGetFilenameByDatasetNameWillReturnValidFilename()
    {
        $resolverPath = $this->createDatasetResolverPath();

        $this->assertEquals($resolverPath->getFilenameByDatasetName(' Extra         Spaces   123 ')->getFilename(), 'Extra Spaces 123');
        $this->assertEquals($resolverPath->getFilenameByDatasetName('Bad/Good Example')->getFilename(), 'BadGood Example');
        $this->assertEquals($resolverPath->getFilenameByDatasetName('already-valid-name')->getFilename(), 'already-valid-name');
        $this->assertEquals($resolverPath->getFilenameByDatasetName('Unacceptable Symbols / %?.. .?. ! \ ')->getFilename(), 'Unacceptable Symbols');
    }

    /**
     * @param string $filename
     *
     * @return string
     */
    protected function buildDatasetFilenameTransfer($filename)
    {
        $datasetFilenameTransfer = new DatasetFilenameTransfer();
        $datasetFilenameTransfer->setFilename($filename);

        return $filename;
    }

    /**
     * @return \Spryker\Zed\Dataset\Business\Resolver\ResolverPath
     */
    protected function createDatasetResolverPath()
    {
        return new ResolverPath();
    }
}
