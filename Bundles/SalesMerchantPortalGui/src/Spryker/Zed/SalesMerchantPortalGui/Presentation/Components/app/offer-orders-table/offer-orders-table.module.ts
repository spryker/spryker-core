import { CommonModule } from '@angular/common';
import { NgModule } from '@angular/core';
import { TableModule } from '@spryker/table';

import { OfferOrdersTableComponent } from './offer-orders-table.component';

@NgModule({
    imports: [CommonModule, TableModule],
    declarations: [OfferOrdersTableComponent],
    exports: [OfferOrdersTableComponent],
})
export class OfferOrdersTableModule {}
