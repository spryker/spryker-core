import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ManageOrderComponent } from './manage-order.component';
import { ManageOrderStatsBlockComponent } from './manage-order-stats-block/manage-order-stats-block.component';
import { ManageOrderTotalsComponent } from './manage-order-totals/manage-order-totals.component';
import { IconModule } from '@spryker/icon';
import { IconInfoModule } from '@spryker/icon/icons';

@NgModule({
    imports: [CommonModule, IconModule, IconInfoModule],
    declarations: [
        ManageOrderComponent,
        ManageOrderStatsBlockComponent,
        ManageOrderTotalsComponent,
    ],
    exports: [
        ManageOrderComponent,
        ManageOrderStatsBlockComponent,
        ManageOrderTotalsComponent,
    ],
})
export class ManageOrderModule {
}
