import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { OffersListTableComponent } from './offers-list-table.component';
import { TableModule } from '@spryker/table';

@NgModule({
    imports: [CommonModule, TableModule],
    declarations: [OffersListTableComponent],
    exports: [OffersListTableComponent],
})
export class OffersListTableModule {}
