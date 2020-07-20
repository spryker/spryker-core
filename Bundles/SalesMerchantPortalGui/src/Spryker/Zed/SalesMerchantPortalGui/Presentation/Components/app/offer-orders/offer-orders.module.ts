import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { HeadlineModule } from '@spryker/headline';
import { OfferOrdersComponent } from './offer-orders.component';
import { OfferOrdersTableModule } from '../offer-orders-table/offer-orders-table.module';

@NgModule({
    imports: [
        CommonModule,
        OfferOrdersTableModule,
        HeadlineModule,
    ],
    declarations: [
        OfferOrdersComponent,
    ],
    exports: [
        OfferOrdersComponent,
    ],
})
export class OfferOrdersModule {
}
