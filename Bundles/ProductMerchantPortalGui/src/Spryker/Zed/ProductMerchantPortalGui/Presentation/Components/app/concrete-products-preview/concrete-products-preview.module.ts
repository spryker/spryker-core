import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ScrollingModule } from '@angular/cdk/scrolling';
import { ChipsModule } from '@spryker/chips';
import { CheckboxModule } from '@spryker/checkbox';
import { InputModule } from '@spryker/input';
import { ConcreteProductsPreviewComponent } from './concrete-products-preview.component';

@NgModule({
    imports: [CommonModule, ChipsModule, CheckboxModule, InputModule, ScrollingModule],
    declarations: [ConcreteProductsPreviewComponent],
    exports: [ConcreteProductsPreviewComponent],
})
export class ConcreteProductsPreviewModule {}
