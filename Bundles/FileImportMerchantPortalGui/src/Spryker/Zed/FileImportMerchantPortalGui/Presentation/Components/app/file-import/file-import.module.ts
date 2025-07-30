import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { HeadlineModule } from '@spryker/headline';
import { FileImportComponent } from './file-import.component';

@NgModule({
    imports: [CommonModule, HeadlineModule],
    declarations: [FileImportComponent],
    exports: [FileImportComponent],
})
export class FileImportModule {}
