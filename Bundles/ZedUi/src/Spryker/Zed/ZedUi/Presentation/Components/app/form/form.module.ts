import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ApplyAttrsModule } from '@spryker/utils';
import { UnsavedChangesFormMonitorModule } from '@spryker/unsaved-changes.monitor.form';

import { FormComponent } from './form.component';

@NgModule({
    imports: [CommonModule, ApplyAttrsModule, UnsavedChangesFormMonitorModule],
    declarations: [FormComponent],
    exports: [FormComponent],
})
export class FormModule {
}
