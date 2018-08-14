<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\FileManager;

use Codeception\Actor;
use Codeception\Configuration;
use Orm\Zed\FileManager\Persistence\SpyFile;
use Orm\Zed\FileManager\Persistence\SpyFileDirectory;
use Orm\Zed\FileManager\Persistence\SpyFileDirectoryQuery;
use Orm\Zed\FileManager\Persistence\SpyFileInfo;
use Orm\Zed\FileManager\Persistence\SpyFileQuery;
use Orm\Zed\FileManager\Persistence\SpyMimeType;
use Orm\Zed\FileManager\Persistence\SpyMimeTypeQuery;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class FileManagerBusinessTester extends Actor
{
    use _generated\FileManagerBusinessTesterActions;

    protected const PATH_DOCUMENT = 'documents/';
    protected const FILE_CONTENT = 'Spryker is awesome';
    protected const ROOT_DIRECTORY = 'fileSystemRoot/uploads/';

    /**
     * @var int
     */
    protected $idFile;

    /**
     * @var int
     */
    protected $idFirstFileInfo;

    /**
     * @var int
     */
    protected $idSecondFileInfo;

    /**
     * @var int
     */
    protected $idMimeType;

    /**
     * @var int
     */
    protected $idFirstFileDirectory;

    /**
     * @var int
     */
    protected $idSecondFileDirectory;

    /**
     * @var int
     */
    protected $idSubFileDirectory;

    /**
     * @return void
     */
    public function insertDbRecords()
    {
        $this->resetDb();

        $file = $this->insertFile();
        $this->insertFileInfos($file);
        $this->insertFileDirectories();
        $this->insertMimeType();
    }

    /**
     * @return void
     */
    public function resetDb()
    {
        SpyFileQuery::create()->deleteAll();
        SpyMimeTypeQuery::create()->deleteAll();
        SpyFileDirectoryQuery::create()->deleteAll();
    }

    /**
     * @param string $fileName
     *
     * @return string
     */
    public function getDocumentFullFileName($fileName)
    {
        return Configuration::dataDir() . static::ROOT_DIRECTORY . static::PATH_DOCUMENT . $fileName;
    }

    /**
     * @return void
     */
    public function clearFiles()
    {
        exec('rm -rf ' . $this->getDocumentFullFileName('*'));
    }

    /**
     * @return int
     */
    public function getIdFile()
    {
        return $this->idFile;
    }

    /**
     * @return int
     */
    public function getIdFirstFileInfo()
    {
        return $this->idFirstFileInfo;
    }

    /**
     * @return int
     */
    public function getIdSecondFileInfo()
    {
        return $this->idSecondFileInfo;
    }

    /**
     * @return int
     */
    public function getIdMimeType()
    {
        return $this->idMimeType;
    }

    /**
     * @return int
     */
    public function getIdFirstFileDirectory()
    {
        return $this->idFirstFileDirectory;
    }

    /**
     * @return int
     */
    public function getIdSecondFileDirectory()
    {
        return $this->idSecondFileDirectory;
    }

    /**
     * @return int
     */
    public function getIdSubFileDirectory()
    {
        return $this->idSubFileDirectory;
    }

    /**
     * @return \Orm\Zed\FileManager\Persistence\SpyFile
     */
    protected function insertFile()
    {
        $file = new SpyFile();
        $file->setFileName('customer.txt');
        $file->save();
        $file->reload();

        $this->idFile = $file->getIdFile();

        return $file;
    }

    /**
     * @return void
     */
    protected function insertMimeType()
    {
        $mimeType = new SpyMimeType();
        $mimeType->setName('text/plain');
        $mimeType->setComment('comment');
        $mimeType->setIsAllowed(true);
        $mimeType->save();

        $this->idMimeType = $mimeType->getIdMimeType();
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFile $file
     *
     * @return void
     */
    protected function insertFileInfos(SpyFile $file)
    {
        $fileInfo = new SpyFileInfo();
        $fileInfo->setFile($file);
        $fileInfo->setSize(10);
        $fileInfo->setType('text');
        $fileInfo->setVersion(1);
        $fileInfo->setVersionName('v. 1');
        $fileInfo->setStorageFileName('customer_v1.txt');
        $fileInfo->setExtension('txt');
        $fileInfo->setCreatedAt('2017-06-06 00:00:00');
        $fileInfo->setUpdatedAt('2017-06-06 00:00:00');
        $fileInfo->save();

        $this->idFirstFileInfo = $fileInfo->getIdFileInfo();

        $fileInfo = new SpyFileInfo();
        $fileInfo->setFile($file);
        $fileInfo->setSize(10);
        $fileInfo->setType('text');
        $fileInfo->setVersion(2);
        $fileInfo->setVersionName('v. 2');
        $fileInfo->setStorageFileName('customer_v2.txt');
        $fileInfo->setExtension('txt');
        $fileInfo->setCreatedAt('2017-07-07 00:00:00');
        $fileInfo->setUpdatedAt('2017-07-07 00:00:00');
        $fileInfo->save();

        $this->idSecondFileInfo = $fileInfo->getIdFileInfo();
    }

    /**
     * @return void
     */
    protected function insertFileDirectories()
    {
        $fileDirectory = new SpyFileDirectory();
        $fileDirectory->setName('first_directory');
        $fileDirectory->setPosition(1);
        $fileDirectory->setIsActive(true);
        $fileDirectory->save();

        $this->idFirstFileDirectory = $fileDirectory->getIdFileDirectory();

        $subFileDirectory = new SpyFileDirectory();
        $subFileDirectory->setName('subdirectory');
        $subFileDirectory->setIsActive(true);
        $subFileDirectory->setPosition(1);
        $subFileDirectory->setParentFileDirectory($fileDirectory);
        $subFileDirectory->save();

        $this->idSubFileDirectory = $subFileDirectory->getIdFileDirectory();

        $secondFileDirectory = new SpyFileDirectory();
        $secondFileDirectory->setName('second_directory');
        $secondFileDirectory->setPosition(2);
        $secondFileDirectory->setIsActive(true);
        $secondFileDirectory->save();

        $this->idSecondFileDirectory = $secondFileDirectory->getIdFileDirectory();
    }
}
