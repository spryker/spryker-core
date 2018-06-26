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
use Orm\Zed\FileManager\Persistence\SpyFileInfo;
use Orm\Zed\FileManager\Persistence\SpyMimeType;
use Propel\Runtime\Propel;

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
        Propel::getConnection()->exec('TRUNCATE TABLE spy_file CASCADE;');
        Propel::getConnection()->exec('TRUNCATE TABLE spy_mime_type CASCADE;');
        Propel::getConnection()->exec('TRUNCATE TABLE spy_file_directory CASCADE;');
        Propel::getConnection()->exec('ALTER SEQUENCE spy_file_pk_seq RESTART WITH 1;');
        Propel::getConnection()->exec('ALTER SEQUENCE spy_mime_type_pk_seq RESTART WITH 1;');
        Propel::getConnection()->exec('ALTER SEQUENCE spy_file_info_pk_seq RESTART WITH 1;');
        Propel::getConnection()->exec('ALTER SEQUENCE spy_file_directory_pk_seq RESTART WITH 1;');
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
     * @return \Orm\Zed\FileManager\Persistence\SpyFile
     */
    protected function insertFile()
    {
        $file = new SpyFile();
        $file->setFileName('customer.txt');
        $file->save();
        $file->reload();

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

        $fileDirectory2 = new SpyFileDirectory();
        $fileDirectory2->setName('second_directory');
        $fileDirectory2->setPosition(2);
        $fileDirectory2->setIsActive(true);
        $fileDirectory2->save();

        $fileSubDirectory = new SpyFileDirectory();
        $fileSubDirectory->setName('subdirectory');
        $fileSubDirectory->setIsActive(true);
        $fileSubDirectory->setPosition(1);
        $fileSubDirectory->setParentFileDirectory($fileDirectory);
        $fileSubDirectory->save();
    }
}
