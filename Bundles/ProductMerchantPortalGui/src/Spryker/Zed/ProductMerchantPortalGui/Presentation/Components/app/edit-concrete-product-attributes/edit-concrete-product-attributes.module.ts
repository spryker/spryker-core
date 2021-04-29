import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { EditConcreteProductAttributesComponent } from './edit-concrete-product-attributes.component';
import { TableModule } from '@spryker/table';

@NgModule({
    imports: [CommonModule, TableModule],
    declarations: [EditConcreteProductAttributesComponent],
    exports: [EditConcreteProductAttributesComponent],
})
export class EditConcreteProductAttributesModule {}
