import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { SelectModule } from '@spryker/select';
import { ButtonModule } from '@spryker/button';
import { IconModule } from '@spryker/icon';
import { InputModule } from '@spryker/input';
import { ProductAttributesSelectorComponent } from './product-attributes-selector.component';

@NgModule({
    imports: [CommonModule, SelectModule, ButtonModule, IconModule, InputModule],
    declarations: [ProductAttributesSelectorComponent],
    exports: [ProductAttributesSelectorComponent],
})
export class ProductAttributesSelectorModule {}
