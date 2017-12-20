<?php

namespace Spryker\Zed\Cms\Communication\Plugin;

use Generated\Shared\Transfer\PageTransfer;

interface PostCmsPageActivatorPluginInterface
{
    /**
     * Specification:
     * - Runs after the CMS activator class functions (activate and deactivate)
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PageTransfer $pageTransfer
     *
     * @return mixed
     */
    public function execute(PageTransfer $pageTransfer);
}