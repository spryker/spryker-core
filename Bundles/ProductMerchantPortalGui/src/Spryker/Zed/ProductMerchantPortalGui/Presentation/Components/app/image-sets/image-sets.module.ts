import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ImageSetsComponent } from './image-sets.component';
import { TableModule } from '@spryker/table';

@NgModule({
    imports: [CommonModule, TableModule],
    declarations: [ImageSetsComponent],
    exports: [ImageSetsComponent],
})
export class ImageSetsModule {}
