<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfile\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantProfile\Business\MerchantProfile\MerchantProfileReader;
use Spryker\Zed\MerchantProfile\Business\MerchantProfile\MerchantProfileReaderInterface;
use Spryker\Zed\MerchantProfile\Business\MerchantProfile\MerchantProfileWriter;
use Spryker\Zed\MerchantProfile\Business\MerchantProfile\MerchantProfileWriterInterface;
use Spryker\Zed\MerchantProfile\Business\MerchantProfileGlossary\MerchantProfileGlossaryWriter;
use Spryker\Zed\MerchantProfile\Business\MerchantProfileGlossary\MerchantProfileGlossaryWriterInterface;
use Spryker\Zed\MerchantProfile\Dependency\Facade\MerchantProfileToGlossaryFacadeInterface;
use Spryker\Zed\MerchantProfile\Dependency\Facade\MerchantProfileToLocaleFacadeInterface;
use Spryker\Zed\MerchantProfile\MerchantProfileDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantProfile\Persistence\MerchantProfileRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantProfile\Persistence\MerchantProfileEntityManagerInterface getEntityManager()
 */
class MerchantProfileBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantProfile\Business\MerchantProfile\MerchantProfileWriterInterface
     */
    public function createMerchantProfileWriter(): MerchantProfileWriterInterface
    {
        return new MerchantProfileWriter(
            $this->getEntityManager(),
            $this->createMerchantProfileGlossaryWriter()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantProfile\Business\MerchantProfile\MerchantProfileReaderInterface
     */
    public function createMerchantProfileReader(): MerchantProfileReaderInterface
    {
        return new MerchantProfileReader(
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantProfile\Business\MerchantProfileGlossary\MerchantProfileGlossaryWriterInterface
     */
    protected function createMerchantProfileGlossaryWriter(): MerchantProfileGlossaryWriterInterface
    {
        return new MerchantProfileGlossaryWriter(
            $this->getGlossaryFacade(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantProfile\Dependency\Facade\MerchantProfileToGlossaryFacadeInterface
     */
    protected function getGlossaryFacade(): MerchantProfileToGlossaryFacadeInterface
    {
        return $this->getProvidedDependency(MerchantProfileDependencyProvider::FACADE_GLOSSARY);
    }

    /**
     * @return \Spryker\Zed\MerchantProfile\Dependency\Facade\MerchantProfileToLocaleFacadeInterface
     */
    protected function getLocaleFacade(): MerchantProfileToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(MerchantProfileDependencyProvider::FACADE_LOCALE);
    }
}
