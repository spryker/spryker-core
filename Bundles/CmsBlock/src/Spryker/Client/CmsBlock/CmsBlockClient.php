<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsBlock;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\CmsBlock\CmsBlockFactory getFactory()
 */
class CmsBlockClient extends AbstractClient implements CmsBlockClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string[] $blockNames
     * @param string $localeName
     *
     * @return array
     */
    public function findBlocksByNames($blockNames, $localeName)
    {
        return $this->getFactory()
            ->createCmsBlockFinder()
            ->getBlocksByNames($blockNames, $localeName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $options
     * @param string $localName
     *
     * @return array
     */
    public function findBlockNamesByOptions(array $options, $localName)
    {
        return $this->getFactory()
            ->createCmsBlockFinder()
            ->getBlockNamesByOptions($options, $localName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $name
     * @param string $localeName
     *
     * @return string
     */
    public function generateBlockNameKey($name, $localeName)
    {
        return $this->getFactory()
            ->createCmsBlockFinder()
            ->generateBlockNameKey($name, $localeName);
    }
}
