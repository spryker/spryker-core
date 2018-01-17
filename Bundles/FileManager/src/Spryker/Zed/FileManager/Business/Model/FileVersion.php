<?php

namespace Spryker\Zed\FileManager\Business\Model;


class FileVersion
{

    const DEFAULT_VERSION_NUMBER = 1;

    /**
     * @var FileFinder
     */
    private $fileFinder;

    /**
     * FileVersion constructor.
     * @param FileFinder $fileFinder
     */
    public function __construct(FileFinder $fileFinder)
    {
        $this->fileFinder = $fileFinder;
    }

    /**
     * @param $fileId
     * @return int
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     */
    public function getNewVersionNumber(int $fileId = null)
    {
        $fileInfo = $this->fileFinder->getLatestFileInfoByFkFile($fileId);

        if ($fileInfo == null) {
            return self::DEFAULT_VERSION_NUMBER;
        }

        return $fileInfo->getVersion() + 1;
    }

    /**
     * @param $versionNumber
     * @return string
     */
    public function getNewVersionName($versionNumber)
    {
        return sprintf('v. %d', $versionNumber);
    }
}