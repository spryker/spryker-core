import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { HeadlineModule } from '@spryker/headline';
import { ChangePasswordOverlayComponent } from './change-password-overlay.component';

@NgModule({
    imports: [CommonModule, HeadlineModule],
    declarations: [ChangePasswordOverlayComponent],
    exports: [ChangePasswordOverlayComponent],
})
export class ChangePasswordOverlayModule {}
