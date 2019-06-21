<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyBusinessUnit;

use ArrayObject;
use Codeception\Actor;
use Generated\Shared\DataBuilder\CompanyBusinessUnitBuilder;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 * @method \Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class CompanyBusinessUnitTester extends Actor
{
    use _generated\CompanyBusinessUnitTesterActions;

    protected const LEVEL = 'level';
    protected const CHILDREN = 'children';

   /**
    * Define custom actions here
    */

    /**
     * @return array
     */
    public static function createCompanyBusinessUnitsProvider(): array
    {
        return [
            'tree structure: null -> A -> B, C' => [
                (function (): ArrayObject {
                    $companyBusinessUnits = new ArrayObject();

                    $companyBusinessUnits->append((new CompanyBusinessUnitBuilder([CompanyBusinessUnitTransfer::ID_COMPANY_BUSINESS_UNIT => 1, CompanyBusinessUnitTransfer::NAME => 'A', CompanyBusinessUnitTransfer::FK_PARENT_COMPANY_BUSINESS_UNIT => null]))->build());
                    $companyBusinessUnits->append((new CompanyBusinessUnitBuilder([CompanyBusinessUnitTransfer::ID_COMPANY_BUSINESS_UNIT => 2, CompanyBusinessUnitTransfer::NAME => 'B', CompanyBusinessUnitTransfer::FK_PARENT_COMPANY_BUSINESS_UNIT => 1]))->build());
                    $companyBusinessUnits->append((new CompanyBusinessUnitBuilder([CompanyBusinessUnitTransfer::ID_COMPANY_BUSINESS_UNIT => 3, CompanyBusinessUnitTransfer::NAME => 'C', CompanyBusinessUnitTransfer::FK_PARENT_COMPANY_BUSINESS_UNIT => 1]))->build());

                    return $companyBusinessUnits;
                })(),
                [
                    1 => [
                        static::LEVEL => 0,
                        static::CHILDREN => [
                            2 => [
                                static::LEVEL => 1,
                                static::CHILDREN => null,
                            ],
                            3 => [
                                static::LEVEL => 1,
                                static::CHILDREN => null,
                            ],
                        ],
                    ],
                ],
            ],
            'tree structure: null -> D -> E -> G ; D -> F' => [
                (function (): ArrayObject {
                    $companyBusinessUnits = new ArrayObject();

                    $companyBusinessUnits->append((new CompanyBusinessUnitBuilder([CompanyBusinessUnitTransfer::ID_COMPANY_BUSINESS_UNIT => 4, CompanyBusinessUnitTransfer::NAME => 'D', CompanyBusinessUnitTransfer::FK_PARENT_COMPANY_BUSINESS_UNIT => null]))->build());
                    $companyBusinessUnits->append((new CompanyBusinessUnitBuilder([CompanyBusinessUnitTransfer::ID_COMPANY_BUSINESS_UNIT => 5, CompanyBusinessUnitTransfer::NAME => 'E', CompanyBusinessUnitTransfer::FK_PARENT_COMPANY_BUSINESS_UNIT => 4]))->build());
                    $companyBusinessUnits->append((new CompanyBusinessUnitBuilder([CompanyBusinessUnitTransfer::ID_COMPANY_BUSINESS_UNIT => 6, CompanyBusinessUnitTransfer::NAME => 'G', CompanyBusinessUnitTransfer::FK_PARENT_COMPANY_BUSINESS_UNIT => 5]))->build());
                    $companyBusinessUnits->append((new CompanyBusinessUnitBuilder([CompanyBusinessUnitTransfer::ID_COMPANY_BUSINESS_UNIT => 7, CompanyBusinessUnitTransfer::NAME => 'F', CompanyBusinessUnitTransfer::FK_PARENT_COMPANY_BUSINESS_UNIT => 4]))->build());

                    return $companyBusinessUnits;
                })(),
                [
                    4 => [
                        static::LEVEL => 0,
                        static::CHILDREN => [
                            5 => [
                                static::LEVEL => 1,
                                static::CHILDREN => [
                                    6 => [
                                        static::LEVEL => 2,
                                        static::CHILDREN => null,
                                    ],
                                ],
                            ],
                            7 => [
                                static::LEVEL => 1,
                                static::CHILDREN => null,
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    public function buildCompanyBusinessUnitTransfer(array $seedData = []): CompanyBusinessUnitTransfer
    {
        $companyBusinessUnitTransfer = (new CompanyBusinessUnitBuilder($seedData))->build();
        $companyBusinessUnitTransfer->setIdCompanyBusinessUnit(null);

        return $companyBusinessUnitTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTreeNodeTransfer[]|\ArrayObject $customerCompanyBusinessUnitTreeNodes
     *
     * @return array
     */
    public function mapTreeToArray(ArrayObject $customerCompanyBusinessUnitTreeNodes): array
    {
        $companyBusinessUnitTreeNodes = [];
        foreach ($customerCompanyBusinessUnitTreeNodes as $companyBusinessUnitTreeNode) {
            $companyBusinessUnitTreeNodeArray = [];

            $companyBusinessUnitTreeNodeArray[static::LEVEL] = $companyBusinessUnitTreeNode->getLevel();

            $children = $this->mapTreeToArray($companyBusinessUnitTreeNode->getChildren());
            $idCompanyBusinessUnit = $companyBusinessUnitTreeNode->getCompanyBusinessUnit()->getIdCompanyBusinessUnit();

            $companyBusinessUnitTreeNodeArray[static::CHILDREN] = $children ?: null;
            $companyBusinessUnitTreeNodes[$idCompanyBusinessUnit] = $companyBusinessUnitTreeNodeArray;
        }

        return $companyBusinessUnitTreeNodes;
    }
}
