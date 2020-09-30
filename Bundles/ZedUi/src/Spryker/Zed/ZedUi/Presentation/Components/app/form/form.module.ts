import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ApplyAttrsModule } from '@spryker/utils';
import { UnsavedChangesFormMonitorModule } from '@spryker/unsaved-changes.monitor.form';
import { CustomElementBoundaryModule } from '@spryker/web-components';

import { FormComponent } from './form.component';

@NgModule({
    imports: [CommonModule, ApplyAttrsModule, UnsavedChangesFormMonitorModule, CustomElementBoundaryModule],
    declarations: [FormComponent],
    exports: [FormComponent],
})
export class FormModule {
}
