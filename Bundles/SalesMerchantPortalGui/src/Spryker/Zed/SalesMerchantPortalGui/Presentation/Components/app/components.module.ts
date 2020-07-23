import { NgModule } from '@angular/core';
import { CustomElementModule } from '@spryker/web-components';
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

@NgModule({
    imports: [
        OfferOrdersModule,
        ButtonAjaxModule,
        ChipsModule,
        CardModule,
        TabsModule,
        CollapsibleModule,
        ManageOrderModule,
    ],
})
export class ComponentsModule extends CustomElementModule {
    protected components = [
        {
            selector: 'mp-offer-orders',
            component: OfferOrdersComponent,
        },
        {
            selector: 'mp-manage-order',
            component: ManageOrderComponent,
        },
        {
            selector: 'mp-manage-order-stats-block',
            component: ManageOrderStatsBlockComponent,
        },
        {
            selector: 'spy-ajax-button',
            component: ButtonAjaxComponent,
        },
        {
            selector: 'spy-chips',
            component: ChipsComponent,
        },
        {
            selector: 'spy-card',
            component: CardComponent,
        },
        {
            selector: 'spy-tab',
            component: TabComponent,
        },
        {
            selector: 'spy-tabs',
            component: TabsComponent,
        },
        {
            selector: 'mp-manage-order-totals',
            component: ManageOrderTotalsComponent,
        },
        {
            selector: 'spy-collapsible',
            component: CollapsibleComponent,
        },
    ];
}
