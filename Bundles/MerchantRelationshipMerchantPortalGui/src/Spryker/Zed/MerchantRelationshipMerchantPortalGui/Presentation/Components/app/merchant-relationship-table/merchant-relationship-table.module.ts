import { CommonModule } from '@angular/common';
import { NgModule } from '@angular/core';
import { TableModule } from '@spryker/table';
import { MerchantRelationshipTableComponent } from './merchant-relationship-table.component';

@NgModule({
    imports: [CommonModule, TableModule],
    declarations: [MerchantRelationshipTableComponent],
    exports: [MerchantRelationshipTableComponent],
})
export class MerchantRelationshipTableModule {}
