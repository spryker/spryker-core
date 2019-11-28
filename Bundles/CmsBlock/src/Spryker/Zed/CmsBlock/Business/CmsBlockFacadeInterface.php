<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlock\Business;

use Generated\Shared\Transfer\CmsBlockGlossaryTransfer;
use Generated\Shared\Transfer\CmsBlockTemplateTransfer;
use Generated\Shared\Transfer\CmsBlockTransfer;

interface CmsBlockFacadeInterface
{
    /**
     * Specification:
     * - Returns an CMS Block transfer by id
     *
     * @api
     *
     * @param int $idCmsBlock
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer|null
     */
    public function findCmsBlockById(int $idCmsBlock): ?CmsBlockTransfer;

    /**
     * Specification:
     * - Changes status of the block to Active
     *
     * @api
     *
     * @param int $idCmsBlock
     *
     * @return void
     */
    public function activateById(int $idCmsBlock): void;

    /**
     * Specification:
     * - Changes status of the block to Inactive
     *
     * @api
     *
     * @param int $idCmsBlock
     *
     * @return void
     */
    public function deactivateById(int $idCmsBlock): void;

    /**
     * Specification:
     * - Updates CMS Block
     * - Asserts that chosen template still exists
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @throws \Spryker\Zed\CmsBlock\Business\Exception\CmsBlockNotFoundException
     * @throws \Spryker\Zed\CmsBlock\Business\Exception\CmsBlockTemplateNotFoundException
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer
     */
    public function updateCmsBlock(CmsBlockTransfer $cmsBlockTransfer): CmsBlockTransfer;

    /**
     * Specification:
     * - Creates a CMS Block record
     * - Asserts that chosen template still exists
     * - Sets CMS Block ID to transfer
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer
     */
    public function createCmsBlock(CmsBlockTransfer $cmsBlockTransfer): CmsBlockTransfer;

    /**
     * Specification:
     * - Loops by all template files in passed path
     * - Creates records with templates in DB
     *
     * @api
     *
     * @param string $templatePath
     *
     * @return void
     */
    public function syncTemplate(string $templatePath): void;

    /**
     * Specification:
     * - Find CMS Block glossary
     * - Hydrate glossary placeholders
     * - Hydrate placeholder translations
     *
     * @api
     *
     * @param int $idCmsBlock
     *
     * @return \Generated\Shared\Transfer\CmsBlockGlossaryTransfer
     */
    public function findGlossary(int $idCmsBlock): CmsBlockGlossaryTransfer;

    /**
     * Specification:
     * - Creates new or updates nested placeholders
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer
     *
     * @throws \Spryker\Zed\CmsBlock\Business\Exception\CmsBlockMappingAmbiguousException
     * @throws \Spryker\Zed\CmsBlock\Business\Exception\MissingCmsBlockGlossaryKeyMapping
     *
     * @return \Generated\Shared\Transfer\CmsBlockGlossaryTransfer
     */
    public function saveGlossary(CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer): CmsBlockGlossaryTransfer;

    /**
     * Specification:
     * - Validate path on existing template file
     * - Create a new template record
     *
     * @api
     *
     * @param string $name
     * @param string $path
     *
     * @throws \Spryker\Zed\CmsBlock\Business\Exception\CmsBlockTemplatePathExistsException
     *
     * @return \Generated\Shared\Transfer\CmsBlockTemplateTransfer
     */
    public function createTemplate(string $name, string $path): CmsBlockTemplateTransfer;

    /**
     * Specification:
     * - Returns template transfer if it exists
     * - Return NULL if no record in DB for template with this path
     *
     * @api
     *
     * @param string $path
     *
     * @return \Generated\Shared\Transfer\CmsBlockTemplateTransfer|null
     */
    public function findTemplate(string $path): ?CmsBlockTemplateTransfer;

    /**
     * Specification:
     * - Finds a template in DB
     * - Checks template file in template directories
     *
     * @api
     *
     * @param int $idCmsBlockTemplate
     *
     * @return bool
     */
    public function hasTemplateFileById(int $idCmsBlockTemplate): bool;
}
