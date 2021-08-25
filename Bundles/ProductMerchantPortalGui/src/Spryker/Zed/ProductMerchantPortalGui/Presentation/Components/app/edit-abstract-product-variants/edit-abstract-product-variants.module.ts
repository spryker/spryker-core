import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { EditAbstractProductVariantsComponent } from './edit-abstract-product-variants.component';
import { CardModule } from '@spryker/card';
import { TableModule } from '@spryker/table';
import { UnsavedChangesFormMonitorModule } from '@spryker/unsaved-changes.monitor.form';

@NgModule({
    imports: [CommonModule, CardModule, TableModule, UnsavedChangesFormMonitorModule],
    declarations: [EditAbstractProductVariantsComponent],
    exports: [EditAbstractProductVariantsComponent],
})
export class EditAbstractProductVariantsModule {}
