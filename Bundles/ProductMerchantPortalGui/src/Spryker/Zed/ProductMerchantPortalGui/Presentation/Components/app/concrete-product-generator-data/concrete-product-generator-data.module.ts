import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { CustomElementBoundaryModule } from '@spryker/web-components';
import { ConcreteProductGeneratorDataComponent } from './concrete-product-generator-data.component';

@NgModule({
    imports: [CommonModule, CustomElementBoundaryModule],
    declarations: [ConcreteProductGeneratorDataComponent],
    exports: [ConcreteProductGeneratorDataComponent],
})
export class ConcreteProductGeneratorDataModule {}
