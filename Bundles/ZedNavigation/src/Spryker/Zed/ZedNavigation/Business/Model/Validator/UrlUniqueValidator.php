<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedNavigation\Business\Model\Validator;

class UrlUniqueValidator implements UrlUniqueValidatorInterface
{
    /**
     * @var array<string>
     */
    protected $urls = [];

    /**
     * @param string $url
     *
     * @throws \Spryker\Zed\ZedNavigation\Business\Model\Validator\UrlUniqueException
     *
     * @return void
     */
    public function validate($url)
    {
        if (in_array($url, $this->urls)) {
            throw new UrlUniqueException($url);
        }
    }

    /**
     * @param string $url
     *
     * @return void
     */
    public function addUrl($url)
    {
        $this->urls[] = $url;
    }
}
