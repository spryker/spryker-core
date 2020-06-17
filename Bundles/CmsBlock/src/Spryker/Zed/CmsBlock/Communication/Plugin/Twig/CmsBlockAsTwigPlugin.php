<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlock\Communication\Plugin\Twig;

use Spryker\Zed\Twig\Communication\Plugin\AbstractTwigExtensionPlugin;

/**
 * @method \Spryker\Zed\CmsBlock\Persistence\CmsBlockRepositoryInterface getRepository()
 * @method \Spryker\Zed\CmsBlock\Communication\CmsBlockCommunicationFactory getFactory()
 */
class CmsBlockAsTwigPlugin extends AbstractTwigExtensionPlugin
{
    protected const FUNCTION_NAME = 'renderCmsBlockAsTwig';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array
     */
    public function getFunctions(): array
    {
        return [
            $this->getFactory()->createRenderCmsBlockAsTwigFunction(),
        ];
    }
}
