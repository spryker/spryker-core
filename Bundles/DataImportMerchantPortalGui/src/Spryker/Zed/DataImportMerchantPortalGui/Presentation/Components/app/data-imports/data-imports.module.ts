import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { HeadlineModule } from '@spryker/headline';
import { DataImportsComponent } from './data-imports.component';
import { TableModule } from '@spryker/table';

@NgModule({
    imports: [CommonModule, TableModule, HeadlineModule],
    declarations: [DataImportsComponent],
    exports: [DataImportsComponent],
})
export class DataImportsModule {}
