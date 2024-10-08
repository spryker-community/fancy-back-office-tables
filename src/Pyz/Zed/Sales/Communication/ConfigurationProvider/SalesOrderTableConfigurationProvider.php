<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Pyz\Zed\Sales\Communication\ConfigurationProvider;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface;
use Spryker\Shared\GuiTable\GuiTableFactoryInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface;

class SalesOrderTableConfigurationProvider
{
    /**
     * @var string
     */
    public const COL_KEY_ORDER_REFERENCE = 'order_reference';

    /**
     * @var string
     */
    public const COL_KEY_CREATED_AT = 'created_at';

    /**
     * @var string
     */
    public const COL_KEY_FIRST_NAME = 'first_name';

    /**
     * @var string
     */
    public const COL_KEY_LAST_NAME = 'last_name';

    /**
     * @var string
     */
    protected const SEARCH_PLACEHOLDER = 'Search by Order Reference';

    /**
     * @uses \Pyz\Zed\Sales\Communication\Controller\IndexController::tableDataAction()
     *
     * @var string
     */
    protected const DATA_URL = '/sales/index/table-data';

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface
     */
    protected ProductOfferMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade;

    /**
     * @var \Spryker\Shared\GuiTable\GuiTableFactoryInterface
     */
    protected GuiTableFactoryInterface $guiTableFactory;

    /**
     * @var array<\Spryker\Zed\ProductOfferMerchantPortalGuiExtension\Dependency\Plugin\ProductTableExpanderPluginInterface>
     */
    protected array $productTableExpanderPlugins;

    /**
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade
     * @param \Spryker\Shared\GuiTable\GuiTableFactoryInterface $guiTableFactory
     * @param array<\Spryker\Zed\ProductOfferMerchantPortalGuiExtension\Dependency\Plugin\ProductTableExpanderPluginInterface> $productTableExpanderPlugins
     */
    public function __construct(
        GuiTableFactoryInterface $guiTableFactory,
    ) {
        $this->guiTableFactory = $guiTableFactory;
    }

    /**
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    public function getConfiguration(): GuiTableConfigurationTransfer
    {
        $guiTableConfigurationBuilder = $this->guiTableFactory->createConfigurationBuilder();

        $guiTableConfigurationBuilder = $this->addColumns($guiTableConfigurationBuilder);
//        $guiTableConfigurationBuilder = $this->addFilters($guiTableConfigurationBuilder);
        $guiTableConfigurationBuilder = $this->addRowActions($guiTableConfigurationBuilder);

        $guiTableConfigurationBuilder
            ->setTableTitle('List of Orders')
            ->setDataSourceUrl(static::DATA_URL)
            ->setSearchPlaceholder(static::SEARCH_PLACEHOLDER);

        $guiTableConfigurationTransfer = $guiTableConfigurationBuilder->createConfiguration();
//        $guiTableConfigurationTransfer = $this->executeProductTableExpanderPlugins($guiTableConfigurationTransfer);

        return $guiTableConfigurationTransfer;
    }

    /**
     * @param \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
     *
     * @return \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface
     */
    protected function addColumns(GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder): GuiTableConfigurationBuilderInterface
    {
        $guiTableConfigurationBuilder
            ->addColumnText(static::COL_KEY_ORDER_REFERENCE, 'Order Reference', true, false)
            ->addColumnText(static::COL_KEY_FIRST_NAME, 'First Name', true, true)
            ->addColumnText(static::COL_KEY_LAST_NAME, 'Last Name', true, true)
            ->addColumnText(static::COL_KEY_CREATED_AT, 'Created At', false, true)
        ;

        return $guiTableConfigurationBuilder;
    }

    /**
     * @param \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
     *
     * @return \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface
     */
    protected function addFilters(GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder): GuiTableConfigurationBuilderInterface
    {
        $guiTableConfigurationBuilder->addFilterSelect('hasOffers', 'Offers', false, [
            '1' => 'With Offers',
            '0' => 'Without Offers',
        ])
            ->addFilterSelect('isActive', 'Status', false, [
                '1' => static::COLUMN_DATA_STATUS_ACTIVE,
                '0' => static::COLUMN_DATA_STATUS_INACTIVE,
            ]);

        return $guiTableConfigurationBuilder;
    }

    /**
     * @param \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
     *
     * @return \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface
     */
    protected function addRowActions(GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder): GuiTableConfigurationBuilderInterface
    {
        $guiTableConfigurationBuilder->addRowActionDrawerAjaxForm(
            'create-offer',
            'Create Offer',
            sprintf(
                '/product-offer-merchant-portal-gui/create-product-offer?product-id=${row.%s}',
                ProductConcreteTransfer::ID_PRODUCT_CONCRETE,
            ),
        )->setRowClickAction('create-offer');

        return $guiTableConfigurationBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    protected function executeProductTableExpanderPlugins(
        GuiTableConfigurationTransfer $guiTableConfigurationTransfer
    ): GuiTableConfigurationTransfer {
        foreach ($this->productTableExpanderPlugins as $productTableExpanderPlugin) {
            $guiTableConfigurationTransfer = $productTableExpanderPlugin->expandConfiguration($guiTableConfigurationTransfer);
        }

        return $guiTableConfigurationTransfer;
    }
}
