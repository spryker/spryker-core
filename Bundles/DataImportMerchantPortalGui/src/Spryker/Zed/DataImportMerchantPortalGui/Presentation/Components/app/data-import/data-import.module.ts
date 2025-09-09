import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { HeadlineModule } from '@spryker/headline';
import { DataImportComponent } from './data-import.component';

@NgModule({
    imports: [CommonModule, HeadlineModule],
    declarations: [DataImportComponent],
    exports: [DataImportComponent],
})
export class DataImportModule {}
