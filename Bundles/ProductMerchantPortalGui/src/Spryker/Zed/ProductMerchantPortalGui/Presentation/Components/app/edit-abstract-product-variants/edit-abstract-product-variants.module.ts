import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { EditAbstractProductVariantsComponent } from './edit-abstract-product-variants.component';
import { CardModule } from '@spryker/card';
import { TableModule } from '@spryker/table';

@NgModule({
    imports: [CommonModule, CardModule, TableModule],
    declarations: [EditAbstractProductVariantsComponent],
    exports: [EditAbstractProductVariantsComponent],
})
export class EditAbstractProductVariantsModule {}
