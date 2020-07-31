import { NgModule } from '@angular/core';
import { CustomElementModule, WebComponentDefs } from '@spryker/web-components';
import { ButtonAjaxComponent, ButtonAjaxModule } from '@spryker/button';
import { ChipsComponent, ChipsModule } from '@spryker/chips';
import { CardModule, CardComponent } from '@spryker/card';
import { CollapsibleComponent, CollapsibleModule } from '@spryker/collapsible';
import { TabsModule, TabsComponent, TabComponent } from '@spryker/tabs';

import { OfferOrdersComponent } from './offer-orders/offer-orders.component';
import { OfferOrdersModule } from './offer-orders/offer-orders.module';
import { ManageOrderComponent } from './manage-order/manage-order.component';
import { ManageOrderStatsBlockComponent } from './manage-order/manage-order-stats-block/manage-order-stats-block.component';
import { ManageOrderTotalsComponent } from './manage-order/manage-order-totals/manage-order-totals.component';
import { ManageOrderModule } from './manage-order/manage-order.module';
import { OrderItemsTableComponent } from './order-items-table/order-items-table.component';
import { OrderItemsTableModule } from './order-items-table/order-items-table.module';
import { ManageOrderCollapsibleTotalsComponent } from './manage-order/manage-order-collapsible-totals/manage-order-collapsible-totals.component';

@NgModule({
    imports: [
        OfferOrdersModule,
        ButtonAjaxModule,
        ChipsModule,
        CardModule,
        TabsModule,
        ManageOrderModule,
        OrderItemsTableModule,
    ],
})
export class ComponentsModule extends CustomElementModule {
    protected components: WebComponentDefs = [
        TabsComponent,
        OfferOrdersComponent,
        ManageOrderComponent,
        ManageOrderStatsBlockComponent,
        ButtonAjaxComponent,
        ChipsComponent,
        CardComponent,
        TabComponent,
        ManageOrderTotalsComponent,
        OrderItemsTableComponent,
        ManageOrderCollapsibleTotalsComponent,
    ];
}
