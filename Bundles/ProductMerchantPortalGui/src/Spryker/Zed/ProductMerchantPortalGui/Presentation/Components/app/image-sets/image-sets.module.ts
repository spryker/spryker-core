import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ImageSetsComponent } from './image-sets.component';
import { ButtonModule } from '@spryker/button';
import { ButtonIconModule } from '@spryker/button.icon';
import { IconModule } from '@spryker/icon';
import { IconPlusModule } from '@spryker/icon/icons';
import { FormItemModule } from '@spryker/form-item';
import { InputModule } from '@spryker/input';
import { IconDeleteModule } from '../../icons';

@NgModule({
    imports: [
        CommonModule,
        ButtonModule,
        ButtonIconModule,
        IconModule,
        IconPlusModule,
        IconDeleteModule,
        FormItemModule,
        InputModule,
    ],
    declarations: [ImageSetsComponent],
    exports: [ImageSetsComponent],
})
export class ImageSetsModule {}
