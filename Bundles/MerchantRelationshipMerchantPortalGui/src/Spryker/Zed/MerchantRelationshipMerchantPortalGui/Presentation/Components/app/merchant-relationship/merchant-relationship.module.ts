import { CommonModule } from '@angular/common';
import { NgModule } from '@angular/core';
import { HeadlineModule } from '@spryker/headline';

import { MerchantRelationshipTableModule } from '../merchant-relationship-table/merchant-relationship-table.module';
import { MerchantRelationshipComponent } from './merchant-relationship.component';

@NgModule({
    imports: [CommonModule, MerchantRelationshipTableModule, HeadlineModule],
    declarations: [MerchantRelationshipComponent],
    exports: [MerchantRelationshipComponent],
})
export class MerchantRelationshipModule {}
