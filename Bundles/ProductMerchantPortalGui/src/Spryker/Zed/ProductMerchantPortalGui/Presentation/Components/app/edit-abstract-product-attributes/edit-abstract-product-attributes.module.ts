import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { EditAbstractProductAttributesComponent } from './edit-abstract-product-attributes.component';
import { TableModule } from '@spryker/table';

@NgModule({
    imports: [CommonModule, TableModule],
    declarations: [EditAbstractProductAttributesComponent],
    exports: [EditAbstractProductAttributesComponent],
})
export class EditAbstractProductAttributesModule {
}
