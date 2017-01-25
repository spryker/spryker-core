<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Url\Generator;

interface UrlGeneratorInterface
{

    /**
     * @param string $url
     * @param array $query
     * @param array $options
     *
     * @return \Spryker\Shared\Url\UrlInterface
     */
    public function generate($url, array $query = [], array $options = []);

}
