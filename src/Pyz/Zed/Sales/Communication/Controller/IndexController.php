<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Zed\Sales\Communication\Controller;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
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
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Sales\Communication\SalesCommunicationFactory getFactory()
 * @method \Spryker\Zed\Sales\Business\SalesFacadeInterface getFacade()
 * @method \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Sales\Persistence\SalesRepositoryInterface getRepository()
 */
class IndexController extends \Spryker\Zed\Sales\Communication\Controller\IndexController
{
    /**
     * @return array
     */
    public function indexAction()
    {
        $table = $this->getFactory()->createOrdersTable();

        return [
            'orders' => $table->render(),

            'productOfferTableConfiguration' =>// new GuiTableConfigurationTransfer(),
                (new ProductOfferGuiTableConfigurationProvider(
                    new ProductOfferMerchantPortalGuiToStoreFacadeBridge(new StoreFacade()),
                    new ProductOfferMerchantPortalGuiToTranslatorFacadeBridge(new TranslatorFacade()),
                    new GuiTableFactory(),
                ))
                ->getConfiguration(),
        ];
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableDataAction(Request $request)
    {
        $this->getFactory()->getGuiTableHttpDataRequestExecutor()->execute(
            $request,
            $this->getFactory()->createProductTableDataProvider(),
            $this->getFactory()->createProductGuiTableConfigurationProvider()->getConfiguration(),
        );
    }
}
