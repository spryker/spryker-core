import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { TableModule } from '@spryker/table';
import { UnsavedChangesFormMonitorModule } from '@spryker/unsaved-changes.monitor.form';
import { OfferPricesTableComponent } from './offer-prices-table.component';

@NgModule({
    imports: [CommonModule, TableModule, UnsavedChangesFormMonitorModule],
    declarations: [OfferPricesTableComponent],
    exports: [OfferPricesTableComponent],
})
export class OfferPricesTableModule {}
