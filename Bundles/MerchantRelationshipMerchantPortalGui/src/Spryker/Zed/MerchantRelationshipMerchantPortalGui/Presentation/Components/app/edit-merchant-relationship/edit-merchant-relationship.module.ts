import { CommonModule } from '@angular/common';
import { NgModule } from '@angular/core';
import { HeadlineModule } from '@spryker/headline';
import { EditMerchantRelationshipComponent } from './edit-merchant-relationship.component';

@NgModule({
    imports: [CommonModule, HeadlineModule],
    declarations: [EditMerchantRelationshipComponent],
    exports: [EditMerchantRelationshipComponent],
})
export class EditMerchantRelationshipModule {}
