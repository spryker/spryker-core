import { ChangeDetectionStrategy, Component, ViewEncapsulation } from '@angular/core';

@Component({
    selector: 'mp-payments',
    templateUrl: './payments.component.html',
    styleUrls: ['./payments.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
    host: { class: 'mp-payments' },
})
export class PaymentsComponent {}
