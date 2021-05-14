import {
    ChangeDetectionStrategy,
    Component,
    HostBinding,
    Input,
    OnChanges,
    SimpleChanges,
    ViewEncapsulation,
} from '@angular/core';
import { ToBoolean } from '@spryker/utils';

@Component({
    selector: 'mp-autogenerate-input',
    templateUrl: './autogenerate-input.component.html',
    styleUrls: ['./autogenerate-input.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
    host: { class: 'mp-autogenerate-input' },
})
export class AutogenerateInputComponent implements OnChanges {
    @Input() name: string;
    @Input() value: string;
    @Input() originalValue: string;
    @Input() placeholder: string;
    @Input() @ToBoolean() isAutogenerate = true;
    @Input() error?: string;
    @Input() checkboxName?: string;
    @Input()
    @HostBinding('class.mp-autogenerate-input--half-width')
    @ToBoolean()
    isFieldHasHalfWidth? = false;

    private defaultValue: string;

    ngOnChanges(changes: SimpleChanges): void {
        if ('value' in changes) {
            this.defaultValue = this.value;
        }
    }

    onCheckboxChange(checked: boolean): void {
        if (checked) {
            this.value = this.defaultValue?.length ? this.defaultValue : this.originalValue;
        }
    }
}
