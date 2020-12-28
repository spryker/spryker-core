import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { OfferPricesTableComponent } from './offer-prices-table.component';
import { TableModule } from '@spryker/table';

@NgModule({
    imports: [CommonModule, TableModule],
    declarations: [OfferPricesTableComponent],
    exports: [OfferPricesTableComponent],
})
export class OfferPricesTableModule {
}
