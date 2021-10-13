import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { CheckboxModule } from '@spryker/checkbox';
import { ContentToggleComponent } from './content-toggle.component';

@NgModule({
    imports: [CommonModule, CheckboxModule],
    declarations: [ContentToggleComponent],
    exports: [ContentToggleComponent],
})
export class ContentToggleModule {}
