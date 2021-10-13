import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { TableModule } from '@spryker/table';
import { UnsavedChangesFormMonitorModule } from '@spryker/unsaved-changes.monitor.form';
import { EditConcreteProductPricesComponent } from './edit-concrete-product-prices.component';

@NgModule({
    imports: [CommonModule, TableModule, UnsavedChangesFormMonitorModule],
    declarations: [EditConcreteProductPricesComponent],
    exports: [EditConcreteProductPricesComponent],
})
export class EditConcreteProductPricesModule {}
