import { ChangeDetectionStrategy, Component, Input, ViewEncapsulation } from '@angular/core';
import { ToBoolean } from '@spryker/utils';

@Component({
    selector: 'mp-content-toggle',
    templateUrl: './content-toggle.component.html',
    styleUrls: ['./content-toggle.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
    host: { class: 'mp-content-toggle' },
})
export class ContentToggleComponent {
    @Input() name = '';
    @Input() @ToBoolean() isContentHidden = true;

    handleCheckChange(checked: boolean): void {
        this.isContentHidden = checked;
    }
}
