<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlock\Communication;

use Spryker\Shared\Twig\Loader\FilesystemLoader;
use Spryker\Shared\Twig\Loader\FilesystemLoaderInterface;
use Spryker\Zed\CmsBlock\Communication\Twig\RenderCmsBlockAsTwigFunction;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\CmsBlock\CmsBlockConfig getConfig()
 * @method \Spryker\Zed\CmsBlock\Persistence\CmsBlockQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CmsBlock\Business\CmsBlockFacadeInterface getFacade()
 * @method \Spryker\Zed\CmsBlock\Persistence\CmsBlockRepositoryInterface getRepository()
 */
class CmsBlockCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\CmsBlock\Communication\Twig\RenderCmsBlockAsTwigFunction
     */
    public function createRenderCmsBlockAsTwigFunction(): RenderCmsBlockAsTwigFunction
    {
        return new RenderCmsBlockAsTwigFunction($this->getRepository());
    }

    /**
     * @return \Spryker\Shared\Twig\Loader\FilesystemLoaderInterface
     */
    public function createTwigFilesystemLoader(): FilesystemLoaderInterface
    {
        return new FilesystemLoader($this->getConfig()->getCmsBlockTemplatePaths(), 'CmsBlock');
    }
}
