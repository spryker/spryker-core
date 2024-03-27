import { CommonModule } from '@angular/common';
import { NgModule } from '@angular/core';
import { SpinnerModule } from '@spryker/spinner';
import { FormSubmitterComponent } from './form-submitter.component';

@NgModule({
    imports: [CommonModule, SpinnerModule],
    declarations: [FormSubmitterComponent],
    exports: [FormSubmitterComponent],
})
export class FormSubmitterModule {}
