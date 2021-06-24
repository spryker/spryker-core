import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { CollapsibleModule } from '@spryker/collapsible';
import { HeadlineModule } from '@spryker/headline';
import { CardModule } from '@spryker/card';
import { EditOfferComponent } from './edit-offer.component';
import { ImageSliderModule } from '../image-slider/image-slider.module';

@NgModule({
    imports: [CommonModule, ImageSliderModule, CollapsibleModule, HeadlineModule, CardModule],
    declarations: [EditOfferComponent],
    exports: [EditOfferComponent],
})
export class EditOfferModule {}
