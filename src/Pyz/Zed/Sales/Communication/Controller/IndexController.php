<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Zed\Sales\Communication\Controller;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Pyz\Zed\Sales\Communication\ConfigurationProvider\SalesOrderTableConfigurationProvider;
use Spryker\Shared\GuiTable\GuiTableFactory;
use Spryker\Shared\GuiTable\GuiTableFactoryInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ConfigurationProvider\ProductOfferGuiTableConfigurationProvider;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Controller\ProductListController;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToStoreFacadeBridge;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToStoreFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeBridge;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface;
use Spryker\Zed\Store\Business\StoreFacade;
use Spryker\Zed\Translator\Business\TranslatorFacade;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Pyz\Zed\Sales\Communication\SalesCommunicationFactory getFactory()
 */
class IndexController extends \Spryker\Zed\Sales\Communication\Controller\IndexController
{
    /**
     * @return array
     */
    public function indexAction()
    {
        return [
            'productOfferTableConfiguration' =>
                (new SalesOrderTableConfigurationProvider(
                    new GuiTableFactory(),
                ))
                ->getConfiguration(),
        ];
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function tableDataAction(Request $request): Response
    {
        return $this->getFactory()->getGuiTableHttpDataRequestExecutor()->execute(
            $request,
            $this->getFactory()->createOrderTableDataProvider(),
            $this->getFactory()->createSalesOrderTableConfigurationProvider()->getConfiguration(),
        );
    }
}
