import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { OfferOrdersComponent } from './offer-orders.component';
import { OfferOrdersTableModule } from '../offer-orders-table/offer-orders-table.module';

@NgModule({
    imports: [
        CommonModule,
        OfferOrdersTableModule,
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
