import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { HeadlineModule } from '@spryker/headline';
import { FileImportsComponent } from './file-imports.component';
import { FileImportsTableModule } from '../file-imports-table/file-imports-table.module';

@NgModule({
    imports: [CommonModule, FileImportsTableModule, HeadlineModule],
    declarations: [FileImportsComponent],
    exports: [FileImportsComponent],
})
export class FileImportsModule {}
