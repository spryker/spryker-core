import { CommonModule } from '@angular/common';
import { NgModule } from '@angular/core';
import { HeadlineModule } from '@spryker/headline';

import { PaymentComponent } from './payment.component';

@NgModule({
    imports: [CommonModule, HeadlineModule],
    declarations: [PaymentComponent],
    exports: [PaymentComponent],
})
export class PaymentModule {}
