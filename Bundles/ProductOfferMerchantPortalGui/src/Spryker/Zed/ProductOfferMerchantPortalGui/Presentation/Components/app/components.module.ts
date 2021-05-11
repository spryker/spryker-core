import { NgModule } from '@angular/core';
import { WebComponentsModule } from '@spryker/web-components';
import { OffersListComponent } from './offers-list/offers-list.component';
import { OffersListModule } from './offers-list/offers-list.module';
import { ProductOfferComponent } from './product-offer/product-offer.component';
import { ProductOfferModule } from './product-offer/product-offer.module';
import { OfferPricesTableComponent } from './offer-prices-table/offer-prices-table.component';
import { OfferPricesTableModule } from './offer-prices-table/offer-prices-table.module';
import { EditOfferModule } from './edit-offer/edit-offer.module';
import { EditOfferComponent } from './edit-offer/edit-offer.component';
import { ButtonLinkModule, ButtonLinkComponent } from '@spryker/button';
import { ChipsModule, ChipsComponent } from '@spryker/chips';
import { CardModule, CardComponent } from '@spryker/card';
import { DateRangePickerModule, DateRangePickerComponent } from '@spryker/date-picker';

@NgModule({
    imports: [
        WebComponentsModule.withComponents([
            OffersListComponent,
            ProductOfferComponent,
            ButtonLinkComponent,
            EditOfferComponent,
            ChipsComponent,
            CardComponent,
            DateRangePickerComponent,
            OfferPricesTableComponent,
        ]),
        ProductOfferModule,
        OffersListModule,
        ButtonLinkModule,
        CardModule,
        DateRangePickerModule,
        ChipsModule,
        EditOfferModule,
        OfferPricesTableModule,
    ],
})
export class ComponentsModule {}
