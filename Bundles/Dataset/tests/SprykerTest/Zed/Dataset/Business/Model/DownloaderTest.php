<?php

namespace SprykerTest\Zed\Dataset\Business\Model;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DatasetFilenameTransfer;
use Spryker\Zed\Dataset\Business\Model\Downloader;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Dataset
 * @group Business
 * @group Model
 * @group DownloaderTest
 * Add your own group annotations below this line
 */
class DownloaderTest extends Unit
{
    const DEFAULT_FILENAME = 'dataset';

    /**
     * @return void
     */
    public function testGetFilenameByDatasetNameWillReturnDefaultFilename()
    {
        $downloader = $this->createDatasetDownloader();

        $this->assertEquals($downloader->getFilenameByDatasetName(null)->getFilename(), static::DEFAULT_FILENAME);
        $this->assertEquals($downloader->getFilenameByDatasetName('')->getFilename(), static::DEFAULT_FILENAME);
        $this->assertEquals($downloader->getFilenameByDatasetName('.')->getFilename(), static::DEFAULT_FILENAME);
        $this->assertEquals($downloader->getFilenameByDatasetName('.!+*.')->getFilename(), static::DEFAULT_FILENAME);
        $this->assertEquals($downloader->getFilenameByDatasetName('%    % .. ... ?=')->getFilename(), static::DEFAULT_FILENAME);
    }

    /**
     * @return void
     */
    public function testGetFilenameByDatasetNameWillReturnValidFilename()
    {
        $downloader = $this->createDatasetDownloader();

        $this->assertEquals($downloader->getFilenameByDatasetName(' Extra         Spaces   123 ')->getFilename(), 'Extra Spaces 123');
        $this->assertEquals($downloader->getFilenameByDatasetName('Bad/Good Example')->getFilename(), 'BadGood Example');
        $this->assertEquals($downloader->getFilenameByDatasetName('already-valid-name')->getFilename(), 'already-valid-name');
        $this->assertEquals($downloader->getFilenameByDatasetName('Unacceptable Symbols / %?.. .?. ! \ ')->getFilename(), 'Unacceptable Symbols');
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
     * @return \Spryker\Zed\Dataset\Business\Model\Downloader
     */
    protected function createDatasetDownloader()
    {
        return new Downloader();
    }
}
