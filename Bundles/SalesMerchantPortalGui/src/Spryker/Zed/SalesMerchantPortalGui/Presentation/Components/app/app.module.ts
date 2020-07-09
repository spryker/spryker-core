import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { CustomElementModule } from '@spryker/web-components';
import { LocaleModule } from '@spryker/locale';
import { EN_LOCALE, EnLocaleModule } from '@spryker/locale/locales/en';
import { DeLocaleModule } from '@spryker/locale/locales/de';
import { HttpClientModule } from '@angular/common/http';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { ButtonComponent, ButtonModule } from '@spryker/button';
import { ChipsComponent, ChipsModule } from '@spryker/chips';
import { CardModule, CardComponent } from '@spryker/card';
import { TabsModule, TabsComponent, TabComponent } from '@spryker/tabs';

import { OfferOrdersComponent } from './offer-orders/offer-orders.component';
import { OfferOrdersModule } from './offer-orders/offer-orders.module';
import { ManageOrderComponent } from './manage-order/manage-order.component';
import { ManageOrderStatsBlockComponent } from './manage-order-stats-block/manage-order-stats-block.component';
import { ManageOrderModule } from './manage-order/manage-order.module';

@NgModule({
    imports: [
        BrowserModule,
        HttpClientModule,
        BrowserAnimationsModule,
        LocaleModule.forRoot({defaultLocale: EN_LOCALE}),
        EnLocaleModule,
        DeLocaleModule,
        OfferOrdersModule,
        ManageOrderModule,
        ButtonModule,  // TODO: CHANGE BUTTON TYPE
        ChipsModule,
        CardModule,
        TabsModule,
    ],
    providers: [],
})
export class AppModule extends CustomElementModule {
    protected components = [
        {
            selector: 'web-mp-offer-orders',
            component: OfferOrdersComponent,
        },
        {
            selector: 'web-mp-manage-order',
            component: ManageOrderComponent,
        },
        {
            selector: 'web-mp-manage-order-stats-block',
            component: ManageOrderStatsBlockComponent,
        },
        {
            selector: 'web-spy-ajax-button',
            component: ButtonComponent, // TODO: CHANGE BUTTON TYPE
        },
        {
            selector: 'web-spy-chips',
            component: ChipsComponent,
        },
        {
            selector: 'web-spy-card',
            component: CardComponent,
        },
        {
            selector: 'web-spy-tab',
            component: TabComponent,
        },
        {
            selector: 'web-spy-tabs',
            component: TabsComponent,
        },
    ];
}
