import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { CreateConcreteProductsComponent } from './create-concrete-products.component';
import { ConcreteProductAttributesSelectorModule } from '../concrete-product-attributes-selector/concrete-product-attributes-selector.module';
import { ConcreteProductsPreviewModule } from '../concrete-products-preview/concrete-products-preview.module';

@NgModule({
    imports: [CommonModule, ConcreteProductAttributesSelectorModule, ConcreteProductsPreviewModule],
    declarations: [CreateConcreteProductsComponent],
    exports: [CreateConcreteProductsComponent],
})
export class CreateConcreteProductsModule {}
