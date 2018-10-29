<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Package\ExternalPackageFinder;

use Generated\Shared\Transfer\PackageFilterTransfer;
use Generated\Shared\Transfer\PackageTransfer;
use Spryker\Zed\Development\DevelopmentConfig;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Zend\Filter\FilterChain;
use Zend\Filter\Word\DashToCamelCase;

class ExternalPackageFinder implements ExternalPackageFinderInterface
{
    /**
     * @var \Spryker\Zed\Development\DevelopmentConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\Development\DevelopmentConfig $config
     */
    public function __construct(DevelopmentConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\PackageFilterTransfer|null $packageFilterTransfer
     *
     * @return \Generated\Shared\Transfer\PackageTransfer[]
     */
    public function getExternalPackages(?PackageFilterTransfer $packageFilterTransfer = null): array
    {
        $packageTransferCollection = [];

        foreach ($this->getPackageFinder() as $directoryInfo) {
            if (in_array($directoryInfo->getFilename(), ['spryker'])) {
                continue;
            }

            if (!$this->isPackage($directoryInfo->getPathname())) {
                continue;
            }

            $packageTransfer = $this->getPackageTransfer($directoryInfo);

            $packageTransferCollection = $this->addPackageToCollection(
                $packageTransfer,
                $packageTransferCollection,
                $packageFilterTransfer
            );
        }

        return $packageTransferCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\PackageTransfer $packageTransfer
     * @param \Generated\Shared\Transfer\PackageTransfer[] $packageTransferCollection
     * @param \Generated\Shared\Transfer\PackageFilterTransfer|null $packageFilterTransfer
     *
     * @return \Generated\Shared\Transfer\PackageTransfer[]
     */
    protected function addPackageToCollection(
        PackageTransfer $packageTransfer,
        array $packageTransferCollection,
        ?PackageFilterTransfer $packageFilterTransfer = null
    ): array {
        if ($packageFilterTransfer !== null && !$this->matches($packageTransfer, $packageFilterTransfer)) {
            return $packageTransferCollection;
        }

        $moduleTransferCollection[$this->buildCollectionKey($packageTransfer)] = $packageTransfer;

        return $moduleTransferCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\PackageTransfer $packageTransfer
     * @param \Generated\Shared\Transfer\PackageFilterTransfer $packageFilterTransfer
     *
     * @return bool
     */
    protected function matches(PackageTransfer $packageTransfer, PackageFilterTransfer $packageFilterTransfer): bool
    {
        if (!$this->match($packageFilterTransfer->getOrganizationName(), $packageTransfer->getOrganizationName())) {
            return false;
        }

        if (!$this->match($packageFilterTransfer->getPackageName(), $packageTransfer->getPackageName())) {
            return false;
        }

        return true;
    }

    /**
     * @param string $search
     * @param string $given
     *
     * @return bool
     */
    protected function match(string $search, string $given): bool
    {
        if ($search === $given) {
            return true;
        }

        if (mb_strpos($search, '*') !== 0) {
            $search = '^' . $search;
        }

        if (mb_strpos($search, '*') === 0) {
            $search = mb_substr($search, 1);
        }

        if (mb_substr($search, -1) !== '*') {
            $search .= '$';
        }

        if (mb_substr($search, -1) === '*') {
            $search = mb_substr($search, 0, mb_strlen($search) - 1);
        }

        return preg_match(sprintf('/%s/', $search), $given);
    }

    /**
     * @param \Generated\Shared\Transfer\PackageTransfer $packageTransfer
     *
     * @return string
     */
    protected function buildCollectionKey(PackageTransfer $packageTransfer): string
    {
        return sprintf('%s.%s', $packageTransfer->getOrganizationName(), $packageTransfer->getPackageName());
    }

    /**
     * @return \Symfony\Component\Finder\SplFileInfo[]|\Symfony\Component\Finder\Finder
     */
    protected function getPackageFinder(): Finder
    {
        return (new Finder())->directories()->depth('== 1')->in(APPLICATION_VENDOR_DIR . DIRECTORY_SEPARATOR);
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $directoryInfo
     *
     * @return \Generated\Shared\Transfer\PackageTransfer
     */
    protected function getPackageTransfer(SplFileInfo $directoryInfo): PackageTransfer
    {
        $composerJsonAsArray = $this->getComposerJsonAsArray($directoryInfo->getPathname());
        $composerName = $composerJsonAsArray['name'];
        [$organizationNameDashed, $packageNameDashed] = explode('/', $composerName);

        $organizationName = $this->camelCase($organizationNameDashed);
        $packageName = $this->camelCase($packageNameDashed);

        $packageTransfer = new PackageTransfer();
        $packageTransfer
            ->setComposerName($composerName)
            ->setOrganizationName($organizationName)
            ->setOrganizationNameDashed($organizationNameDashed)
            ->setPackageName($packageName)
            ->setPackageNameDashed($packageNameDashed)
            ->setPath($directoryInfo->getPathname());

        return $packageTransfer;
    }

    /**
     * @param string $path
     *
     * @return array
     */
    protected function getComposerJsonAsArray(string $path): array
    {
        $pathToComposerJson = sprintf('%s/composer.json', $path);
        $fileContent = file_get_contents($pathToComposerJson);
        $composerJsonAsArray = json_decode($fileContent, true);

        return $composerJsonAsArray;
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function camelCase(string $value): string
    {
        $filterChain = new FilterChain();
        $filterChain->attach(new DashToCamelCase());

        return ucfirst($filterChain->filter($value));
    }

    /**
     * Directory detection for being package root - should contains composer json file.
     *
     * @param string $path
     *
     * @return bool
     */
    protected function isPackage(string $path): bool
    {
        $pathToComposerJson = sprintf('%s/composer.json', $path);

        return is_file($pathToComposerJson);
    }
}
