import { ChangeDetectionStrategy, Component, Input, OnChanges, SimpleChanges, ViewEncapsulation } from '@angular/core';

@Component({
    selector: 'mp-autogenerate-input',
    templateUrl: './autogenerate-input.component.html',
    styleUrls: ['./autogenerate-input.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
    host: {
        class: 'mp-autogenerate-input',
        '[class.mp-autogenerate-input--half-width]': 'isFieldHasHalfWidth',
    },
})
export class AutogenerateInputComponent implements OnChanges {
    @Input() name: string;
    @Input() value: string;
    @Input() defaultValue: string;
    @Input() placeholder: string;
    @Input() isAutogenerate: boolean;
    @Input() error?: string;
    @Input() checkboxName?: string;
    @Input() isFieldHasHalfWidth?: boolean;

    ngOnChanges(changes: SimpleChanges): void {
        if ('value' in changes) {
            this.defaultValue = this.value;
        }
    }

    onCheckboxChange(checked: boolean): void {
        if (checked) {
            this.value = this.defaultValue;
        }
    }
}
