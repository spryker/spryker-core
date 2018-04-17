<?php

namespace SprykerTest\Zed\Dataset\Business\Model;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DatasetFilenameTransfer;
use Spryker\Zed\Dataset\Business\Model\DownloaderManager;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Dataset
 * @group Business
 * @group Model
 * @group DownloaderManagerTest
 * Add your own group annotations below this line
 */
class DownloaderManagerTest extends Unit
{
    const DEFAULT_FILENAME = 'dataset';

    /**
     * @return void
     */
    public function testGetFilenameByDatasetNameWillReturnDefaultFilename()
    {
        $downloaderManager = $this->createDatasetDownloaderManager();

        $this->assertEquals($downloaderManager->getFilenameByDatasetName(null)->getFilename(), static::DEFAULT_FILENAME);
        $this->assertEquals($downloaderManager->getFilenameByDatasetName('')->getFilename(), static::DEFAULT_FILENAME);
        $this->assertEquals($downloaderManager->getFilenameByDatasetName('.')->getFilename(), static::DEFAULT_FILENAME);
        $this->assertEquals($downloaderManager->getFilenameByDatasetName('.!+*.')->getFilename(), static::DEFAULT_FILENAME);
        $this->assertEquals($downloaderManager->getFilenameByDatasetName('%    % .. ... ?=')->getFilename(), static::DEFAULT_FILENAME);
    }

    /**
     * @return void
     */
    public function testGetFilenameByDatasetNameWillReturnValidFilename()
    {
        $downloaderManager = $this->createDatasetDownloaderManager();

        $this->assertEquals($downloaderManager->getFilenameByDatasetName(' Extra         Spaces   123 ')->getFilename(), 'Extra Spaces 123');
        $this->assertEquals($downloaderManager->getFilenameByDatasetName('Bad/Good Example')->getFilename(), 'BadGood Example');
        $this->assertEquals($downloaderManager->getFilenameByDatasetName('already-valid-name')->getFilename(), 'already-valid-name');
        $this->assertEquals($downloaderManager->getFilenameByDatasetName('Unacceptable Symbols / %?.. .?. ! \ ')->getFilename(), 'Unacceptable Symbols');
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
     * @return \Spryker\Zed\Dataset\Business\Model\DownloaderManager
     */
    protected function createDatasetDownloaderManager()
    {
        return new DownloaderManager();
    }
}
