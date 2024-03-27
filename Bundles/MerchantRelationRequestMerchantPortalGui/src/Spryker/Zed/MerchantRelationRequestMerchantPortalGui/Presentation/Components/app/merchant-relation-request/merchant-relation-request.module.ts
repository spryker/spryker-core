import { CommonModule } from '@angular/common';
import { NgModule } from '@angular/core';
import { HeadlineModule } from '@spryker/headline';

import { MerchantRelationRequestTableModule } from '../merchant-relation-request-table/merchant-relation-request-table.module';
import { MerchantRelationRequestComponent } from './merchant-relation-request.component';

@NgModule({
    imports: [CommonModule, MerchantRelationRequestTableModule, HeadlineModule],
    declarations: [MerchantRelationRequestComponent],
    exports: [MerchantRelationRequestComponent],
})
export class MerchantRelationRequestModule {}
