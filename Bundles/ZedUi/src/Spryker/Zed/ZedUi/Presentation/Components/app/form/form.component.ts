import { ChangeDetectionStrategy, Component, Input, ViewEncapsulation, ViewChild } from '@angular/core';
import { ToJson, ToBoolean } from '@spryker/utils';
import { UnsavedChangesFormMonitorDirective } from '@spryker/unsaved-changes.monitor.form';

@Component({
    selector: 'mp-form',
    templateUrl: './form.component.html',
    styleUrls: ['./form.component.less'],
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
