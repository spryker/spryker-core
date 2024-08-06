import { ChangeDetectionStrategy, Component, ViewEncapsulation } from '@angular/core';

@Component({
    selector: 'mp-payment',
    templateUrl: './payment.component.html',
    styleUrls: ['./payment.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
    host: { class: 'mp-payment' },
})
export class PaymentComponent {}
