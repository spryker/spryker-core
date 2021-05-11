import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { EditAbstractProductVariantsComponent } from './edit-abstract-product-variants.component';
import { TableModule } from '@spryker/table';

@NgModule({
    imports: [CommonModule, TableModule],
    declarations: [EditAbstractProductVariantsComponent],
    exports: [EditAbstractProductVariantsComponent],
})
export class EditAbstractProductVariantsModule {}
