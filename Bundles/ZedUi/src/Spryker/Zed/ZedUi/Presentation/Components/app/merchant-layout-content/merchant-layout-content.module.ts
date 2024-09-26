import { CommonModule } from '@angular/common';
import { NgModule } from '@angular/core';
import { HeadlineModule } from '@spryker/headline';
import { MerchantLayoutContentComponent } from './merchant-layout-content.component';

@NgModule({
    imports: [CommonModule, HeadlineModule],
    declarations: [MerchantLayoutContentComponent],
    exports: [MerchantLayoutContentComponent],
})
export class MerchantLayoutContentModule {}
