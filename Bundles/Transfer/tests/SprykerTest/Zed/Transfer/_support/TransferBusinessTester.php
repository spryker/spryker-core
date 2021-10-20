<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Transfer;

use Codeception\Actor;
use Codeception\Stub;
use ReflectionClass;
use Spryker\Shared\Kernel\Transfer\AbstractEntityTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Zed\Transfer\Business\DataBuilder\Definition\DataBuilderDefinitionFinder;
use Spryker\Zed\Transfer\Business\EntityTransfer\Definition\EntityTransferDefinitionFinder;
use Spryker\Zed\Transfer\Business\Model\Generator\FinderInterface;
use Spryker\Zed\Transfer\Business\Transfer\Definition\TransferDefinitionFinder;
use Spryker\Zed\Transfer\Business\TransferBusinessFactory;
use Spryker\Zed\Transfer\Dependency\Facade\TransferToPropelFacadeBridge;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 *
 * @method \Spryker\Zed\Transfer\Business\TransferBusinessFactory getFactory()
 * @method \Spryker\Zed\Transfer\TransferConfig getModuleConfig()
 */
class TransferBusinessTester extends Actor
{
    use _generated\TransferBusinessTesterActions {
        getFacade as getTransferFacade;
    }

    /**
     * @var string
     */
    protected const TRANSFER_DESTINATION_DIR = 'Transfers';

    /**
     * @return \Spryker\Zed\Transfer\Business\Model\Generator\FinderInterface
     */
    public function createTransferDefinitionFinder(): FinderInterface
    {
        $transferDirectory = $this->getVirtualDirectory(['transfer' => ['foo.transfer.xml' => 'content']]);

        $this->mockConfigMethod('getSourceDirectories', function () use ($transferDirectory) {
            return [$transferDirectory . '/transfer'];
        });

        return new TransferDefinitionFinder(
            $this->getModuleConfig(),
            $this->getFactory()->getUtilGlobService(),
        );
    }

    /**
     * @return \Spryker\Zed\Transfer\Business\Model\Generator\FinderInterface
     */
    public function createEntityTransferDefinitionFinder(): FinderInterface
    {
        $entityTransferDirectory = $this->getVirtualDirectory(['entity-transfer' => ['foo.schema.xml' => 'content']]);

        $this->mockConfigMethod('getEntitiesSourceDirectories', function () use ($entityTransferDirectory) {
            return [$entityTransferDirectory . '/entity-transfer'];
        });

        $propelFacadeBridge = Stub::make(TransferToPropelFacadeBridge::class, [
            'getSchemaDirectory' => $entityTransferDirectory . '/entity-transfer',
        ]);

        return new EntityTransferDefinitionFinder(
            $this->getModuleConfig(),
            $this->getFactory()->getUtilGlobService(),
            $propelFacadeBridge,
        );
    }

    /**
     * @return \Spryker\Zed\Transfer\Business\Model\Generator\FinderInterface
     */
    public function createDataBuilderDefinitionFinder(): FinderInterface
    {
        $dataBuilderDirectory = $this->getVirtualDirectory(['data-builder' => ['foo.databuilder.xml' => 'content']]);

        $this->mockConfigMethod('getDataBuildersSourceDirectories', function () use ($dataBuilderDirectory) {
            return [$dataBuilderDirectory . '/data-builder'];
        });

        return new DataBuilderDefinitionFinder(
            $this->getModuleConfig(),
            $this->getFactory()->getUtilGlobService(),
        );
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade|\Spryker\Zed\Transfer\Business\TransferFacade
     */
    public function getFacade()
    {
        $facade = $this->getTransferFacade();
        $facade->setFactory($this->getTransferBusinessFactory());

        return $facade;
    }

    /**
     * @return string
     */
    public function getTransferDestinationDir(): string
    {
        return static::TRANSFER_DESTINATION_DIR;
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    public function isEntityTransfersExist(string $path): bool
    {
        foreach ($this->getVirtualDirectoryContents($path) as $transferFileName) {
            if ($this->isEntityTransfer($transferFileName)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    public function isDataTransfersExist(string $path): bool
    {
        foreach ($this->getVirtualDirectoryContents($path) as $transferFileName) {
            if ($this->isDataTransfer($transferFileName)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $transferFileName
     *
     * @return bool
     */
    protected function isDataTransfer(string $transferFileName): bool
    {
        return $this->isTransferExtending($transferFileName, AbstractTransfer::class);
    }

    /**
     * @param string $transferFileName
     *
     * @return bool
     */
    protected function isEntityTransfer(string $transferFileName): bool
    {
        return $this->isTransferExtending($transferFileName, AbstractEntityTransfer::class);
    }

    /**
     * @param string $transferFileName
     * @param string $expectedBaseClassName
     *
     * @return bool
     */
    protected function isTransferExtending(string $transferFileName, string $expectedBaseClassName): bool
    {
        $transferFullyQualifiedClassName = $this->buildTransferClassName($transferFileName);

        if (!$transferFullyQualifiedClassName) {
            return false;
        }

        $transferReflection = new ReflectionClass($transferFullyQualifiedClassName);
        $parentClassName = $transferReflection->getParentClass()
            ? $transferReflection->getParentClass()->getName()
            : null;

        return $parentClassName === $expectedBaseClassName;
    }

    /**
     * @param string $transferFileName
     *
     * @return string|null
     */
    protected function buildTransferClassName(string $transferFileName): ?string
    {
        $className = pathinfo($transferFileName, PATHINFO_FILENAME);

        if (!$className || !$this->isTransferClassName($className)) {
            return null;
        }

        return sprintf('Generated\Shared\Transfer\%s', $className);
    }

    /**
     * @param string $className
     *
     * @return bool
     */
    protected function isTransferClassName(string $className): bool
    {
        return (substr($className, -8) === 'Transfer');
    }

    /**
     * @return string
     */
    protected function getTransferDestinationUrl(): string
    {
        return $this->getVirtualDirectory() . $this->getTransferDestinationDir() . DIRECTORY_SEPARATOR;
    }

    /**
     * @return \Spryker\Zed\Transfer\Business\TransferBusinessFactory
     */
    protected function getTransferBusinessFactory(): TransferBusinessFactory
    {
        $this->mockConfigMethod('getClassTargetDirectory', function () {
            return $this->getTransferDestinationUrl();
        });

        $factory = new TransferBusinessFactory();
        $factory->setConfig($this->getModuleConfig('Transfer'));

        return $factory;
    }
}
