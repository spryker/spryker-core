import { ChangeDetectionStrategy, Component, Input, ViewChild, ViewEncapsulation } from '@angular/core';
import { UnsavedChangesFormMonitorDirective } from '@spryker/unsaved-changes.monitor.form';
import { ToBoolean, ToJson } from '@spryker/utils';

@Component({
    selector: 'mp-form',
    templateUrl: './form.component.html',
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
})
export class FormComponent {
    @ViewChild(UnsavedChangesFormMonitorDirective)
    unsavedChangesFormMonitorDirective?: UnsavedChangesFormMonitorDirective;

    @Input() action?: string;
    @Input() method?: string;
    @Input() name?: string;
    @Input() @ToJson() attrs: Record<string, string> = {};
    @Input() @ToBoolean() withMonitor = false;

    submitHandler() {
        this.unsavedChangesFormMonitorDirective?.reset();
    }
}
