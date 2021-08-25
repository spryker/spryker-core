import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { TableModule } from '@spryker/table';
import { UnsavedChangesFormMonitorModule } from '@spryker/unsaved-changes.monitor.form';
import { EditAbstractProductPricesComponent } from './edit-abstract-product-prices.component';

@NgModule({
    imports: [CommonModule, TableModule, UnsavedChangesFormMonitorModule],
    declarations: [EditAbstractProductPricesComponent],
    exports: [EditAbstractProductPricesComponent],
})
export class EditAbstractProductPricesModule {}
