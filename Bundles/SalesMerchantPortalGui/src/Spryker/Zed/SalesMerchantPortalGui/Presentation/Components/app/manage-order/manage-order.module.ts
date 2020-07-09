import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ManageOrderComponent } from './manage-order.component';
import { ManageOrderStatsBlockComponent } from '../manage-order-stats-block/manage-order-stats-block.component';

@NgModule({
    imports: [CommonModule],
    declarations: [ManageOrderComponent, ManageOrderStatsBlockComponent],
    exports: [ManageOrderComponent, ManageOrderStatsBlockComponent],
})
export class ManageOrderModule {
}
