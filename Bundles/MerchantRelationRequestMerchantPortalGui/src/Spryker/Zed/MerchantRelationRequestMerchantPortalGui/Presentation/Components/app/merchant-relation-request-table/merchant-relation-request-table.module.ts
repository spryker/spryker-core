import { CommonModule } from '@angular/common';
import { NgModule } from '@angular/core';
import { TableModule } from '@spryker/table';
import { MerchantRelationRequestTableComponent } from './merchant-relation-request-table.component';

@NgModule({
    imports: [CommonModule, TableModule],
    declarations: [MerchantRelationRequestTableComponent],
    exports: [MerchantRelationRequestTableComponent],
})
export class MerchantRelationRequestTableModule {}
