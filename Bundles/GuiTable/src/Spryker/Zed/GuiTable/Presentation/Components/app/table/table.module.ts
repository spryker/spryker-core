import { CommonModule } from '@angular/common';
import { NgModule } from '@angular/core';
import { TableModule as SprykerTableModule } from '@spryker/table';
import { TableComponent } from './table.component';

@NgModule({
    imports: [CommonModule, SprykerTableModule],
    declarations: [TableComponent],
    exports: [TableComponent],
})
export class TableModule {}
