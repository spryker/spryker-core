import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { EditOfferComponent } from './edit-offer.component';
import { ImageSliderModule } from '../image-slider/image-slider.module';
import { CollapsibleModule } from '@spryker/collapsible';

@NgModule({
    imports: [CommonModule, ImageSliderModule, CollapsibleModule],
    declarations: [EditOfferComponent],
    exports: [EditOfferComponent],
})
export class EditOfferModule {
}
