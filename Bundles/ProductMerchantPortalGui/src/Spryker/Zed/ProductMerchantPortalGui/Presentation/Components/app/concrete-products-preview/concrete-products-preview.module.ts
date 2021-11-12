import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ScrollingModule } from '@angular/cdk/scrolling';
import { ChipsModule } from '@spryker/chips';
import { CheckboxModule } from '@spryker/checkbox';
import { InputModule } from '@spryker/input';
import { IconModule } from '@spryker/icon';
import { FormItemModule } from '@spryker/form-item';
import { InvokeModule } from '@spryker/utils';
import { ButtonIconModule } from '@spryker/button.icon';
import { ConcreteProductsPreviewComponent } from './concrete-products-preview.component';

@NgModule({
    imports: [
        CommonModule,
        ChipsModule,
        CheckboxModule,
        InputModule,
        ScrollingModule,
        IconModule,
        FormItemModule,
        InvokeModule,
        ButtonIconModule,
    ],
    declarations: [ConcreteProductsPreviewComponent],
    exports: [ConcreteProductsPreviewComponent],
})
export class ConcreteProductsPreviewModule {}
