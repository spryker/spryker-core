import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ManageOrderComponent } from './manage-order.component';
import { ManageOrderStatsBlockComponent } from '../manage-order-stats-block/manage-order-stats-block.component';
import { ManageOrderTotalsComponent } from '../manage-order-totals/manage-order-totals.component';

@NgModule({
    imports: [CommonModule],
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
