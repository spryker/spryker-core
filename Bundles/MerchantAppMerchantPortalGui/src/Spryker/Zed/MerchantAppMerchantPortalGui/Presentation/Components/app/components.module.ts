import { NgModule } from '@angular/core';
import { ButtonComponent, ButtonLinkComponent, ButtonLinkModule, ButtonModule } from '@spryker/button';
import { CardComponent, CardModule } from '@spryker/card';
import { ChipsComponent, ChipsModule } from '@spryker/chips';
import { IconComponent, IconModule } from '@spryker/icon';
import { WebComponentsModule } from '@spryker/web-components';
import { IconNoDataModule, IconPaymentModule } from '../icons';
import { PaymentComponent } from './payment/payment.component';
import { PaymentModule } from './payment/payment.module';
import { PaymentsComponent } from './payments/payments.component';
import { PaymentsModule } from './payments/payments.module';

@NgModule({
    imports: [
        WebComponentsModule.withComponents([
            PaymentsComponent,
            CardComponent,
            ChipsComponent,
            PaymentComponent,
            ButtonLinkComponent,
            ButtonComponent,
            IconComponent,
        ]),
        ButtonModule,
        ButtonLinkModule,
        CardModule,
        IconModule,
        IconPaymentModule,
        PaymentsModule,
        ChipsModule,
        PaymentModule,
        IconNoDataModule,
    ],
    providers: [],
    declarations: [],
})
export class ComponentsModule {}
