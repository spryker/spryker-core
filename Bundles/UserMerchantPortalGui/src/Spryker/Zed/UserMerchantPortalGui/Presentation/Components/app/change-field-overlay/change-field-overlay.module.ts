import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { HeadlineModule } from '@spryker/headline';
import { ChangeFieldOverlayComponent } from './change-field-overlay.component';

@NgModule({
    imports: [CommonModule, HeadlineModule],
    declarations: [ChangeFieldOverlayComponent],
    exports: [ChangeFieldOverlayComponent],
})
export class ChangeFieldOverlayModule {}
