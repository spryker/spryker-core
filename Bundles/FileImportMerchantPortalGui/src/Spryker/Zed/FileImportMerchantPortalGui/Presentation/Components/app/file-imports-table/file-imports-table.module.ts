import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { TableModule } from '@spryker/table';

import { FileImportsTableComponent } from './file-imports-table.component';

@NgModule({
    imports: [CommonModule, TableModule],
    declarations: [FileImportsTableComponent],
    exports: [FileImportsTableComponent],
})
export class FileImportsTableModule {}
