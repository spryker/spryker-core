import { CommonModule } from '@angular/common';
import { NgModule } from '@angular/core';
import { ButtonModule } from '@spryker/button';
import { HeadlineModule } from '@spryker/headline';
import { ConfirmModalModule } from '@spryker/modal';
import { MerchantRelationEditComponent } from './merchant-relation-edit.component';

@NgModule({
    imports: [CommonModule, HeadlineModule, ConfirmModalModule, ButtonModule],
    declarations: [MerchantRelationEditComponent],
    exports: [MerchantRelationEditComponent],
})
export class MerchantRelationEditModule {}
