import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { MfaHandlerComponent } from './mfa-handler.component';

@NgModule({
    imports: [CommonModule],
    declarations: [MfaHandlerComponent],
    exports: [MfaHandlerComponent],
})
export class MfaHandlerModule {}
